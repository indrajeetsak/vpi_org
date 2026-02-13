<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    // 'organ_id' is added as posts are likely filtered by it.
    protected $allowedFields    = [
        'name', 
        'level_id', // FK to levels table
        // 'organ_id', // FK to organs table (posts will be organ-specific within a level in future)
        'allows_multiple_occupants', 
        'max_occupants',
        'added_by', 'added_by_name', 'edited_by', 'edited_by_name'
    ];

    // Timestamps - assuming they are common across the three post tables
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Determines the correct post table name based on the level ID.
     *
     * @param int $levelId
     * @return string|null The table name or null if levelId is out of defined ranges.
     */
    public function getTableNameForLevel(int $levelId): ?string // Changed to public
    {
        if ($levelId >= 1 && $levelId <= 5) {
            return 'action_level_posts';
        } elseif ($levelId >= 6 && $levelId <= 10) {
            return 'constituency_level_posts';
        } elseif ($levelId >= 11 && $levelId <= 15) {
            return 'governing_level_posts';
        } elseif ($levelId >= 16 && $levelId <= 17) {
            return 'managerial_level_posts';
        }
        log_message('error', "PostModel: No table defined for level_id: {$levelId}");
        return null;
    }

    /**
     * Fetches posts based on level ID from the appropriate table.
     *
     * @param int $levelId
     * @return array
     */
    public function getPostsByLevel(int $levelId): array
    {
        $tableName = $this->getTableNameForLevel($levelId);

        if (!$tableName) {
            log_message('debug', "PostModel: No table determined for levelId: {$levelId}. Returning empty array.");
            return []; // No table found for this level range
        }
log_message('debug', "PostModel: Attempting to query table '{$tableName}' for levelId: {$levelId}");
        $builder = $this->db->table($tableName);
         // The level_id is used to determine the table, no further filtering by level_id is needed on the post table itself.
        $builder->orderBy('id', 'ASC'); // Good practice to order results

       // For debugging, you can log the SQL query string:
        // log_message('debug', "PostModel SQL Query for table '{$tableName}': " . $builder->getCompiledSelect());

        try {
            $query = $builder->get();
            return $query->getResult($this->returnType);
        } catch (\Throwable $e) {
            // Log the actual database error
            log_message('error', "PostModel DB Error for table '{$tableName}', levelId '{$levelId}': " . $e->getMessage());
            // Re-throw to allow CodeIgniter's default error handling to take over (which results in the 500 page)
            throw $e;
        } 
    }
 /**
     * Fetches a single post by its ID and level ID (to determine the table).
     *
     * @param int $postId
     * @param int $levelId
     * @return array|null
     */
    public function getPostByIdAndLevel(int $postId, int $levelId): ?array
    {
        $tableName = $this->getTableNameForLevel($levelId);
        if (!$tableName) {
            log_message('error', "PostModel: No table defined for level_id: {$levelId} when trying to fetch post ID: {$postId}");
            return null;
        }
        return $this->db->table($tableName)->where('id', $postId)->get()->getRowArray();
    }

    // Note: Standard Model methods like find(), findAll(), insert(), update(), delete()
    // will require special handling if used directly, as $this->table is not
    // statically defined. You would typically need to set $this->table dynamically
    // before calling them or create more specialized methods.
}