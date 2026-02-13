<?php
$mysqli = new mysqli("localhost", "root", "", "vpi_ob");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$tables = ['action_level_posts', 'constituency_level_posts', 'governing_level_posts'];

foreach ($tables as $table) {
    echo "Table: $table\n";
    if ($result = $mysqli->query("DESCRIBE $table")) {
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'] . "\n";
        }
        $result->free();
    }
    echo "-------------------\n";
    if ($result = $mysqli->query("SELECT * FROM $table LIMIT 5")) {
        while ($row = $result->fetch_assoc()) {
            print_r($row);
        }
        $result->free();
    }
    echo "\n===================\n";
}

$mysqli->close();
