<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuditColumnsToMissingTables extends Migration
{
    public function up()
    {
        $fields = [
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
            ]
        ];

        // Add to appointments
        if ($this->db->tableExists('appointments')) {
            $this->forge->addColumn('appointments', $fields);
        }

        // Add to admins
        if ($this->db->tableExists('admins')) {
            $this->forge->addColumn('admins', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('appointments')) {
            $this->forge->dropColumn('appointments', ['added_by', 'added_by_name', 'edited_by', 'edited_by_name']);
        }
        if ($this->db->tableExists('admins')) {
            $this->forge->dropColumn('admins', ['added_by', 'added_by_name', 'edited_by', 'edited_by_name']);
        }
    }
}
