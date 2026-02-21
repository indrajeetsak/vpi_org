<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StandardizeSectorsCircleId extends Migration
{
    public function up()
    {
        // 1. Add circle_id column
        $fields = [
            'circle_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'block_id',
            ],
        ];
        $this->forge->addColumn('sectors', $fields);

        // 2. Migrate existing data
        $db = \Config\Database::connect();
        $db->table('sectors')->where('circle', 'East Circle')->update(['circle_id' => 1]);
        $db->table('sectors')->where('circle', 'West Circle')->update(['circle_id' => 2]);
        $db->table('sectors')->where('circle', 'North Circle')->update(['circle_id' => 3]);
        $db->table('sectors')->where('circle', 'South Circle')->update(['circle_id' => 4]);

        // 3. Add Foreign Key
        // Note: We need to make sure circle_id matches circles.id type/unsigned status
        // circles.id is INT(11) UNSIGNED AUTO_INCREMENT
        
        $this->forge->addForeignKey('circle_id', 'circles', 'id', 'CASCADE', 'SET NULL');
        $this->forge->processIndexes('sectors'); // Process added keys

        // 4. Drop old circle column
        $this->forge->dropColumn('sectors', 'circle');
    }

    public function down()
    {
        // Revert: Add circle column, migrate back, drop circle_id
        $fields = [
            'circle' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('sectors', $fields);

        $db = \Config\Database::connect();
        $db->table('sectors')->where('circle_id', 1)->update(['circle' => 'East Circle']);
        $db->table('sectors')->where('circle_id', 2)->update(['circle' => 'West Circle']);
        $db->table('sectors')->where('circle_id', 3)->update(['circle' => 'North Circle']);
        $db->table('sectors')->where('circle_id', 4)->update(['circle' => 'South Circle']);

        $this->forge->dropForeignKey('sectors', 'sectors_circle_id_foreign');
        $this->forge->dropColumn('sectors', 'circle_id');
    }
}
