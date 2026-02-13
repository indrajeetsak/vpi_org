<?php
$dsn = "mysql:host=localhost;dbname=vpi_ob;charset=utf8mb4";
$user = "root";
$password = "";
try {
    $pdo = new PDO($dsn, $user, $password);
    $stmt = $pdo->query("DESCRIBE users");
    $fields = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $fields[] = $row['Field'];
    }
    file_put_contents('schema_dump.txt', implode("\n", $fields));
} catch (Exception $e) {
    file_put_contents('schema_dump.txt', "Error: " . $e->getMessage());
}
