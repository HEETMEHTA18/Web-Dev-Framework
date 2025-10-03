<?php
require_once 'db.php';
require_once 'helpers.php';
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? clean_str($_POST['username']) : '';
    $email    = isset($_POST['email']) ? clean_str($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

    if (isset($_SESSION['captcha_sum'])) {
        if (!isset($_POST['captcha']) || (int)$_POST['captcha'] !== (int)$_SESSION['captcha_sum']) {
            $errors[] = 'Captcha answer is incorrect.';
        }
    }

    if (!is_valid_username($username)) {
        $errors[] = 'Username must be 3-30 chars: letters, numbers, underscore only.';
    }
    if (!is_valid_email($email)) {
        $errors[] = 'Invalid email format.';
    }
    if ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    }
    if (!is_strong_password($password)) {
        $errors[] = 'Password must be at least 8 chars and include uppercase, lowercase and a digit.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        if (!$stmt) {
            $errors[] = 'Database error (prepare).';
        } else {
            $stmt->bind_param('ss', $username, $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = 'Username or email already taken.';
            }
            $stmt->close();
        }
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
        if (!$insert) {
            $errors[] = 'Database error (prepare insert).';
        } else {
            $insert->bind_param('sss', $username, $email, $hash);
            if ($insert->execute()) {
                $success = 'Registration successful. You can now log in.';
            } else {
                $errors[] = 'Failed to create user. Try again later.';
            }
            $insert->close();
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title></head>
<body>
<h2>Register</h2>

<?php if ($success): ?>
    <p style="color:green;"><?php echo sanitize_output($success); ?></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
    <?php foreach ($errors as $e): ?>
        <li><?php echo sanitize_output($e); ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="register.php" novalidate>
    Username: <input type="text" name="username" value="<?php echo isset($username) ? sanitize_output($username) : ''; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo isset($email) ? sanitize_output($email) : ''; ?>" required><br>
    Password: <input type="password" name="password" required><br>
    Confirm: <input type="password" name="password2" required><br>

    <?php
    if (empty($_SESSION['captcha_sum'])) {
        $a = rand(2,9);
        $b = rand(1,9);
        $_SESSION['captcha_q'] = "$a + $b = ?";
        $_SESSION['captcha_sum'] = $a + $b;
    }
    ?>
    <label><?php echo sanitize_output($_SESSION['captcha_q']); ?></label>
    <input type="number" name="captcha" required><br>

    <button type="submit">Register</button>
</form>

<script>
document.querySelector('form').addEventListener('submit', function(e){
    const pw = document.querySelector('input[name=password]').value;
    const pw2 = document.querySelector('input[name=password2]').value;
    if (pw !== pw2) {
        alert('Passwords do not match');
        e.preventDefault();
        return;
    }
});
<script>
function validateForm() {
    const username = document.querySelector('input[name=username]').value.trim();
    const email = document.querySelector('input[name=email]').value.trim();
    const pwd = document.querySelector('input[name=password]').value;

    const userRegex = /^[A-Za-z0-9_]{3,30}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!userRegex.test(username)) { alert('Invalid username'); return false; }
    if (!emailRegex.test(email)) { alert('Invalid email'); return false; }
    if (!pwdRegex.test(pwd)) { alert('Weak password'); return false; }
    return true;
}
</script>
<form onsubmit="return validateForm()">
  ...
</form>

</script>

</body>
</html>
