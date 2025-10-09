<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_COOKIE['remember_user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['username'] ?? $_COOKIE['remember_user'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Welcome</title>
</head>
<body style="font-family: Arial; padding: 20px; background: #f9f9f9;">
  <h2>Welcome, <?php echo htmlspecialchars($user); ?>!</h2>
  <p>You are logged in.</p>
  <a href="logout.php">Logout</a>
</body>
</html>
