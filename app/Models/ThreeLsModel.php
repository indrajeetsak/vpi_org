<?php

namespace App\Models;

use CodeIgniter\Model;

class ThreeLsModel extends Model
{
    protected $table = '3ls';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', '4ls_id', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all 3 Loksabha constituencies for a specific 4 Loksabha
     * 
     * @param int $fourLsId
     * @return array
     */
    public function get3LsByFourLs(int $fourLsId): array
    {
        return $this->where('4ls_id', $fourLsId)->orderBy('name', 'ASC')->findAll();
    }
}
