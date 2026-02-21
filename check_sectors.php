<?php
require 'public/index.php';
$db = \Config\Database::connect();
$sectors = $db->table('sectors')->like('name', 'Sipajhar')->get()->getResultArray();
print_r($sectors);
