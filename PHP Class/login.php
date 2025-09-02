<?php
// filepath: c:\xampp\htdocs\PHP Class\practical 6\login.php
session_start();

// Dummy user data
$users = [
    'student' => 'password123',
    'admin' => 'adminpass'
];

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    setcookie('rememberme', '', time() - 3600, '/');
    header("Location: login.php");
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user'] = $username;
        if ($remember) {
            setcookie('rememberme', $username, time() + 7*24*3600, '/');
        }
        header("Location: login.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

// Auto-login with cookie
if (!isset($_SESSION['user']) && isset($_COOKIE['rememberme'])) {
    $cookieUser = $_COOKIE['rememberme'];
    if (isset($users[$cookieUser])) {
        $_SESSION['user'] = $cookieUser;
    }
}

// Redirect if logged in
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; }
        .container { background: #fff; padding: 2em; border-radius: 8px; max-width: 400px; margin: 2em auto; box-shadow: 0 2px 8px #ccc; }
        .logout { margin-top: 1em; }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, $user!</h2>
    <p>You are logged in.</p>
    <a class="logout" href="login.php?logout=1">Logout</a>
</div>
</body>
</html>
HTML;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login System</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; }
        .container { background: #fff; padding: 2em; border-radius: 8px; max-width: 400px; margin: 2em auto; box-shadow: 0 2px 8px #ccc; }
        label { display: block; margin-top: 1em; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin-top: 4px; }
        .error { color: red; margin-top: 1em; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <label>Username:
            <input type="text" name="username" required>
        </label>
        <label>Password:
            <input type="password" name="password" required>
        </label>
        <label>
            <input type="checkbox" name="remember"> Remember Me
        </label>
        <button type="submit" style="margin-top:1em;">Login</button>
    </form>
</div>
</body>
</html>