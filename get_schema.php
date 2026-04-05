<?php
$mysqli = new mysqli("localhost", "root", "", "vpi_ob");
$result = $mysqli->query("DESCRIBE users");
echo "=== vpi_ob.users ===\n";
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}
$mysqli->close();

$mysqli = new mysqli("localhost", "root", "", "vpi-member");
$result = $mysqli->query("DESCRIBE users");
echo "=== vpi-member.users ===\n";
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}
$mysqli->close();
