<?php
// Test blocks endpoint
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Testing Blocks Functionality ===\n\n";
    
    // Get a district ID
    $stmt = $pdo->query("SELECT id, name FROM districts LIMIT 1");
    $district = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($district) {
        echo "Test District: {$district['name']} (ID: {$district['id']})\n\n";
        
        // Check existing blocks
        $stmt = $pdo->prepare("SELECT * FROM blocks WHERE district_id = ?");
        $stmt->execute([$district['id']]);
        $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Existing Blocks:\n";
        if (count($blocks) > 0) {
            foreach ($blocks as $block) {
                echo "  - {$block['name']} (ID: {$block['id']})\n";
            }
        } else {
            echo "  No blocks found.\n";
        }
        
        echo "\n=== Testing Insert ===\n";
        echo "Attempting to insert: Test Block 1, Test Block 2\n";
        
        $testBlocks = [
            ['district_id' => $district['id'], 'name' => 'Test Block 1'],
            ['district_id' => $district['id'], 'name' => 'Test Block 2']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO blocks (district_id, name, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        foreach ($testBlocks as $block) {
            try {
                $stmt->execute([$block['district_id'], $block['name']]);
                echo "✓ Inserted: {$block['name']}\n";
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo "⚠ Already exists: {$block['name']}\n";
                } else {
                    echo "✗ Error: {$e->getMessage()}\n";
                }
            }
        }
        
    } else {
        echo "No districts found in database.\n";
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
