<?php
$host = 'localhost';
$db = 'vpi_ob';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE TABLE IF NOT EXISTS `polling_booths` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `mla_area_id` int(11) NOT NULL,
      `name` varchar(255) NOT NULL,
      `added_by` int(11) DEFAULT NULL,
      `added_by_name` varchar(100) DEFAULT NULL,
      `edited_by` int(11) DEFAULT NULL,
      `edited_by_name` varchar(100) DEFAULT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `mla_area_id` (`mla_area_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $pdo->exec($sql);
    echo "Table 'polling_booths' created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
