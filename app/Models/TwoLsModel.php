<?php

namespace App\Models;

use CodeIgniter\Model;

class TwoLsModel extends Model
{
    protected $table = '2ls';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Name', '3ls_id', 'code', 'map', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all 2 Loksabha constituencies for a specific 3 Loksabha
     * 
     * @param int $threeLsId
     * @return array
     */
    public function get2LsByThreeLs(int $threeLsId): array
    {
        return $this->where('3ls_id', $threeLsId)->orderBy('Name', 'ASC')->findAll();
    }
}
