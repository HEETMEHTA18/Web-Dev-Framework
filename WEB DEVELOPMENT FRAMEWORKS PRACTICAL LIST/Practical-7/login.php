<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit();
}

$valid_username = "admin";
$valid_password = "1234";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;

        if (isset($_POST['remember'])) {
            setcookie("remember_user", $username, time() + 3600, "/");
        }

        header("Location: welcome.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body style="font-family: Arial; padding: 20px; background: #f9f9f9;">
  <h2>Login Page</h2>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>
      <input type="checkbox" name="remember"> Remember Me
    </label><br><br>

    <button type="submit">Login</button>
  </form>
</body>
</html>
