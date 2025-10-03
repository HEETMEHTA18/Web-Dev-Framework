<?php
session_start();

// Load users from external credentials file (not tracked by git)
$credentialsFile = __DIR__ . '/credentials.php';
if (file_exists($credentialsFile)) {
    $users = include $credentialsFile;
} else {
    // Fallback - create credentials.php from template
    $users = [
        ["username" => "demo", "password" => "demo123", "role" => "user"]
    ];
    error_log("Warning: credentials.php not found. Using demo credentials.");
}

if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = trim($_POST['username']);
    $pwd   = trim($_POST['password']);

    $valid = false;
    foreach ($users as $user) {
        if ($uname === $user['username'] && $pwd === $user['password']) {
            $_SESSION['username'] = $uname;
            $_SESSION['role'] = $user['role'];
            $valid = true;
            break;
        }
    }

    if ($valid) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body style="font-family: Arial; padding:20px; background:#f9f9f9;">
  <h2>Login Page</h2>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  
  <form method="POST" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
