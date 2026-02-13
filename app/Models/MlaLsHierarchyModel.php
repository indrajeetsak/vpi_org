<?php

namespace App\Models;

use CodeIgniter\Model;

class MlaLsHierarchyModel extends Model
{
    protected $table            = 'mla_ls_hierarchy'; // Make sure this table exists and is correctly named
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'mla_area_id', 
        'ls_id',
        'LS_Name', // Matched to schema
        'two_ls_id',
        'two_LS_Name', // Matched to schema
        'three_ls_id',
        'three_LS_Name', // Matched to schema
        'four_ls_id',
        'four_LS_Name', // Matched to schema
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fetches the LS hierarchy data for a given MLA Area ID.
     *
     * @param int $mlaAreaId
     * @return array|null
     */
    public function getHierarchyByMlaAreaId(int $mlaAreaId): ?array
    {
        // Select specific columns with correct casing
        return $this->select('mla_area_id, ls_id, LS_Name, two_ls_id, two_LS_Name, three_ls_id, three_LS_Name, four_ls_id, four_LS_Name')
                    ->where('mla_area_id', $mlaAreaId)
                    ->first();
    }
}
