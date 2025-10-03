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
        $tmp = @new mysqli($h, $username, $password, $dbname, $p);
        if ($tmp && $tmp->connect_errno === 0) {
            $conn = $tmp;
            break 2;
        }
        if ($tmp) {
            $lastErr = $tmp->connect_error;
        }
    }
}

if (!$conn) {
    $msg = 'Database Connection Failed. Please ensure MySQL is running in XAMPP and the DB "' . $dbname . '" exists.';
    if ($lastErr) $msg .= ' Last error: ' . $lastErr;
    die($msg);
}
?>
