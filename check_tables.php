<?php
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SHOW TABLES LIKE '%polling%'");
    echo "Tables with 'polling':\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }

    $stmt2 = $pdo->query("SHOW TABLES LIKE '%booth%'");
    echo "Tables with 'booth':\n";
    while ($row = $stmt2->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }
    
    $stmt3 = $pdo->query("SHOW TABLES LIKE '%station%'");
    echo "Tables with 'station':\n";
    while ($row = $stmt3->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
