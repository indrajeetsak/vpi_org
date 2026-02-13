<?php
// Check if 1ls table exists and its structure
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check all tables with 'ls' in the name
    echo "=== All Loksabha Tables ===\n";
    $stmt = $pdo->query("SHOW TABLES LIKE '%ls%'");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "\n";
    }
    
    // Check if there's a table for 1 Loksabha (might be named differently)
    echo "\n=== Checking for 1 Loksabha table ===\n";
    $tables = ['1ls', 'ls', 'loksabha', 'one_ls', 'single_ls'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            echo "Found table: $table\n";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  {$row['Field']} - {$row['Type']}\n";
            }
        } catch (PDOException $e) {
            // Table doesn't exist
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
