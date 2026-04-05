<?php

$files = [
    'app/Models/UserModel.php',
    'app/Controllers/Admin.php',
    'app/Controllers/Auth.php',
    'app/Controllers/CommitteeController.php',
    'app/Controllers/Dashboard.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace protected table name in UserModel
    $content = preg_replace("/protected\\s+\\$table\\s*=\\s*['\"]users['\"];/", "protected \$table = 'master_users';", $content);
    
    // Replace table('users') and table("users")
    $content = preg_replace("/table\\(['\"]users['\"]\\)/", "table('master_users')", $content);

    // Replace users.column with master_users.column
    // Avoid replacing if it's already master_users.
    $content = preg_replace("/(?<!master_)users\\./", "master_users.", $content);
    
    // Replace 'users' in JOINs: join('users', ...) or join("users", ...)
    $content = preg_replace("/join\\(\\s*['\"]users['\"]\\s*,/", "join('master_users',", $content);
    $content = preg_replace("/join\\(\\s*['\"]users as/i", "join('master_users as", $content);

    // Replace from('users')
    $content = preg_replace("/from\\(\\s*['\"]users['\"]\\s*\\)/", "from('master_users')", $content);

    file_put_contents($file, $content);
    echo "Updated $file\n";
}
