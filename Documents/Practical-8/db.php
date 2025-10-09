<?php
$hosts = ['127.0.0.1', 'localhost'];
$ports = [3307, 3306];
$username = 'root';
$password = '';
$dbname = 'studenthub';

$conn = null;
$lastErr = null;
foreach ($hosts as $h) {
    foreach ($ports as $p) {
        // connect without selecting a database so we can create it if missing
        $tmp = @new mysqli($h, $username, $password, "", $p);
        if ($tmp && $tmp->connect_errno === 0) {
            // create database if it doesn't exist
            $safeDb = $tmp->real_escape_string($dbname);
            if (!$tmp->query("CREATE DATABASE IF NOT EXISTS `{$safeDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
                $lastErr = $tmp->error;
            } else {
                // select the database
                if ($tmp->select_db($dbname)) {
                    $conn = $tmp;
                    break 2;
                } else {
                    $lastErr = $tmp->error;
                }
            }
        }
        if ($tmp) {
            $lastErr = $tmp->connect_error ?: $lastErr;
        }
    }
}

if (!$conn) {
    $msg = 'Database Connection Failed. Please ensure MySQL is running in XAMPP and the DB "' . $dbname . '" can be created.';
    if ($lastErr) $msg .= ' Last error: ' . $lastErr;
    die($msg);
}
?>
