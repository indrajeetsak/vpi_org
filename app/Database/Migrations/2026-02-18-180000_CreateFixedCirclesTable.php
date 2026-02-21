<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFixedCirclesTable extends Migration
{
    public function up()
    {
        // Drop table if exists to ensure clean state for fixed records
        $this->forge->dropTable('circles', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->createTable('circles');

        // Insert Fixed Records with Explicit IDs
        $data = [
            ['id' => 1, 'name' => 'East Circle'],
            ['id' => 2, 'name' => 'West Circle'],
            ['id' => 3, 'name' => 'North Circle'],
            ['id' => 4, 'name' => 'South Circle'],
        ];

        $this->db->table('circles')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('circles');
    }
}
