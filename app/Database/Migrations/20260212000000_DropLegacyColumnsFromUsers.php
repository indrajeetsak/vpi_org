<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropLegacyColumnsFromUsers extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('users', [
            'state',
            'district',
            'mla_area',
            'block',
            'ls',
            '2ls',
            '3ls',
            '4ls',
            'organ',
            'level',
            'post'
        ]);
    }

    public function down()
    {
        // To restore, we would need to add them back as VARCHARs
        $this->forge->addColumn('users', [
            'state'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'district' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'mla_area' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'block'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'ls'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            '2ls'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            '3ls'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            '4ls'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'organ'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'level'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'post'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
    }
}
