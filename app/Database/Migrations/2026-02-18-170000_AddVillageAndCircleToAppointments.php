<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVillageAndCircleToAppointments extends Migration
{
    public function up()
    {
        $fields = [
            'village_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'sector_id',
            ],
            'circle_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'village_id',
            ],
        ];
        $this->forge->addColumn('appointments', $fields);
        
        // Add foreign keys
        // Note: Check if village and circle tables exist and ids match types before adding FKs if strictly needed.
        // For now, adding columns is sufficient. FKs can be added if strict integrity is required and tables are guaranteed to exist.
        // Given previous context, tables 'villages' and 'circles' exist.
        
        /* 
        $this->forge->addForeignKey('village_id', 'villages', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('circle_id', 'circles', 'id', 'CASCADE', 'SET NULL');
        */
    }

    public function down()
    {
        $this->forge->dropColumn('appointments', 'village_id');
        $this->forge->dropColumn('appointments', 'circle_id');
    }
}
