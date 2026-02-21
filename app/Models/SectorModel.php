<?php

namespace App\Models;

use CodeIgniter\Model;

class SectorModel extends Model
{
    protected $table            = 'sectors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'block_id', 'circle_id', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Get all sectors for a specific block
     * 
     * @param int $blockId
     * @return array
     */
    public function getSectorsByBlock(int $blockId): array
    {
        return $this->where('block_id', $blockId)->orderBy('name', 'ASC')->findAll();
    }
}
