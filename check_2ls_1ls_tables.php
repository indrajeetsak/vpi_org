<?php
// Check 2ls and 1ls table structure
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Checking 2ls Table ===\n";
    $stmt = $pdo->query("DESCRIBE 2ls");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Key']}\n";
    }
    
    echo "\n=== Checking 1ls Table ===\n";
    $stmt = $pdo->query("DESCRIBE 1ls");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Key']}\n";
    }
    
    echo "\n=== Sample 2ls Data ===\n";
    $stmt = $pdo->query("SELECT * FROM 2ls LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
    echo "\n=== Sample 1ls Data ===\n";
    $stmt = $pdo->query("SELECT * FROM 1ls LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
