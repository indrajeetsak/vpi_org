<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFrontIdToAppointments extends Migration
{
    public function up()
    {
        $fields = [
            'front_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'organ_id',
            ],
        ];
        $this->forge->addColumn('appointments', $fields);
        
        // Optional: Add foreign key if fronts table is guaranteed to exist and rule enforcement is desired
        // $this->forge->addForeignKey('front_id', 'fronts', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('appointments', 'front_id');
    }
}
