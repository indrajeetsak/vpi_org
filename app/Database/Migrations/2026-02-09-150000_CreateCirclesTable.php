<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectorsTable extends Migration
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
            'block_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('block_id');
        
        // Add foreign key constraint
        $this->forge->addForeignKey('block_id', 'blocks', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('sectors');
    }

    public function down()
    {
        $this->forge->dropTable('sectors');
    }
}

