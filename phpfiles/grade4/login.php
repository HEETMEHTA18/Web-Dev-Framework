<?php
// login.php
session_start();

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // hash of XyZzy12*_php123

$failure = false;

// Handle logout (defensive)
if (isset($_POST['logout'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = isset($_POST['who']) ? trim($_POST['who']) : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';

    if ($user === '' || $pass === '') {
        $failure = "User name and password are required";
    } else {
        $check = hash('md5', $salt . $pass);
        if ($check === $stored_hash) {
            header('Location: game.php?name=' . urlencode($user));
            exit;
        } else {
            $failure = "Incorrect password";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>f6789b89 - Login</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 500px; margin: 40px auto; }
    label { display:block; margin-top: 10px; }
    input[type="text"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; }
    .actions { margin-top: 15px; display: flex; gap: 10px; }
  </style>
</head>
<body>
  <h1>Please Log In</h1>
  <?php if ($failure !== false): ?>
    <p style="color: red;"><?php echo htmlspecialchars($failure); ?></p>
  <?php endif; ?>

  <form method="POST">
    <label for="who">User Name</label>
    <input type="text" name="who" id="who" autocomplete="username" />

    <label for="pass">Password</label>
    <input type="password" name="pass" id="pass" autocomplete="current-password" />

    <div class="actions">
      <input type="submit" value="Log In" />
      <button type="submit" name="logout" value="1">Cancel</button>
    </div>
  </form>
  <!-- Hint: Password is php123 -->
</body>
</html>
