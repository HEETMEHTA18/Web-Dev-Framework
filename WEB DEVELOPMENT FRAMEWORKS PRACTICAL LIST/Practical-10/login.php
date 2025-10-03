<?php
session_start();
$users = [
    ["username" => "admin", "password" => "admin@123", "role" => "admin"],
    ["username" => "student1", "password" => "stud123", "role" => "user"],
    ["username" => "student2", "password" => "pass321", "role" => "user"]
];

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
