<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCircleToSectors extends Migration
{
    public function up()
    {
        $fields = [
            'circle' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'name',
            ],
        ];
        $this->forge->addColumn('sectors', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('sectors', 'circle');
    }
}
