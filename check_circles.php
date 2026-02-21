<?php
// Script to check circles table
define('FCPATH', __DIR__ . '/public');
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
exit(CodeIgniter\Boot::bootWeb($paths));

use App\Models\CircleModel;

$model = model('App\Models\CircleModel');
$circles = $model->findAll();

echo "Current Circles:\n";
foreach ($circles as $circle) {
    echo "ID: " . $circle['id'] . " - Name: " . $circle['name'] . "\n";
}
