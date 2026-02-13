<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateAdminUser extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel();
        
        // Check if admin user already exists
        $existingUser = $userModel->where('mobile', '9999999999')->first();
        
        if (!$existingUser) {
            $data = [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'mobile' => '9999999999',
                'password' => 'admin123', // This will be hashed by the model
                'date_of_birth' => '1990-01-01',
                'gender' => 'male',
                'father_name' => 'Admin Father',
                'mother_name' => 'Admin Mother',
                'aadhaar_number' => '111122223333',
                'state_id' => 1, // Uttar Pradesh
                'district_id' => 1, // Lucknow
                'mla_area_id' => 1, // Lucknow
                'block_id' => 1, // Lucknow
                'address_line1' => 'Admin Address Line 1',
                'address_line2' => 'Admin Address Line 2',
                'pin_code' => '226001',
                'organ_id' => 1, // Main Committee
                'level_id' => 1, // National
                'post_id' => 1, // Administrator
                'status' => 'approved',
                'is_admin' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $userModel->insert($data);
            echo "Admin user created successfully.\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}
