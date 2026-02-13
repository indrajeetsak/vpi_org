<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $user, $password);
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo implode("\n", $columns) . "\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
