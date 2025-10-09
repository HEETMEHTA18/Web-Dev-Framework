<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'authdb';

// connect without selecting DB so we can create it if needed
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) {
    die('DB Connection failed: ' . $conn->connect_error);
}

$safeDb = $conn->real_escape_string($DB_NAME);
if (!$conn->query("CREATE DATABASE IF NOT EXISTS `{$safeDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die('Failed to create or verify database: ' . $conn->error);
}

if (!$conn->select_db($DB_NAME)) {
    die('Failed to select database: ' . $conn->error);
}

$conn->set_charset('utf8mb4');
?>
