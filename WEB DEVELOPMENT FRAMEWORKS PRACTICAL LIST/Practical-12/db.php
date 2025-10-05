<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "eventdb";

// connect without selecting database so we can create it if missing
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$safeDb = $conn->real_escape_string($db);
if (!$conn->query("CREATE DATABASE IF NOT EXISTS `{$safeDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die("Failed to create or verify database '{$db}': " . $conn->error);
}

if (!$conn->select_db($db)) {
    die("Failed to select database '{$db}': " . $conn->error);
}
?>
