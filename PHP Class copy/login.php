<?php
session_start();

$error = "";
$success = "";
$counter = isset($_COOKIE['login_counter']) ? intval($_COOKIE['login_counter']) : 0;

// Database connection
$hostname = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "test";
$conne = new mysqli($hostname, $db_username, $db_password, $dbname);
if ($conne->connect_error) {
    die("Connection failed: " . $conne->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $uname = isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : '';
    $pass = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : '';
    $remember = isset($_POST["remember"]);

    if ($action === 'register') {
        // Registration logic
        if ($uname && $pass) {
            // Check if user already exists
            $stmt = $conne->prepare("SELECT * FROM users WHERE name=?");
            $stmt->bind_param("s", $uname);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $error = "Username already exists.";
            } else {
                $stmt_insert = $conne->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
                $stmt_insert->bind_param("ss", $uname, $pass);
                if ($stmt_insert->execute()) {
                    // Get the newly created user's id
                    $newId = $conne->insert_id;
                    $success = "Registration successful! Your user id is: " . intval($newId) . ". You can now log in.";
                } else {
                    $error = "Registration failed: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            }
            $stmt->close();
        } else {
            $error = "Please enter both username and password.";
        }
    } elseif ($action === 'login') {
        // Login logic
        if ($uname && $pass) {
            $stmt = $conne->prepare("SELECT * FROM users WHERE name=? AND password=?");
            $stmt->bind_param("ss", $uname, $pass);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows === 1) {
                $_SESSION['username'] = $uname;
                setcookie('username', $uname, time() + 3600, "/");
                if ($remember) {
                    setcookie('rememberme', $uname, time() + 7 * 24 * 3600, "/");
                }
                // Increment login counter
                $counter++;
                setcookie('login_counter', $counter, time() + 30 * 24 * 3600, "/");
                header("Location: ../PHP-CRUD/index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
            $stmt->close();
        } else {
            $error = "Please enter both username and password.";
        }
    }
}
$conne->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login & Register</title>
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

        .success {
            color: green;
            margin-top: 1em;
        }

        .tab {
            margin-bottom: 1em;
        }

        .tab button {
            padding: 0.5em 1em;
            margin-right: 0.5em;
        }

        .active {
            font-weight: bold;
        }
    </style>
    <script>
        function showTab(tab) {
            document.getElementById('loginForm').style.display = tab === 'login' ? 'block' : 'none';
            document.getElementById('registerForm').style.display = tab === 'register' ? 'block' : 'none';
            document.getElementById('loginTab').classList.toggle('active', tab === 'login');
            document.getElementById('registerTab').classList.toggle('active', tab === 'register');
        }
    </script>
</head>

<body onload="showTab('login')">
    <div class="container">
        <div class="tab">
            <button id="loginTab" onclick="showTab('login')">Login</button>
            <button id="registerTab" onclick="showTab('register')">Register</button>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        <form id="loginForm" method="POST" autocomplete="off" style="display:none;">
            <input type="hidden" name="action" value="login">
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
        <form id="registerForm" method="POST" autocomplete="off" style="display:none;">
            <input type="hidden" name="action" value="register">
            <label>Username:
                <input type="text" name="username" required>
            </label>
            <label>Password:
                <input type="password" name="password" required>
            </label>
            <button type="submit" style="margin-top:1em;">Register</button>
        </form>
        <p>Login Count (cookie): <?= $counter ?></p>
    </div>
    <script>
        // Show login tab by default
        showTab('login');
    </script>
</body>
</html>