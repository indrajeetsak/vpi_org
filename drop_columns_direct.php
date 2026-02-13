<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Attempting to drop columns...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    $columnsToDrop = [
        'organ_id', 'level_id', 'post_id', 'status',
        'sector_id', 'ls_id', '2ls_id', '3ls_id', '4ls_id'
    ];

    foreach ($columnsToDrop as $col) {
        try {
            $pdo->exec("ALTER TABLE users DROP COLUMN $col");
            echo "Dropped $col\n";
        } catch (Exception $e) {
            echo "Failed to drop $col: " . $e->getMessage() . "\n";
        }
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Done.\n";

} catch (PDOException $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
