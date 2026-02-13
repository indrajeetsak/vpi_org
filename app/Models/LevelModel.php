<?php

namespace App\Models;

use CodeIgniter\Model;

class LevelModel extends Model
{
    protected $table = 'levels';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'description', 'status', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at', 'deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'status' => 'permit_empty|in_list[active,inactive]'
    ];
}
