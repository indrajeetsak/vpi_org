<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialData extends Seeder
{
    public function run()
    {
        // Organs
        $organs = [
            [
                'name' => 'Main Committee',
                'description' => 'Main governing committee',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Front',
                'description' => 'Front organization',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Cell',
                'description' => 'Specialized cell',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Insert organs
        $this->db->table('organs')->insertBatch($organs);

        // Levels
        $levels = [
            [
                'name' => 'National',
                'description' => 'National level',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'State',
                'description' => 'State level',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'District',
                'description' => 'District level',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Insert levels
        $this->db->table('levels')->insertBatch($levels);
    }
}
