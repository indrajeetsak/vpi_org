<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLocationTables extends Migration
{    public function up()
    {
        // Drop existing tables in reverse order to handle foreign key constraints
        $tables = ['blocks', 'mla_area', 'districts', 'states'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->forge->dropTable($table, true);
            }
        }
        
        // Create tables and add foreign keys one by one
        $this->createStatesTable();
        $this->createDistrictsTable();
        $this->createMlaAreasTable();
        $this->createBlocksTable();
    }

    private function createStatesTable()
    {

        // States table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('states', true);
    }

    private function createDistrictsTable()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'state_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('districts', true);

        // Add foreign key after table creation
        $this->db->query("ALTER TABLE districts ADD CONSTRAINT fk_districts_states 
                         FOREIGN KEY (state_id) REFERENCES states(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE");
    }

    private function createMlaAreasTable()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'district_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('mla_area', true);
        
        // Add foreign key after table creation
        $this->db->query("ALTER TABLE mla_area ADD CONSTRAINT fk_mla_area_districts 
                         FOREIGN KEY (district_id) REFERENCES districts(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE");
    }

    private function createBlocksTable()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'district_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('blocks', true);
        
        // Add foreign key after table creation
        $this->db->query("ALTER TABLE blocks ADD CONSTRAINT fk_blocks_districts 
                         FOREIGN KEY (district_id) REFERENCES districts(id) 
                         ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        $this->forge->dropTable('blocks', true);
        $this->forge->dropTable('mla_area', true);
        $this->forge->dropTable('districts', true);
        $this->forge->dropTable('states', true);
    }
}
