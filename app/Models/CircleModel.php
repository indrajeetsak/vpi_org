<?php

namespace App\Models;

use CodeIgniter\Model;

class CircleModel extends Model
{
    protected $table = 'circles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
    protected $useTimestamps = true;
}
