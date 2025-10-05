<?php
$host = "localhost";
$user = "root";    
$pass = "";        
$dbname = "college"; 

// Connect to MySQL server (no database specified) so we can create the DB if it doesn't exist
$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist, then select it
$createSql = "CREATE DATABASE IF NOT EXISTS `" . mysqli_real_escape_string($conn, $dbname) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (!mysqli_query($conn, $createSql)) {
    die("Failed to create or verify database '$dbname': " . mysqli_error($conn));
}

if (!mysqli_select_db($conn, $dbname)) {
    die("Failed to select database '$dbname': " . mysqli_error($conn));
}
?>
