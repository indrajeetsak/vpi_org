<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixUsersTableSchema extends Migration
{
    public function up()
    {
        // Add new INT foreign key columns to users table
        $fields = [
            'state_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'aadhaar_number',
            ],
            'district_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'state_id',
            ],
            'mla_area_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'district_id',
            ],
            'block_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'mla_area_id',
            ],
            'ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'block_id',
            ],
            '2ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'ls_id',
            ],
            '3ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => '2ls_id',
            ],
            '4ls_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => '3ls_id',
            ],
            'organ_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'pin_code',
            ],
            'level_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'organ_id',
            ],
            'post_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'level_id',
            ],
        ];

        $this->forge->addColumn('users', $fields);

        // Add indexes for better query performance
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_state_id` (`state_id`)');
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_district_id` (`district_id`)');
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_mla_area_id` (`mla_area_id`)');
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_block_id` (`block_id`)');
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_organ_id` (`organ_id`)');
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_level_id` (`level_id`)');
        $this->db->query('ALTER TABLE `users` ADD INDEX `idx_post_id` (`post_id`)');

        // Add foreign key constraints
        $this->db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_mla_area` FOREIGN KEY (`mla_area_id`) REFERENCES `mla_area` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_block` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_organ` FOREIGN KEY (`organ_id`) REFERENCES `organs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `users` ADD CONSTRAINT `fk_users_level` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        
        // Note: post_id doesn't have FK constraint because it references different tables based on level_id
    }

    public function down()
    {
        // Drop foreign key constraints first
        if ($this->db->DBDriver === 'MySQLi') {
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_state`');
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_district`');
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_mla_area`');
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_block`');
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_organ`');
            $this->db->query('ALTER TABLE `users` DROP FOREIGN KEY `fk_users_level`');
        }

        // Drop indexes
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_state_id`');
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_district_id`');
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_mla_area_id`');
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_block_id`');
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_organ_id`');
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_level_id`');
        $this->db->query('ALTER TABLE `users` DROP INDEX `idx_post_id`');

        // Drop columns
        $this->forge->dropColumn('users', [
            'state_id',
            'district_id',
            'mla_area_id',
            'block_id',
            'ls_id',
            '2ls_id',
            '3ls_id',
            '4ls_id',
            'organ_id',
            'level_id',
            'post_id',
        ]);
    }
}
