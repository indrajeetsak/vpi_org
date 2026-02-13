<?php
// Manual script to query levels
$mysqli = new mysqli("localhost", "root", "", "vpi_ob");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$sql = "SELECT id, name FROM levels";
$result = $mysqli->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " - Name: " . $row['name'] . "\r\n";
    }
    $result->free();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
