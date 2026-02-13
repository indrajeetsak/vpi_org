<?php
define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';
$db = \Config\Database::connect();
$query = $db->query('SELECT id, name FROM levels ORDER BY id ASC');
foreach ($query->getResultArray() as $row) {
    echo "ID: {$row['id']} - Name: {$row['name']}\n";
}
echo "---END---\n";
