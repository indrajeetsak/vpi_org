<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropRedundantJurisdictionFields extends Migration
{
    public function up()
    {
        $columnsToDrop = [
            'organ_id',
            'level_id',
            'post_id',
            'status',
            'sector_id',
            'ls_id',
            '2ls_id',
            '3ls_id',
            '4ls_id'
        ];

        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($columnsToDrop as $column) {
            if ($this->db->fieldExists($column, 'users')) {
                // Drop any foreign keys that might exist for these columns
                // In MySQL, we need to know the name, but setting checks to 0 usually allows dropping the column.
                // If it fails, we might need to be more explicit.
                $this->db->query("ALTER TABLE users DROP COLUMN $column");
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
        
        // Note: state_id, district_id, mla_area_id, block_id are retained for personal address.
    }

    public function down()
    {
        $this->forge->addColumn('users', [
            'organ_id'    => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'level_id'    => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'post_id'     => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected', 'pending_payment'], 'default' => 'pending'],
            'sector_id'   => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'ls_id'       => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            '2ls_id'      => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            '3ls_id'      => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            '4ls_id'      => ['type' => 'INT', 'constraint' => 11, 'null' => true],
        ]);
    }
}
