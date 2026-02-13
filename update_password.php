<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$mysqli = new mysqli('localhost', 'root', '', 'vpi_ob');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
$mobile = '9999999999';

$stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE mobile = ?");
$stmt->bind_param("ss", $hash, $mobile);

if ($stmt->execute()) {
    echo "Password updated successfully\n";
    
    // Verify the update
    $verify = $mysqli->prepare("SELECT password FROM users WHERE mobile = ?");
    $verify->bind_param("s", $mobile);
    $verify->execute();
    $result = $verify->get_result();
    $user = $result->fetch_assoc();
    
    echo "New hash in DB: " . $user['password'] . "\n";
    echo "Verification test: " . (password_verify($password, $user['password']) ? "success" : "failed") . "\n";
} else {
    echo "Error updating password: " . $mysqli->error . "\n";
}
