<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'date_of_birth', 'approval_date'];
    protected $casts   = [
        'is_admin' => 'boolean',
        'is_atheist' => 'boolean'
    ];

    public function setPassword(string $pass) {
        $this->attributes['password'] = password_hash($pass, PASSWORD_DEFAULT);
        return $this;
    }
}