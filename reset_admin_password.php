<?php
// Reset admin password script
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Resetting Admin Password ===\n\n";
    
    // The email to update
    $email = 'admin@example.com';
    
    // The new password
    $newPassword = 'password';
    
    // Hash the password using PHP's password_hash (same as CodeIgniter uses)
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update the password
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->execute([
        'password' => $hashedPassword,
        'email' => $email
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo "✓ Password successfully updated!\n\n";
        echo "Login Credentials:\n";
        echo "==================\n";
        echo "Email: $email\n";
        echo "Password: $newPassword\n";
        echo "==================\n\n";
        echo "You can now log in with these credentials.\n";
    } else {
        echo "⚠️  No user found with email: $email\n";
        echo "Please check if the email address is correct.\n";
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
