<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vpi_ob', 'root', '');
    $stmt = $pdo->query('SELECT id, name FROM levels ORDER BY id ASC');
    $output = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= sprintf("%2d: %s\n", $row['id'], $row['name']);
    }
    file_put_contents('levels_list.txt', $output);
    echo "Done";
} catch (PDOException $e) { echo $e->getMessage(); }
