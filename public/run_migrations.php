<?php
/**
 * Script to run CodeIgniter 4 database migrations from the web interface.
 * Useful for hosting environments where SSH/CLI access is not available.
 * 
 * IMPORTANT: Delete this file after running to prevent unauthorized execution.
 */

// Define FCPATH as the current directory (public)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Load the paths config file
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
if (!$pathsPath) {
    die("Error: Could not find app/Config/Paths.php. Make sure this script is in your 'public' directory.");
}
require $pathsPath;

$paths = new Config\Paths();
$bootstrapPath = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
if (!file_exists($bootstrapPath)) {
    die("Error: Could not find system bootstrap file at {$bootstrapPath}");
}
require $bootstrapPath;

// Instantiate the migrations service
$migrate = \Config\Services::migrations();

echo "<html><body>";
echo "<h2>CodeIgniter 4 Migration Execution</h2>";

try {
    // Run the migrations to the latest state
    if ($migrate->latest()) {
        echo "<h3 style='color: green;'>Migrations ran successfully!</h3>";
        
        $history = $migrate->getHistory();
        if(!empty($history)) {
            echo "<ul>";
            foreach($history as $migration) {
                echo "<li>Batch {$migration->batch}: " . htmlspecialchars($migration->name) . "</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<h3 style='color: orange;'>Nothing to migrate. Your database is up-to-date.</h3>";
    }
} catch (\Throwable $e) {
    echo "<h3 style='color: red;'>Error running migrations:</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " at line " . $e->getLine() . "</p>";
}

echo "<hr/>";
echo "<p><strong>Security Warning:</strong> Please delete this <code>run_migrations.php</code> file from your server after use.</p>";
echo "</body></html>";
