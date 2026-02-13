<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectorIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'sector_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'after'          => 'block_id',
            ],
        ]);
        
        // Add foreign key
        $this->db->query('ALTER TABLE users ADD CONSTRAINT fk_users_sector_id FOREIGN KEY (sector_id) REFERENCES sectors(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key first
        $this->db->query('ALTER TABLE users DROP FOREIGN KEY fk_users_sector_id');
        $this->forge->dropColumn('users', 'sector_id');
    }
}
