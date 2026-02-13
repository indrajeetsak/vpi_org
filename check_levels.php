<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vpi_ob', 'root', '');
    $stmt = $pdo->query('SELECT id, name FROM levels ORDER BY id ASC');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['id'] . ': ' . $row['name'] . "\n";
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
