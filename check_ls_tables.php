<?php
// Check 3ls and 4ls table structure
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Checking 4ls Table ===\n";
    $stmt = $pdo->query("DESCRIBE 4ls");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Key']}\n";
    }
    
    echo "\n=== Checking 3ls Table ===\n";
    $stmt = $pdo->query("DESCRIBE 3ls");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Key']}\n";
    }
    
    echo "\n=== Sample 4ls Data ===\n";
    $stmt = $pdo->query("SELECT * FROM 4ls LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
    echo "\n=== Sample 3ls Data ===\n";
    $stmt = $pdo->query("SELECT * FROM 3ls LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
