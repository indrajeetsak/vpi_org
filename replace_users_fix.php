<?php

$files = [
    'app/Models/UserModel.php',
    'app/Controllers/Admin.php',
    'app/Controllers/Auth.php',
    'app/Controllers/CommitteeController.php',
    'app/Controllers/Dashboard.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "$file not found.\n";
        continue;
    }
    $content = file_get_contents($file);
    
    // Exact string replacements
    $content = str_replace("protected \$table = 'users';", "protected \$table = 'master_users';", $content);
    $content = str_replace("table('users')", "table('master_users')", $content);
    $content = str_replace("table(\"users\")", "table(\"master_users\")", $content);
    
    // For joining, it is mostly 'join('appointments', 'appointments.user_id = users.id')'
    // Regex for 'users.'
    // We use a safe single-quoted regex
    $content = preg_replace('/(?<!master_)users\./', 'master_users.', $content);
    
    // For join arguments: join('users', ...
    $content = preg_replace('/join\(\s*[\'"]users[\'"]\s*,/i', "join('master_users',", $content);
    $content = preg_replace('/join\(\s*[\'"]users as/i', "join('master_users as", $content);

    // For from('users')
    $content = preg_replace('/from\(\s*[\'"]users[\'"]\s*\)/i', "from('master_users')", $content);

    file_put_contents($file, $content);
    echo "Updated $file\n";
}
