<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vpi_ob', 'root', '');
    $stmt = $pdo->query('SELECT id, name FROM levels ORDER BY id ASC');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        printf("%2d: %s\n", $row['id'], $row['name']);
    }
} catch (PDOException $e) { echo $e->getMessage(); }
