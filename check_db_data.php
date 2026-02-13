<?php
// Quick database check script
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Database Location Data Check ===\n\n";
    
    // Check states
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM states");
    $statesCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "States: $statesCount\n";
    
    if ($statesCount > 0) {
        $stmt = $pdo->query("SELECT id, name FROM states LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - ID {$row['id']}: {$row['name']}\n";
        }
    }
    
    echo "\n";
    
    // Check districts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM districts");
    $districtsCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Districts: $districtsCount\n";
    
    if ($districtsCount > 0) {
        $stmt = $pdo->query("SELECT id, name, state_id FROM districts LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - ID {$row['id']}: {$row['name']} (State ID: {$row['state_id']})\n";
        }
    }
    
    echo "\n";
    
    // Check mla_area
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM mla_area");
    $mlaCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "MLA Areas: $mlaCount\n";
    
    // Check blocks
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM blocks");
    $blocksCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Blocks: $blocksCount\n";
    
    echo "\n";
    
    if ($statesCount == 0 || $districtsCount == 0) {
        echo "⚠️  WARNING: Missing location reference data!\n";
        echo "   The registration form requires states and districts to be populated.\n";
        echo "   You need to seed the database with location data.\n";
    } else {
        echo "✓ Location data exists in database\n";
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
