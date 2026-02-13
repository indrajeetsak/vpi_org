<?php

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
    protected $table = 'states';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['name', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]'
    ];
}