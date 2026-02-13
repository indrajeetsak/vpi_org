<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAdminPassword extends Seeder
{
    public function run()
    {
        $password = 'admin123';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->db->table('users')
            ->where('mobile', '9999999999')
            ->update(['password' => $hash]);

        // Verify the update
        $user = $this->db->table('users')
            ->where('mobile', '9999999999')
            ->get()
            ->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            echo "Password updated and verified successfully.\n";
        } else {
            echo "Password update failed or verification failed.\n";
        }
    }
}
