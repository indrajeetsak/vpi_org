<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockModel extends Model
{
    protected $table            = 'blocks'; // Make sure this table exists
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['district_id', 'name', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at']; // Added timestamps

// Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';    
    public function getBlocksByDistrict(int $districtId): array
    {
        return $this->where('district_id', $districtId)->orderBy('name', 'ASC')->findAll();
    }
}