<?php
/**
 * Script to run CodeIgniter 4 database migrations from the web interface.
 * Useful for hosting environments where SSH/CLI access to run 'php spark
 * migrate' is not available.
 * 
 * IMPORTANT: Delete or move this file outside of public access after running
 * to prevent unauthorized execution.
 */

// Define FCPATH assuming this script is in the project root alongside the 'public' directory
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

// Change working directory to public/
if (is_dir(FCPATH)) {
    chdir(FCPATH);
} else {
    // Fallback if public folder doesn't exist, assume script is within public itself
    define('FCPATH_FALLBACK', __DIR__ . DIRECTORY_SEPARATOR);
    chdir(FCPATH_FALLBACK);
}

// Load the paths config file
$pathsPath = realpath(__DIR__ . '/app/Config/Paths.php');
if (!$pathsPath) {
    die("Error: Could not find app/Config/Paths.php. Make sure this script is in your project root.");
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
        
        // Show current migration status / history mapping
        $history = $migrate->getHistory();
        if(!empty($history)) {
            echo "<ul>";
            foreach($history as $migration) {
                echo "<li>Batch {$migration->batch}: " . htmlspecialchars($migration->name) . "</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<h3 style='color: orange;'>Nothing to migrate. It seems your database already has the latest migrations.</h3>";
    }
} catch (\Throwable $e) {
    echo "<h3 style='color: red;'>Error running migrations:</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " at line " . $e->getLine() . "</p>";
}

echo "<hr/>";
echo "<p><strong>Security Warning:</strong> Please delete this <code>run_migrations.php</code> file from your server after you have finished running the migrations to prevent unauthorized access.</p>";
echo "</body></html>";
