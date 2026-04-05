<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'member_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1', '2', '3', '4', '5'],
                'default'    => '1',
                'comment'    => '0-> Zero Member, 1 -> Primary Member, 2 -> Donor, 3 -> Office Bearer, 4 -> VPI Admin, 5 -> VPI Super Admin'
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'default'    => '0',
            ],
            'referral_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'unique'     => true,
            ],
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
                'unique'     => true,
            ],
            'alternate_mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['male', 'female', 'other'],
                'null'       => true,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'father_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'mother_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'aadhaar_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
                'null'       => true,
                'unique'     => true,
            ],
            'pm_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'company_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'state_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'district_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'mla_area_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'block_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'address_line1' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'address_line2' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pin_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => true,
                
            ],
            'voter_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'added_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'added_by_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'edited_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'edited_by_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('state_id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('mla_area_id');
        $this->forge->addKey('block_id');
        
        $this->forge->createTable('master_users');
    }

    public function down()
    {
        $this->forge->dropTable('master_users', true);
    }
}
