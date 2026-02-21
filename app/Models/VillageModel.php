<?php

namespace App\Models;

use CodeIgniter\Model;

class VillageModel extends Model
{
    protected $table            = 'villages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'sector_id', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Get all villages for a specific sector
     * 
     * @param int $sectorId
     * @return array
     */
    public function getVillagesBySector(int $sectorId): array
    {
        return $this->where('sector_id', $sectorId)->orderBy('name', 'ASC')->findAll();
    }
}
