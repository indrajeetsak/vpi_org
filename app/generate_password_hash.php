<?php

// The password you want to hash
$plainPassword = 'Admin@123';

// Hash the password using PHP's recommended default algorithm (usually bcrypt)
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Output the original password and its hash
echo "Plain Password: " . $plainPassword . "\n";
echo "Hashed Password: " . $hashedPassword . "\n";

echo "\n";
echo "To use this script:\n";
echo "1. Save it as a .php file (e.g., generate_password_hash.php) on your server.\n";
echo "2. Run it from your command line using: php generate_password_hash.php\n";
echo "3. Copy the 'Hashed Password' value to use in your database.\n";

?>
