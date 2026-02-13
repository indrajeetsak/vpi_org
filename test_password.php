<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test password verification
$password = 'admin123';
$mobile = '9999999999';

// Connect to database
$mysqli = new mysqli('localhost', 'root', '', 'vpi_ob');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Get current hash from database
$stmt = $mysqli->prepare("SELECT password FROM users WHERE mobile = ?");
$stmt->bind_param("s", $mobile);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "Current hash in DB: " . $user['password'] . "\n";
    echo "Testing password_verify with 'admin123': " . (password_verify($password, $user['password']) ? "true" : "false") . "\n";
    
    // Create a new hash for comparison
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "New hash of 'admin123': " . $new_hash . "\n";
    echo "Verification of new hash: " . (password_verify($password, $new_hash) ? "true" : "false") . "\n";
} else {
    echo "User not found\n";
}
