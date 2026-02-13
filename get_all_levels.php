<?php
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';
$db = \Config\Database::connect();
$query = $db->query('SELECT id, name FROM levels ORDER BY id ASC');
print_r($query->getResultArray());
