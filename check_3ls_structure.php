<?php
// Check 3ls table structure and data
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== 3ls Table Structure ===\n";
    $stmt = $pdo->query("DESCRIBE 3ls");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Key']} - {$row['Extra']}\n";
    }
    
    echo "\n=== Sample 3ls Data ===\n";
    $stmt = $pdo->query("SELECT * FROM 3ls LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
    echo "\n=== Count of records with id = 0 ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM 3ls WHERE id = 0");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Records with id=0: {$result['count']}\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
