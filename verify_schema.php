<?php
require 'vendor/autoload.php';
$app = require_once 'system/Test/bootstrap.php';
$db = \Config\Database::connect();
$fields = $db->getFieldNames('users');
echo implode(', ', $fields);
