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
    die("Error: Could not find app/Config/Paths.php.");
}
require $pathsPath;

$paths = new Config\Paths();
$bootPath = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';
if (!file_exists($bootPath)) {
    die("Error: Could not find system Boot file at {$bootPath}");
}
require $bootPath;

// Create a custom boot sequence to initialize the framework programmatically
class WebMigrationBoot extends \CodeIgniter\Boot {
    public static function bootCustom($paths) {
        static::definePathConstants($paths);
        if (! defined('APP_NAMESPACE')) {
            static::loadConstants();
        }
        static::checkMissingExtensions();

        static::loadDotEnv($paths);
        static::defineEnvironment();
        static::loadEnvironmentBootstrap($paths);

        static::loadCommonFunctions();
        static::loadAutoloader();
        static::setExceptionHandler();
        static::initializeKint();
        static::autoloadHelpers();
        
        static::initializeCodeIgniter();
    }
}

echo "<html><body>";
echo "<h2>CodeIgniter 4 Migration Execution</h2>";

try {
    // Bootstrap the framework safely without running the Router
    WebMigrationBoot::bootCustom($paths);

    // Instantiate and run the migrations service
    $migrate = \Config\Services::migrations();
    
    if ($migrate->latest()) {
        echo "<h3 style='color: green;'>Migrations ran successfully!</h3>";
        
        $history = $migrate->getHistory();
        if(!empty($history)) {
            echo "<ul>";
            foreach($history as $migration) {
                $mname = $migration->class ?? $migration->version ?? 'Unknown';
                echo "<li>Batch {$migration->batch}: " . htmlspecialchars($mname) . "</li>";
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
