<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        // 1. Create appointments table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'level_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'post_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'organ_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'state_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'district_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'block_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'sector_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'mla_area_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'ls_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            '2ls_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            '3ls_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            '4ls_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'approved', // Assuming migrated data is approved
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointments');

        // 2. Migrate existing data
        // We assume current state_id, district_id etc in users table are APPOINTMENT/Commitee locations
        // because that's what the Admin panel was saving into them.
        // We will copy them to the new table.
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $users = $builder->where('level_id >', 0)->get()->getResultArray();

        $appointments = [];
        foreach ($users as $user) {
            $appointments[] = [
                'user_id' => $user['id'],
                'level_id' => $user['level_id'],
                'post_id' => $user['post_id'],
                'organ_id' => $user['organ_id'],
                'state_id' => $user['state_id'],
                'district_id' => $user['district_id'],
                'block_id' => $user['block_id'],
                'sector_id' => $user['sector_id'],
                'mla_area_id' => $user['mla_area_id'],
                'ls_id' => $user['ls_id'],
                '2ls_id' => $user['2ls_id'],
                '3ls_id' => $user['3ls_id'],
                '4ls_id' => $user['4ls_id'],
                'status' => $user['status'] == 'pending' ? 'pending' : 'approved', // Simple mapping
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        }

        if (!empty($appointments)) {
             $db->table('appointments')->insertBatch($appointments);
        }
        
        // Option: Nullify the columns in users table?
        // For safety, we keep them for now, but will stop using them for appointments in logic.
        // We could run an update to set them null if strictly personal info is needed and currently matches.
        // But since we don't have separate personal address fields populated distinctively yet (except address text),
        // we leave them.
    }

    public function down()
    {
        $this->forge->dropTable('appointments');
    }
}
