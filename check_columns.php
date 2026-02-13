<?php
// Initialize the framework (minimal)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/vendor/codeigniter4/framework/system/bootstrap.php';

$db = \Config\Database::connect();
$fields = $db->getFieldNames('users');
echo "Columns in users table:\n";
print_r($fields);
