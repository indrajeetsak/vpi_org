<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPollingBoothIdToAppointments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('appointments', [
            'polling_booth_id' => [
                'type'  => 'INT',
                'null'  => true,
                'after' => 'mla_area_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('appointments', 'polling_booth_id');
    }
}
