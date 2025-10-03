<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();
} else {
    if (time() - $_SESSION['start_time'] > 300) { // 5 min timeout
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body style="font-family: Arial; padding:20px; background:#f9f9f9;">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
  <p>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
    <p>This is your dashboard.</p>
  <a href="logout.php">Logout</a>
</body>
</html>
