<?php
$mysqli = new mysqli("localhost", "root", "", "vpi_ob");
if ($mysqli->connect_errno) { echo "Failed to connect to MySQL: " . $mysqli->connect_error; exit(); }
$result = $mysqli->query("SELECT * FROM levels");
while ($row = $result->fetch_assoc()) { echo "ID: " . $row['id'] . " - Name: " . $row['name'] . "\n"; }
$mysqli->close();
