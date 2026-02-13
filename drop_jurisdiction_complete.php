<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get all foreign keys for the users table
    $stmt = $pdo->prepare("
        SELECT CONSTRAINT_NAME, COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_NAME = 'users'
        AND TABLE_SCHEMA = 'vpi_ob'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    $stmt->execute();
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $targetCols = ['organ_id', 'level_id', 'post_id', 'sector_id', 'ls_id', '2ls_id', '3ls_id', '4ls_id'];

    echo "Found " . count($fks) . " foreign keys.\n";

    foreach ($fks as $fk) {
        if (in_array($fk['COLUMN_NAME'], $targetCols)) {
            echo "Dropping FK: " . $fk['CONSTRAINT_NAME'] . " on " . $fk['COLUMN_NAME'] . "\n";
            try {
                $pdo->exec("ALTER TABLE users DROP FOREIGN KEY " . $fk['CONSTRAINT_NAME']);
            } catch (Exception $e) {
                echo "Failed to drop FK " . $fk['CONSTRAINT_NAME'] . ": " . $e->getMessage() . "\n";
            }
        }
    }

    echo "Now dropping columns...\n";
    foreach ($targetCols as $col) {
        try {
            $pdo->exec("ALTER TABLE users DROP COLUMN $col");
            echo "Dropped column $col\n";
        } catch (Exception $e) {
            echo "Failed to drop column $col: " . $e->getMessage() . "\n";
        }
    }
    
    // Also drop 'status' which shouldn't have an FK but might have an index
    try {
        $pdo->exec("ALTER TABLE users DROP COLUMN status");
        echo "Dropped column status\n";
    } catch (Exception $e) {}

    echo "Finished.\n";

} catch (PDOException $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
