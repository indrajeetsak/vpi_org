<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";
try {
    $pdo = new PDO($dsn, $user, $password);
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
