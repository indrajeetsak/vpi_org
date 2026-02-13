<?php

namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table            = 'districts'; // Make sure this table exists
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['state_id', 'name', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at']; // Add other fields as necessary

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDistrictsByState(int $stateId): array
    {
        return $this->where('state_id', $stateId)->orderBy('name', 'ASC')->findAll();
    }
}