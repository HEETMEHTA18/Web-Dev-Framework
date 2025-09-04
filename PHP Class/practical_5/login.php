<?php
session_start();

define("USER", "admin");
define("PASS", "1234");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = htmlspecialchars($_POST["username"]);
    $pass = htmlspecialchars($_POST["password"]);
    $remember = isset($_POST["remember"]);

    if (USER == $uname && PASS == $pass) {
        $_SESSION['username'] = $uname;
        setcookie('username', $uname, time() + 3600, "/");
        if ($remember) {
            setcookie('rememberme', $uname, time() + 7 * 24 * 3600, "/");
        }
        header("Location: profile.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
        }

        .container {
            background: #fff;
            padding: 2em;
            border-radius: 8px;
            max-width: 400px;
            margin: 2em auto;
            box-shadow: 0 2px 8px #ccc;
        }

        label {
            display: block;
            margin-top: 1em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
        }

        .error {
            color: red;
            margin-top: 1em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <label>Username:
                <input type="text" name="username" required>
            </label>
            <label>Password:
                <input type="password" name="password" required>
            </label>
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
            <button type="submit" style="margin-top:1em;" name="button">Login</button>
        </form>
    </div>
</body>

</html>