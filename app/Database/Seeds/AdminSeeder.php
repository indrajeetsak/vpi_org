<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Super Admin',
            'email'      => 'super-admin@votersparty.in',
            'mobile'     => '9999999999',
            'password'   => password_hash('password', PASSWORD_DEFAULT),
            'admin_type' => 1, // Super Admin
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Check if admin already exists
        $db = \Config\Database::connect();
        $builder = $db->table('admins');
        
        if ($builder->where('email', $data['email'])->countAllResults() == 0) {
            $builder->insert($data);
            echo "Super Admin seeded successfully.\n";
        } else {
            echo "Super Admin already exists.\n";
        }
    }
}
