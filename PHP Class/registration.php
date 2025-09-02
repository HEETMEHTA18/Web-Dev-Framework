<?php
// filepath: c:\xampp\htdocs\PHP Class\practical 6\registration.php
function sanitize($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

$success = false;
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $course = sanitize($_POST['course'] ?? '');

    if ($name && $email && $course) {
        $data = "$name,$email,$course\n";
        $file = 'registrations.txt';
        if (file_put_contents($file, $data, FILE_APPEND | LOCK_EX) !== false) {
            $success = true;
        } else {
            $error = "Could not save registration.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; }
        .container { background: #fff; padding: 2em; border-radius: 8px; max-width: 400px; margin: 2em auto; box-shadow: 0 2px 8px #ccc; }
        label { display: block; margin-top: 1em; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; }
        .msg { margin-top: 1em; color: green; }
        .error { margin-top: 1em; color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Student Registration</h2>
    <?php if ($success): ?>
        <div class="msg">Registration successful!</div>
    <?php elseif ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <label>Name:
            <input type="text" name="name" required>
        </label>
        <label>Email:
            <input type="email" name="email" required>
        </label>
        <label>Course:
            <select name="course" required>
                <option value="">Select</option>
                <option>BCA</option>
                <option>BSc</option>
                <option>BBA</option>
                <option>BA</option>
                <option>BCom</option>
            </select>
        </label>
        <button type="submit" style="margin-top:1em;">Register</button>
    </form>
</div>
</body>
</html>