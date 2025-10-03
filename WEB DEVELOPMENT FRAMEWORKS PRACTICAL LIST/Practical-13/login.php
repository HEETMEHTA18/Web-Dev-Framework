<?php
require_once 'db.php';
require_once 'helpers.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = isset($_POST['identifier']) ? clean_str($_POST['identifier']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username_or_email) || empty($password)) {
        $errors[] = 'Please fill required fields.';
    } else {
        $stmt = $conn->prepare('SELECT id, username, email, password_hash FROM users WHERE username = ? OR email = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('ss', $username_or_email, $username_or_email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $user = $res->fetch_assoc()) {
                if (password_verify($password, $user['password_hash'])) {
                    if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $upd = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                        if ($upd) { $upd->bind_param('si', $newHash, $user['id']); $upd->execute(); $upd->close(); }
                    }

                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header('Location: dashboard.php'); // change as needed
                    exit;
                } else {
                    $errors[] = 'Invalid credentials.';
                }
            } else {
                $errors[] = 'Invalid credentials.';
            }
            $stmt->close();
        } else {
            $errors[] = 'Database error (prepare).';
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (!empty($errors)): ?>
    <ul style="color:red;">
    <?php foreach ($errors as $e): ?>
        <li><?php echo sanitize_output($e); ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="login.php">
    Username or Email: <input type="text" name="identifier" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
        