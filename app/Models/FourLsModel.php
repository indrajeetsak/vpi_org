<?php

namespace App\Models;

use CodeIgniter\Model;

class FourLsModel extends Model
{
    protected $table = '4ls';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Name', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name'];
}
