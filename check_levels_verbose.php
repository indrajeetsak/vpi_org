<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vpi_ob', 'root', '');
    foreach($pdo->query('SELECT id, name FROM levels ORDER BY id ASC') as $row) {
        printf("%2d: %s\n", $row['id'], $row['name']);
    }
} catch (PDOException $e) { echo $e->getMessage(); }
