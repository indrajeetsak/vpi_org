<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";
try {
    $pdo = new PDO($dsn, $user, $password);
    $stmt = $pdo->query("SELECT * FROM admins");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Admins found: " . count($admins) . "\n";
    print_r($admins);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
