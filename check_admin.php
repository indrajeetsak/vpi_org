<?php
// Quick admin credentials check script
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Admin User Credentials ===\n\n";
    
    // Check for admin users using is_admin column
    $stmt = $pdo->query("SELECT * FROM users WHERE is_admin = 1 ORDER BY id LIMIT 10");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($admins) > 0) {
        echo "Found " . count($admins) . " admin user(s):\n\n";
        foreach ($admins as $admin) {
            echo "========================================\n";
            echo "ADMIN LOGIN CREDENTIALS:\n";
            echo "========================================\n";
            echo "Email/Login: {$admin['email']}\n";
            echo "Name: {$admin['first_name']} {$admin['last_name']}\n";
            echo "Mobile: {$admin['mobile']}\n";
            echo "Status: {$admin['status']}\n";
            echo "ID: {$admin['id']}\n";
            echo "\nNote: Password is hashed in database.\n";
            echo "If you don't know the password, you may need to reset it.\n";
            echo "========================================\n\n";
        }
    } else {
        echo "⚠️  No admin users found in database!\n";
        echo "Checking all users...\n\n";
        
        // Check all users
        $stmt = $pdo->query("SELECT id, first_name, last_name, email, mobile, is_admin, status FROM users ORDER BY id LIMIT 20");
        $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($allUsers) > 0) {
            echo "All users in database:\n";
            foreach ($allUsers as $user) {
                echo "\nID: {$user['id']}\n";
                echo "Name: {$user['first_name']} {$user['last_name']}\n";
                echo "Email: {$user['email']}\n";
                echo "Mobile: {$user['mobile']}\n";
                echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
                echo "Status: {$user['status']}\n";
                echo "---\n";
            }
        } else {
            echo "No users found in database at all!\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
