<?php
// Define the path to the framework's boot file
define('FCPATH', __DIR__ . '/public/');

// Load the framework's paths
require_once FCPATH . '../app/Config/Paths.php';
$paths = new \Config\Paths();

// Load the framework's boot file
require rtrim($paths->systemDirectory, '\/') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Get the database connection
$db = \Config\Database::connect();

// Get the state ID
$state = $db->table('states')->where('name', 'Uttar Pradesh')->get()->getRow();

if ($state) {
    echo "The ID of Uttar Pradesh is: " . $state->id;
} else {
    echo "Uttar Pradesh not found in the database.";
}
?>