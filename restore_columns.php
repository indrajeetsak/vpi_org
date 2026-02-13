<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Restoring missing columns...\n";
    
    // Check if is_admin exists first
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER pin_code");
        echo "Added is_admin\n";
    }

    // Restore payment_status
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'payment_status'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN payment_status VARCHAR(50) DEFAULT 'unpaid' AFTER is_admin");
        echo "Added payment_status\n";
    }

    // Restore transaction_id
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'transaction_id'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN transaction_id VARCHAR(100) NULL AFTER payment_status");
        echo "Added transaction_id\n";
    }

    echo "Done.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
