<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropIsAdminFromUsers extends Migration
{
    public function up()
    {
        $columns = ['is_admin', 'payment_status', 'transaction_id'];
        
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($columns as $column) {
            if ($this->db->fieldExists($column, 'users')) {
                $this->db->query("ALTER TABLE users DROP COLUMN $column");
            }
        }
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
    }

    public function down()
    {
        //
    }
}
