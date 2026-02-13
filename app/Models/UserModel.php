<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'mobile', 'alternate_mobile',
        'password',
        'date_of_birth',
        'gender',
        'father_name',
        'mother_name',
        'aadhaar_number',
        'address_line1',
        'address_line2',
        'pin_code',
        'photo',
        'state_id',
        'district_id',
        'mla_area_id',
        'block_id',
        'payment_status',
        'transaction_id',
        'added_by',
        'added_by_name',
        'edited_by',
        'edited_by_name',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
}
