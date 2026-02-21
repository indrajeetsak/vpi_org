<?php

/**
 * ============================================================
 * VPI-OB — Browser-Based Database Migration Runner
 * ============================================================
 * USAGE:
 *   1. Upload this file into the `public/` folder on your
 *      hosting (e.g. public_html/public/ or wherever
 *      org.votersparty.in points to).
 *   2. Visit: https://org.votersparty.in/migrate_run.php?token=vpi_migrate_2026
 *   3. ⚠️  DELETE THIS FILE immediately after migration is done.
 *
 * SECURITY:
 *   Set a secret token below. The URL must include
 *   ?token=YOUR_SECRET_TOKEN or the script will refuse to run.
 * ============================================================
 */

// ─── CONFIGURE THIS ────────────────────────────────────────
define('SECRET_TOKEN', 'vpi_migrate_2026');   // change this!
// ───────────────────────────────────────────────────────────

// ----------------------------------------------------------
// Security gate
// ----------------------------------------------------------
if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:sans-serif;color:red">❌ Forbidden. Missing or wrong token.</h2>');
}

// ----------------------------------------------------------
// Bootstrap CodeIgniter
// The script lives in public/ so parent dir is the project root
// ----------------------------------------------------------
$projectRoot = dirname(__DIR__);   // one level up from public/

define('FCPATH',    __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', $projectRoot . '/vendor/codeigniter4/framework/system/');
define('APPPATH',    $projectRoot . '/app/');
define('WRITEPATH',  $projectRoot . '/writable/');
define('ROOTPATH',   $projectRoot . '/');

if (!is_dir(SYSTEMPATH)) {
    die('<h2 style="font-family:sans-serif;">❌ Cannot find CodeIgniter framework at: ' . SYSTEMPATH . '<br>Make sure vendor/ exists on the server.</h2>');
}


// Suppress PHP notices/warnings from CI4 running outside HTTP context
error_reporting(E_ERROR | E_PARSE);

// Fake CLI environment that CI4 needs
$_SERVER['argv']    = ['migrate_run.php'];
$_SERVER['argc']    = 1;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
if (!isset($_SERVER['HTTP_HOST'])) $_SERVER['HTTP_HOST'] = 'localhost';

// ----------------------------------------------------------
// Pretty HTML output helper
// ----------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DB Migration — VPI-OB</title>
<style>
  * { box-sizing: border-box; }
  body { font-family: 'Segoe UI', sans-serif; background: #0f172a; color: #e2e8f0; margin: 0; padding: 30px; }
  h1 { color: #818cf8; font-size: 1.6rem; margin-bottom: 5px; }
  .subtitle { color: #64748b; font-size: 0.9rem; margin-bottom: 25px; }
  .card { background: #1e293b; border: 1px solid #334155; border-radius: 10px; padding: 20px 25px; margin-bottom: 20px; }
  .log { background: #0f172a; border: 1px solid #1e293b; border-radius: 8px; padding: 15px; font-family: monospace; font-size: 0.85rem; white-space: pre-wrap; word-break: break-word; line-height: 1.6; max-height: 500px; overflow-y: auto; }
  .ok   { color: #34d399; }
  .warn { color: #fbbf24; }
  .err  { color: #f87171; }
  .info { color: #60a5fa; }
  .tag  { display:inline-block; padding: 2px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; }
  .tag-ok   { background:#064e3b; color:#34d399; }
  .tag-warn { background:#78350f; color:#fbbf24; }
  .tag-err  { background:#7f1d1d; color:#f87171; }
  .summary  { display:flex; gap:15px; flex-wrap:wrap; margin-top: 15px; }
  .stat     { background:#0f172a; border-radius:8px; padding:12px 20px; text-align:center; flex:1; min-width:120px; }
  .stat .num { font-size:2rem; font-weight:700; }
  .stat .lbl { font-size:0.75rem; color:#64748b; text-transform:uppercase; letter-spacing:.05em; }
  .warn-box { background:#78350f33; border:1px solid #78350f; border-radius:8px; padding:12px 16px; color:#fbbf24; font-size:0.85rem; margin-top:15px; }
</style>
</head>
<body>
<h1>🗄️ VPI-OB — Database Migration Runner</h1>
<p class="subtitle">Running all pending CodeIgniter migrations…</p>

<?php

// ----------------------------------------------------------
// Capture migration output
// ----------------------------------------------------------
$output   = [];
$errors   = [];
$warnings = [];
$applied  = 0;
$skipped  = 0;

try {
    // Check if shell_exec is available
    if (!function_exists('shell_exec') || in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))))) {
        throw new \RuntimeException('shell_exec is disabled on this server. Please run migrations via SSH: php spark migrate');
    }

    // Find PHP binary
    $phpBin = PHP_BINARY ?: 'php';

    // Run spark migrate from the project root
    $cmd    = escapeshellarg($phpBin) . ' ' . escapeshellarg(ROOTPATH . 'spark') . ' migrate --no-ansi 2>&1';
    $rawOut = shell_exec($cmd);

    if ($rawOut === null) {
        $errors[] = 'shell_exec returned null — command may have failed silently.';
    } else {
        // Parse spark output line by line
        $lines = explode("\n", $rawOut);
        foreach ($lines as $line) {
            $line = trim(preg_replace('/\x1B\[[0-9;]*m/', '', $line)); // strip ANSI colors
            if (empty($line)) continue;

            if (stripos($line, 'error') !== false || stripos($line, 'fail') !== false || stripos($line, 'exception') !== false) {
                $errors[] = $line;
            } elseif (stripos($line, 'up to date') !== false || stripos($line, 'no migrations') !== false || stripos($line, 'nothing') !== false) {
                $warnings[] = $line;
            } elseif (stripos($line, 'migrating') !== false || stripos($line, 'migrated') !== false || stripos($line, 'running') !== false || stripos($line, 'done') !== false) {
                $output[] = $line;
                $applied++;
            } else {
                $output[] = $line;
            }
        }

        if (empty($errors) && empty($output) && empty($warnings)) {
            $warnings[] = 'All migrations are already up to date. Nothing new to run.';
        }
    }

} catch (\Throwable $e) {
    $errors[] = get_class($e) . ': ' . $e->getMessage();
}

// ----------------------------------------------------------
// Render results
// ----------------------------------------------------------

$statusTag = !empty($errors)
    ? '<span class="tag tag-err">❌ ERROR</span>'
    : (!empty($warnings) && empty($output) && $applied === 0
        ? '<span class="tag tag-warn">⚠️ UP TO DATE</span>'
        : '<span class="tag tag-ok">✅ SUCCESS</span>');

echo '<div class="card">';
echo '<div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">';
echo '<strong style="font-size:1.1rem;">Result</strong> ' . $statusTag;
echo '</div>';

echo '<div class="summary">';
echo '<div class="stat"><div class="num ok">' . $applied . '</div><div class="lbl">Applied</div></div>';
echo '<div class="stat"><div class="num warn">' . count($warnings) . '</div><div class="lbl">Warnings</div></div>';
echo '<div class="stat"><div class="num err">' . count($errors) . '</div><div class="lbl">Errors</div></div>';
echo '</div>';

echo '</div>'; // .card

// Log output
echo '<div class="card">';
echo '<strong>Migration Log</strong>';
echo '<div class="log" style="margin-top:12px;">';

if (!empty($output)) {
    foreach ($output as $line) {
        echo '<span class="ok">' . htmlspecialchars($line) . '</span>' . "\n";
    }
}
if (!empty($warnings)) {
    foreach ($warnings as $w) {
        echo '<span class="warn">⚠  ' . htmlspecialchars($w) . '</span>' . "\n";
    }
}
if (!empty($errors)) {
    foreach ($errors as $e) {
        echo '<span class="err">✖  ' . htmlspecialchars($e) . '</span>' . "\n";
    }
}
if (empty($output) && empty($warnings) && empty($errors)) {
    echo '<span class="info">No output received from migration runner.</span>' . "\n";
}

echo '</div>'; // .log
echo '</div>'; // .card

// Warning box
echo '<div class="warn-box">';
echo '⚠️  <strong>Security reminder:</strong> Delete <code>migrate_run.php</code> from your server immediately after running migrations!';
echo '</div>';

?>
</body>
</html>
