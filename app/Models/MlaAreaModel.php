<?php

namespace App\Models;

use CodeIgniter\Model;

class MlaAreaModel extends Model
{
    protected $table            = 'mla_area'; // Make sure this table exists
   protected $primaryKey = 'id'; // Or your actual primary key
   protected $useAutoIncrement = true; // Explicitly set, default is true
    protected $returnType = 'array';
    protected $allowedFields    = ['district_id', 'name', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at'; // Matched to schema

    public function getMlaAreasByDistrict(int $districtId): array
    {
        // Align with schema and $allowedFields
        return $this->where('district_id', $districtId)->orderBy('name', 'ASC')->findAll();
    }
}