<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncUsers extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'sync:users';
    protected $description = 'Migrate and merge data from vpi_ob.users and vpi-members.users into users';

    public function run(array $params)
    {
        $db1 = \Config\Database::connect(); // vpi_ob (default)
        $db2 = \Config\Database::connect([
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'vpi-members',
            'DBDriver' => 'MySQLi',
            'charset'  => 'utf8mb4',
            'DBCollat' => 'utf8mb4_general_ci'
        ]);

        $masterBuilder = $db1->table('users');
        $obUsersBuilder = $db1->table('users');
        $donorsBuilder = $db2->table('users');

        CLI::write("Migrating Office Bearers from vpi_ob.users...", 'green');
        
        // 1. Move Office Bearers (Type 5)
        $obUsers = $obUsersBuilder->get()->getResultArray();
        CLI::write("Found " . count($obUsers) . " Office Bearers.");
        
        $insertedCount = 0;
        foreach ($obUsers as $user) {
            $data = $user;
            // Ensure type is 3 (Office Bearer)
            $data['type'] = '3';
            
            // Set empty strings to null for UNIQUE constraints
            $data['email'] = empty(trim((string)$data['email'])) ? null : trim($data['email']);
            $data['mobile'] = empty(trim((string)$data['mobile'])) ? null : trim($data['mobile']);
            $data['aadhaar_number'] = empty(trim((string)$data['aadhaar_number'])) ? null : trim($data['aadhaar_number']);

            // Insert into users, preserving ID to maintain relations
            try {
                $masterBuilder->insert($data);
                $insertedCount++;
            } catch (\Exception $e) {
                CLI::write("Error inserting OB ID {$data['id']}: " . $e->getMessage(), 'red');
            }
        }
        
        CLI::write("Inserted $insertedCount Office Bearers into users.");

        CLI::write("Migrating Donors from vpi-members.users...", 'green');
        
        // 2. Move Others
        $donors = $donorsBuilder->get()->getResultArray();
        CLI::write("Found " . count($donors) . " Donors.");
        
        $donorInsertedCount = 0;
        $donorUpdatedCount = 0;

        foreach ($donors as $donor) {
            $email = empty(trim((string)$donor['email'])) ? null : trim($donor['email']);
            $mobile = empty(trim((string)$donor['mobile'])) ? null : trim($donor['mobile']);
            
            // Ensure gender matches ENUM constraint ('male','female','other')
            $gender = null;
            if (!empty($donor['gender'])) {
                $gender = strtolower(trim($donor['gender']));
                if (!in_array($gender, ['male', 'female', 'other'])) {
                    $gender = null;
                }
            }

            $data = [
                'member_id'    => $donor['member_id'],
                'type'         => !empty($donor['type']) ? $donor['type'] : '1',
                'status'       => !empty($donor['status']) ? $donor['status'] : '0',
                'referral_code'=> $donor['referral_code'],
                'first_name'   => $donor['first_name'],
                'last_name'    => $donor['last_name'],
                'gender'       => $gender,
                'father_name'  => $donor['father_name'],
                'mobile'       => $mobile,
                'email'        => $email,
                'pm_no'        => $donor['pm_no'],
                'password'     => $donor['password'],
                'company_id'   => $donor['company_id'],
                'created_at'   => $donor['created_at'],
                'updated_at'   => $donor['updated_at']
            ];

            // If date_of_birth is valid date
            if (!empty($donor['date_of_birth']) && strtotime($donor['date_of_birth'])) {
                $data['date_of_birth'] = date('Y-m-d', strtotime($donor['date_of_birth']));
            } else {
                $data['date_of_birth'] = null;
            }

            // Find matching user in users by email or mobile
            $existingUser = null;
            if ($email || $mobile) {
                $query = $db1->table('users')->groupStart();
                if ($email) {
                    $query->orWhere('email', $email);
                }
                if ($mobile) {
                    $query->orWhere('mobile', $mobile);
                }
                $query->groupEnd();
                $existingUser = $query->get()->getRowArray();
            }

            if ($existingUser) {
                // Update missing info
                $updateData = [];
                foreach ($data as $key => $val) {
                    if (empty($existingUser[$key]) && !empty($val)) {
                        $updateData[$key] = $val;
                    }
                }
                
                // Do NOT overwrite 'type' if existing user is '3' (Office Bearer)
                if (isset($existingUser['type']) && $existingUser['type'] == '3') {
                    unset($updateData['type']);
                }

                if (!empty($updateData)) {
                    try {
                        $db1->table('users')->where('id', $existingUser['id'])->update($updateData);
                        $donorUpdatedCount++;
                    } catch (\Exception $e) {
                         CLI::write("Error updating Donor {$data['first_name']}: " . $e->getMessage(), 'red');
                    }
                }
            } else {
                // Insert new (let auto-increment assign ID)
                try {
                    $db1->table('users')->insert($data);
                    $donorInsertedCount++;
                } catch (\Exception $e) {
                    CLI::write("Error inserting Donor {$data['first_name']}: " . $e->getMessage(), 'red');
                }
            }
        }
        
        CLI::write("Inserted $donorInsertedCount Donors, updated $donorUpdatedCount existing users.", 'green');
        CLI::write("Sync complete!", 'green');
    }
}
