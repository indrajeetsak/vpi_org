<?php
/**
 * Script to copy office bearers from `users` table to `master_users` table
 * on the live server.
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
require $bootPath;

class SyncMigrationBoot extends \CodeIgniter\Boot {
    public static function bootCustom($paths) {
        static::definePathConstants($paths);
        if (! defined('APP_NAMESPACE')) { static::loadConstants(); }
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
echo "<h2>CodeIgniter 4 Sync Office Bearers</h2>";

try {
    SyncMigrationBoot::bootCustom($paths);

    $db1 = \Config\Database::connect();
    
    // Disable strict mode checking if needed for inserts
    $masterBuilder = $db1->table('master_users');
    $obUsersBuilder = $db1->table('users');

    echo "<p>Migrating Office Bearers from 'users' table to 'master_users' table...</p>";

    $obUsers = $obUsersBuilder->get()->getResultArray();
    echo "<p>Found " . count($obUsers) . " Office Bearers in the old 'users' table.</p>";
    
    $insertedCount = 0;
    foreach ($obUsers as $user) {
        $data = $user;
        $data['type'] = '3'; // Office Bearer
        
        $data['email'] = empty(trim((string)$data['email'])) ? null : trim($data['email']);
        $data['mobile'] = empty(trim((string)$data['mobile'])) ? null : trim($data['mobile']);
        $data['aadhaar_number'] = empty(trim((string)$data['aadhaar_number'])) ? null : trim($data['aadhaar_number']);

        try {
            // Check if already inserted
            if ($masterBuilder->where('id', $data['id'])->countAllResults() == 0) {
                $masterBuilder->insert($data);
                $insertedCount++;
            }
        } catch (\Exception $e) {
            echo "<p style='color:red;'>Error inserting OB ID {$data['id']}: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "<h3 style='color: green;'>Inserted $insertedCount Office Bearers into master_users successfully!</h3>";

} catch (\Throwable $e) {
    echo "<h3 style='color: red;'>Error running sync:</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr/>";
echo "<p><strong>Security Warning:</strong> Please delete this <code>sync_office_bearers.php</code> file from your server after use.</p>";
echo "</body></html>";
