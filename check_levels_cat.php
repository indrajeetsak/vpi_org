<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vpi_ob', 'root', '');
    $stmt = $pdo->query('SELECT id, name FROM levels ORDER BY id ASC');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = (int)$row['id'];
        $cat = 'Unknown';
        if ($id >= 1 && $id <= 5) $cat = 'Action';
        elseif ($id >= 6 && $id <= 10) $cat = 'Constituency';
        elseif ($id >= 11 && $id <= 15) $cat = 'Governing';
        elseif ($id >= 16 && $id <= 17) $cat = 'Managerial';
        
        printf("[%s] %2d: %s\n", $cat, $id, $row['name']);
    }
} catch (PDOException $e) { echo $e->getMessage(); }
