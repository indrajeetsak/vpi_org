<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Migration_2025_06_06_095000_CreateUsersTable extends Migration
{    public function up()
    {
        // Drop the table if it exists
        if ($this->db->tableExists('users')) {
            $this->forge->dropTable('users', true);
        }
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'unique' => true,
            ],
            'alternate_mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female', 'other'],
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'father_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mother_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'aadhaar_number' => [
                'type' => 'VARCHAR',
                'constraint' => 12,
                'unique' => true,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'district' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mla_area' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'block' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'ls' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            '2ls' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            '3ls' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            '4ls' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'address_line1' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'address_line2' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pin_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
            ],
            'organ' => [
                'type' => 'ENUM',
                'constraint' => ['Main Committee', 'Front', 'Cell'],
                'null' => true,
            ],
            'level' => [
                'type' => 'ENUM',
                'constraint' => ['National', 'State', 'District'],
                'null' => true,
            ],
            'post' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'suspended'],
                'default' => 'pending',
            ],
            'is_admin' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
