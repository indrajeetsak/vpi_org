<?php

namespace App\Models;

use CodeIgniter\Model;

class PollingBoothModel extends Model
{
    protected $table            = 'polling_booths';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['mla_area_id', 'name', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPollingBoothsByMlaArea(int $mlaAreaId): array
    {
        return $this->where('mla_area_id', $mlaAreaId)->orderBy('name', 'ASC')->findAll();
    }
}
