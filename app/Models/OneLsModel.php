<?php

namespace App\Models;

use CodeIgniter\Model;

class OneLsModel extends Model
{
    protected $table = 'ls';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', '2ls_id', 'state_id', 'code', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all 1 Loksabha constituencies for a specific 2 Loksabha
     * 
     * @param int $twoLsId
     * @return array
     */
    public function get1LsByTwoLs(int $twoLsId): array
    {
        return $this->where('2ls_id', $twoLsId)->orderBy('name', 'ASC')->findAll();
    }
}
