<?php

namespace App\Helpers;

/**
 * CommitteeQueryEngine — Gemini-powered NL committee query engine.
 *
 * Gemini handles natural language understanding (intent, level, post, location).
 * PHP handles all SQL execution (safe, no injection risk from LLM output).
 */
class CommitteeQueryEngine
{
    /** @var \CodeIgniter\Database\BaseConnection */
    private $db;

    private string $apiKey;
    private string $geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    // Level name → level_id
    private const LEVEL_IDS = [
        'state'    => 11,
        'district' => 16,
        'mla'      => 6,
        'block'    => 5,
        'town'     => 5,
        'mp'       => 7,
        'sector'   => 3,
    ];

    public function __construct()
    {
        $this->db     = \Config\Database::connect();
        $this->apiKey = env('GEMINI_API_KEY') ?: 'AIzaSyCtprsHU8jCd7DPgulgjnGlFlIQmUIzIdw';
    }

    // ─────────────────────────────────────────────────────────────
    // PUBLIC ENTRY POINT
    // ─────────────────────────────────────────────────────────────

    public function parse(string $question): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'GEMINI_API_KEY is not configured.', 'answer' => '', 'results' => [], 'type' => 'error'];
        }

        // 1. Let Gemini extract structured params from the question
        $params = $this->extractWithGemini($question);

        // If Gemini fails, fall back to rule-based parsing
        if (isset($params['error'])) {
            $fallback = $this->extractWithRules($question);
            if (!isset($fallback['error'])) {
                $params = $fallback;
                $params['_fallback'] = true;
            } else {
                return ['error' => $params['error'], 'answer' => '', 'results' => [], 'type' => 'error'];
            }
        }

        // 2. Resolve location names to DB IDs
        $locationFilters = $this->resolveLocations($params['locations'] ?? []);

        // 3. Resolve post name to post ID
        $postFilter = $this->resolvePost($params['post'] ?? null);

        // 4. Resolve level
        $levelId = null;
        if (!empty($params['level'])) {
            $levelKey = mb_strtolower(trim($params['level']));
            foreach (self::LEVEL_IDS as $kw => $id) {
                if (str_contains($levelKey, $kw)) { $levelId = $id; break; }
            }
        }

        // 5. Build and run the DB query
        return $this->buildAnswer(
            $params['intent']   ?? 'list',
            $levelId,
            $postFilter,
            $locationFilters,
            $params['post']     ?? null
        );
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-BASED FALLBACK (used when Gemini API is unavailable)
    // ─────────────────────────────────────────────────────────────

    private function extractWithRules(string $question): array
    {
        $q = mb_strtolower(trim($question));

        // Intent
        $intent = 'list';
        if (preg_match('/\b(who is|who are|who\'s)\b/', $q)) $intent = 'who';
        elseif (preg_match('/\b(how many|count|total|number of)\b/', $q)) $intent = 'count';

        // Level
        $level = null;
        $levelMap = ['sector' => 'sector', 'block' => 'block', 'district' => 'district',
                     'state' => 'state', 'mla' => 'mla', 'assembly' => 'mla',
                     'mp' => 'mp', 'loksabha' => 'mp', 'lok sabha' => 'mp'];
        foreach ($levelMap as $kw => $lvl) {
            if (str_contains($q, $kw)) { $level = $lvl; break; }
        }

        // Post: scan post tables
        $postName = null;
        $postTables = ['action_level_posts', 'constituency_level_posts', 'governing_level_posts'];
        $allPosts = [];
        foreach ($postTables as $t) {
            try {
                $rows = $this->db->table($t)->select('name')->get()->getResultArray();
                foreach ($rows as $r) $allPosts[] = $r['name'];
            } catch (\Exception $e) {}
        }
        usort($allPosts, fn($a, $b) => strlen($b) - strlen($a)); // longest first
        foreach ($allPosts as $p) {
            if (preg_match('/\b' . preg_quote(mb_strtolower($p), '/') . '\b/u', $q)) {
                $postName = $p; break;
            }
        }

        // Locations: scan all location tables with word-boundary match + de-dup
        $locationTables = [
            'sectors', 'blocks', 'mla_area', 'ls', 'districts', 'states'
        ];
        $rawFound = [];
        foreach ($locationTables as $t) {
            try {
                $rows = $this->db->table($t)->select('name')->get()->getResultArray();
                foreach ($rows as $r) {
                    $locLower = mb_strtolower($r['name']);
                    if (preg_match('/\b' . preg_quote($locLower, '/') . '\b/u', $q)) {
                        $rawFound[] = $r['name'];
                    }
                }
            } catch (\Exception $e) {}
        }

        // Remove names that are pure substrings of other matched names
        $locations = [];
        foreach ($rawFound as $name) {
            $isSubstr = false;
            foreach ($rawFound as $other) {
                if ($name !== $other && str_contains(mb_strtolower($other), mb_strtolower($name))) {
                    $isSubstr = true; break;
                }
            }
            if (!$isSubstr) $locations[] = $name;
        }

        return [
            'intent'    => $intent,
            'level'     => $level,
            'post'      => $postName,
            'locations' => array_values(array_unique($locations)),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // GEMINI API CALL
    // ─────────────────────────────────────────────────────────────

    private function extractWithGemini(string $question): array
    {
        $systemPrompt = <<<PROMPT
You are a query parser for an Indian political organization's committee database.

Given a natural language question about committees, extract the following fields:
- intent: one of "who", "count", or "list"
- level: the committee level mentioned — one of: "state", "district", "mla", "block", "mp", "sector", or null
- post: the post/designation mentioned (e.g. "President", "Secretary", "Treasurer"), or null
- locations: an array of location names mentioned (e.g. ["Goalpara", "Assam"]) — just the plain names, no type labels

Return ONLY a valid JSON object with these four keys. No explanation, no markdown, no code fences.

Example input: "Who is president of Goalpara district committee in Assam?"
Example output: {"intent":"who","level":"district","post":"President","locations":["Goalpara","Assam"]}

Example input: "How many posts are occupied in bajli block?"
Example output: {"intent":"count","level":"block","post":null,"locations":["Bajli"]}

Example input: "List all members of the state committee"
Example output: {"intent":"list","level":"state","post":null,"locations":[]}

Question: {$question}
PROMPT;

        $payload = json_encode([
            'contents' => [[
                'parts' => [['text' => $systemPrompt]]
            ]]
        ]);

        $ch = curl_init($this->geminiEndpoint . '?key=' . $this->apiKey);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT        => 15,
        ]);

        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            return ['error' => 'Network error calling Gemini: ' . $curlErr];
        }
        if ($httpCode !== 200) {
            $decoded = json_decode($raw, true);
            $msg = $decoded['error']['message'] ?? "HTTP {$httpCode}";
            return ['error' => 'Gemini API error: ' . $msg];
        }

        // Parse Gemini response
        $decoded = json_decode($raw, true);
        $text    = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Strip any accidental markdown fences
        $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
        $text = preg_replace('/\s*```$/', '', $text);

        $params = json_decode(trim($text), true);

        if (!is_array($params)) {
            return ['error' => 'Gemini returned unreadable response: ' . substr($text, 0, 200)];
        }

        return $params;
    }

    // ─────────────────────────────────────────────────────────────
    // LOCATION RESOLUTION  (name → DB id + table)
    // ─────────────────────────────────────────────────────────────

    private function resolveLocations(array $locationNames): array
    {
        if (empty($locationNames)) return [];

        $tables = [
            ['table' => 'states',    'field' => 'state_id'],
            ['table' => 'districts', 'field' => 'district_id'],
            ['table' => 'blocks',    'field' => 'block_id'],
            ['table' => 'mla_area',  'field' => 'mla_area_id'],
            ['table' => 'ls',        'field' => 'ls_id'],
            ['table' => 'sectors',   'field' => 'sector_id'],
        ];

        $found = [];

        foreach ($locationNames as $name) {
            $name = trim($name);
            if (empty($name)) continue;

            foreach ($tables as $t) {
                try {
                    $row = $this->db->table($t['table'])
                        ->select('id, name')
                        ->where("LOWER(name)", mb_strtolower($name))
                        ->limit(1)
                        ->get()->getRowArray();

                    if ($row) {
                        $found[] = [
                            'id'    => $row['id'],
                            'name'  => $row['name'],
                            'table' => $t['table'],
                            'field' => $t['field'],
                        ];
                        break; // found in this table, stop checking others
                    }
                } catch (\Exception $e) {
                    // table doesn't exist, skip
                }
            }
        }

        return $found;
    }

    // ─────────────────────────────────────────────────────────────
    // POST RESOLUTION  (name → post row)
    // ─────────────────────────────────────────────────────────────

    private function resolvePost(?string $postName): ?array
    {
        if (empty($postName)) return null;

        $tables = [
            'action_level_posts',
            'constituency_level_posts',
            'governing_level_posts',
        ];

        foreach ($tables as $table) {
            try {
                $row = $this->db->table($table)
                    ->select('id, name')
                    ->where("LOWER(name)", mb_strtolower($postName))
                    ->limit(1)
                    ->get()->getRowArray();

                if ($row) return ['id' => $row['id'], 'name' => $row['name']];
            } catch (\Exception $e) {}
        }

        // Fallback: partial match
        foreach ($tables as $table) {
            try {
                $row = $this->db->table($table)
                    ->select('id, name')
                    ->like('name', $postName)
                    ->limit(1)
                    ->get()->getRowArray();

                if ($row) return ['id' => $row['id'], 'name' => $row['name']];
            } catch (\Exception $e) {}
        }

        return ['id' => null, 'name' => $postName];
    }

    // ─────────────────────────────────────────────────────────────
    // QUERY BUILDER & ANSWER FORMATTER
    // ─────────────────────────────────────────────────────────────

    private function buildAnswer(string $intent, ?int $levelId, ?array $postFilter, array $locations, ?string $rawPostName): array
    {
        $builder = $this->db->table('appointments');
        $builder->select("
            users.first_name, users.last_name, users.mobile,
            levels.name AS level_name,
            COALESCE(alp.name, clp.name, glp.name) AS post_name,
            COALESCE(s.name,  '') AS state_name,
            COALESCE(d.name,  '') AS district_name,
            COALESCE(b.name,  '') AS block_name,
            COALESCE(m.name,  '') AS mla_name,
            COALESCE(sc.name, '') AS sector_name
        ");
        $builder->join('users',   'users.id = appointments.user_id', 'inner');
        $builder->join('levels',  'levels.id = appointments.level_id', 'left');
        $builder->join('action_level_posts as alp',       'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5',   'left');
        $builder->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10',  'left');
        $builder->join('governing_level_posts as glp',    'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 16', 'left');
        $builder->join('states    as s',  's.id  = appointments.state_id',    'left');
        $builder->join('districts as d',  'd.id  = appointments.district_id', 'left');
        $builder->join('blocks    as b',  'b.id  = appointments.block_id',    'left');
        $builder->join('mla_area  as m',  'm.id  = appointments.mla_area_id', 'left');
        $builder->join('sectors   as sc', 'sc.id = appointments.sector_id',   'left');

        $builder->where('appointments.status', 'approved');

        if ($levelId) {
            $builder->where('appointments.level_id', $levelId);
        }

        // Post filter
        if ($postFilter && $postFilter['id']) {
            $builder->where('appointments.post_id', $postFilter['id']);
        } elseif ($postFilter && $postFilter['name']) {
            $builder->like('COALESCE(alp.name, clp.name, glp.name)', $postFilter['name']);
        }

        // Location filters
        foreach ($locations as $loc) {
            $builder->where("appointments.{$loc['field']}", $loc['id']);
        }

        $rows = $builder->get()->getResultArray();

        // Build location label
        $locationLabel = implode(', ', array_unique(array_column($locations, 'name')));

        if (empty($rows)) {
            return [
                'answer'  => 'No committee members found' . ($locationLabel ? " for **{$locationLabel}**" : '') . '.',
                'results' => [],
                'type'    => 'empty',
            ];
        }

        if ($intent === 'count') {
            $count = count($rows);
            $postLabel = $postFilter ? ' ' . $postFilter['name'] . ' post' . ($count !== 1 ? 's' : '') : ' post' . ($count !== 1 ? 's' : '');
            return [
                'answer'  => 'There ' . ($count === 1 ? 'is' : 'are') . " **{$count}** occupied{$postLabel}" . ($locationLabel ? " in **{$locationLabel}**" : '') . '.',
                'results' => $rows,
                'type'    => 'count',
            ];
        }

        if ($intent === 'who' && $postFilter) {
            $names = array_map(fn($r) => trim($r['first_name'] . ' ' . $r['last_name']), $rows);
            $nameStr = implode(', ', $names);
            return [
                'answer'  => "The **{$postFilter['name']}**" . ($locationLabel ? " of **{$locationLabel}**" : '') . " is **{$nameStr}**.",
                'results' => $rows,
                'type'    => 'who',
            ];
        }

        return [
            'answer'  => 'Found **' . count($rows) . ' member(s)**' . ($locationLabel ? " in **{$locationLabel}**" : '') . '.',
            'results' => $rows,
            'type'    => 'list',
        ];
    }
}
