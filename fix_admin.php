<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Fixing admin status...\n";
    $affected = $pdo->exec("UPDATE users SET is_admin = 1 WHERE mobile = '9999999999' OR email = 'admin@example.com'");
    echo "Rows updated: $affected\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
