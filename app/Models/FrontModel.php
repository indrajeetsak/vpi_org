<?php

namespace App\Models;

use CodeIgniter\Model;

class FrontModel extends Model
{
    protected $table            = 'fronts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllFronts()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }
}
