<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";
try {
    $pdo = new PDO($dsn, $user, $password);
    $stmt = $pdo->prepare("SELECT id, first_name, is_admin FROM users WHERE mobile = ? OR email = ?");
    $stmt->execute(['9999999999', 'admin@example.com']);
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($admins) . " admins:\n";
    print_r($admins);
    
    // Check if is_admin column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
    if ($stmt->fetch()) {
        echo "is_admin column exists.\n";
    } else {
        echo "is_admin column MISSING!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
