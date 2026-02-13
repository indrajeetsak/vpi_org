<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMlaLsHierarchyTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'mla_area_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'LS_Name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'two_ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'two_LS_Name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'three_ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'three_LS_Name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'four_ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'four_LS_Name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
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
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('mla_area_id', 'mla_area', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mla_ls_hierarchy');
    }

    public function down()
    {
        $this->forge->dropTable('mla_ls_hierarchy');
    }
}
