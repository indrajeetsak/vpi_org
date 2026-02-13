<?php
// Load CodeIgniter
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require 'system/bootstrap.php';

use Config\Database;

$db = Database::connect();

$tables = ['action_level_posts', 'constituency_level_posts', 'governing_level_posts'];

foreach ($tables as $table) {
    echo "Table: $table\n";
    $query = $db->query("DESCRIBE $table");
    foreach ($query->getResultArray() as $row) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    echo "-------------------\n";
    $data = $db->table($table)->limit(5)->get()->getResultArray();
    print_r($data);
    echo "\n===================\n";
}
