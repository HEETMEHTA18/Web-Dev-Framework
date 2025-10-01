<?php
// Simple migration helper to ensure 'users' table has an AUTO_INCREMENT primary key 'id'.
// Run once in browser: http://localhost/PHP%20Class/migrate_users_table.php

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'test';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('Connect error: ' . $mysqli->connect_error);
}

function columnExists($mysqli, $table, $column){
    $sql = "SHOW COLUMNS FROM `" . $mysqli->real_escape_string($table) . "` LIKE '" . $mysqli->real_escape_string($column) . "'";
    $res = $mysqli->query($sql);
    return ($res && $res->num_rows > 0);
}

// Ensure users table exists
$res = $mysqli->query("SHOW TABLES LIKE 'users'");
if (!$res || $res->num_rows === 0) {
    echo "Table 'users' does not exist in database '{$db}'.\n";
    echo "Create it with:
CREATE TABLE users (\n  id INT PRIMARY KEY AUTO_INCREMENT,\n  name VARCHAR(100) NOT NULL UNIQUE,\n  password VARCHAR(255) NOT NULL\n);";
    exit;
}

// Check if 'id' column exists
if (!columnExists($mysqli, 'users', 'id')) {
    echo "'id' column missing — adding INT NOT NULL AUTO_INCREMENT PRIMARY KEY...<br>\n";
    $alter = "ALTER TABLE `users` ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
    if ($mysqli->query($alter) === TRUE) {
        echo "Added 'id' column as AUTO_INCREMENT primary key.<br>\n";
    } else {
        echo "Failed to add 'id' column: " . $mysqli->error . "<br>\n";
        exit;
    }
} else {
    // If column exists, check if it's auto_increment and primary
    $row = $mysqli->query("SHOW COLUMNS FROM `users` LIKE 'id'")->fetch_assoc();
    $extra = $row['Extra'] ?? '';
    $key = $row['Key'] ?? '';
    $needsAlter = false;
    if (stripos($extra, 'auto_increment') === false) {
        echo "'id' column exists but is not AUTO_INCREMENT — attempting to modify...<br>\n";
        $needsAlter = true;
    }
    if (strtoupper($key) !== 'PRI') {
        echo "'id' column exists but is not PRIMARY KEY — attempting to modify...<br>\n";
        $needsAlter = true;
    }
    if ($needsAlter) {
        // To make existing column auto_increment primary, alter it. Be careful: if data present it may fail.
        $alter = "ALTER TABLE `users` MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
        if ($mysqli->query($alter) === TRUE) {
            echo "Modified 'id' column to be AUTO_INCREMENT PRIMARY KEY.<br>\n";
        } else {
            echo "Failed to modify 'id' column: " . $mysqli->error . "<br>\n";
            echo "If this fails, consider creating a new column and migrating values manually.";
            exit;
        }
    } else {
        echo "'id' column already exists and is AUTO_INCREMENT PRIMARY KEY — nothing to do.<br>\n";
    }
}

// Show final columns
echo "<h3>Current 'users' table columns:</h3>\n<pre>";
$cols = $mysqli->query("SHOW COLUMNS FROM `users`");
while ($c = $cols->fetch_assoc()) {
    echo htmlspecialchars(print_r($c, true));
}
echo "</pre>";

$mysqli->close();

?>