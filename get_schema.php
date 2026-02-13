<?php
$mysqli = new mysqli("localhost", "root", "", "vpi_ob");
$tables = ['action_level_posts', 'constituency_level_posts', 'governing_level_posts'];
foreach ($tables as $t) {
    echo "TABLE: $t\n";
    $res = $mysqli->query("DESCRIBE $t");
    while ($r = $res->fetch_assoc()) {
        echo $r['Field'] . "\n";
    }
    echo "----------------\n";
}
