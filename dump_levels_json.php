<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vpi_ob', 'root', '');
    $stmt = $pdo->query('SELECT id, name FROM levels ORDER BY id ASC');
    $levels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents('levels_dump.json', json_encode($levels, JSON_PRETTY_PRINT));
    echo "Dumped " . count($levels) . " levels to levels_dump.json\n";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
