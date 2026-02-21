<?php
require 'public/index.php';
$db = \Config\Database::connect();
$sectors = $db->query('SELECT a.sector_id, sc.name as sector_name, b.name as block_name, COUNT(a.id) as count FROM appointments a LEFT JOIN sectors sc ON sc.id = a.sector_id LEFT JOIN blocks b ON b.id = COALESCE(sc.block_id, a.block_id) WHERE a.sector_id IN (35, 36) GROUP BY a.sector_id, sc.name, b.name')->getResultArray();
print_r($sectors);
