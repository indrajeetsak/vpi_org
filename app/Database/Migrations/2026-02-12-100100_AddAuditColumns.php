<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuditColumns extends Migration
{
    public function up()
    {
        $tables = [
            'users',
            'states',
            'districts',
            'blocks',
            'sectors',
            'mla_area',
            'ls',
            '2ls',
            '3ls',
            '4ls',
            'levels',
            'action_level_posts',
            'constituency_level_posts',
            'governing_level_posts'
        ];

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

        foreach ($tables as $table) {
            // Check if table exists before adding columns to avoid errors if a table is missing
            if ($this->db->tableExists($table)) {
                $tableFields = [];
                foreach ($fields as $fieldName => $fieldData) {
                    if (!$this->db->fieldExists($fieldName, $table)) {
                        $tableFields[$fieldName] = $fieldData;
                    }
                }
                
                if (!empty($tableFields)) {
                    $this->forge->addColumn($table, $tableFields);
                }
            }
        }
    }

    public function down()
    {
        $tables = [
            'users',
            'states',
            'districts',
            'blocks',
            'sectors',
            'mla_area',
            'ls',
            '2ls',
            '3ls',
            '4ls',
            'levels',
            'action_level_posts',
            'constituency_level_posts',
            'governing_level_posts'
        ];

        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->forge->dropColumn($table, ['added_by', 'added_by_name', 'edited_by', 'edited_by_name']);
            }
        }
    }
}
