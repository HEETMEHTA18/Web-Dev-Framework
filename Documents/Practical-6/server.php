<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $form = __DIR__ . DIRECTORY_SEPARATOR . 'register.html';
    if (is_readable($form)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($form);
        exit;
    }
    echo "<h2>Registration</h2><p>Registration form not found. Please open <a href=\"register.html\">register.html</a>.</p>";
    exit;
}
function get_post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$name = htmlspecialchars(get_post('name'));
$email = htmlspecialchars(get_post('email'));
$password = get_post('password');

$errors = [];

if ($name === '') {
    $errors[] = 'Name is required.';
}

if ($email === '') {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email is not a valid email address.';
}

if ($password === '') {
    $errors[] = 'Password is required.';
} elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

if (!empty($errors)) {
    echo "<h2>Registration Failed</h2>";
    echo "<ul>";
    foreach ($errors as $e) {
        echo '<li>' . htmlspecialchars($e) . '</li>';
    }
    echo "</ul>";
    echo "<p><a href=\"register.html\">Go Back</a></p>";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$entry = [
    'name' => $name,
    'email' => $email,
    'passwordHash' => $passwordHash,
    'createdAt' => date('c')
];

$line = json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n";
$file = __DIR__ . DIRECTORY_SEPARATOR . 'registrations.txt';
if (file_put_contents($file, $line, FILE_APPEND | LOCK_EX) === false) {
    echo "<h2>Error</h2><p>Unable to store registration. Check file permissions.</p>";
    exit;
}


echo "<!doctype html><html><head><meta charset=\"utf-8\"><title>Registration Successful</title></head><body style=\"font-family:Arial,sans-serif;padding:20px;background:#f9f9f9\">";
echo "<h2>Registration Successful!</h2>";
echo "<p>Thank you, <strong>" . htmlspecialchars($name) . "</strong>. Your registration has been recorded.</p>";
echo "<p><a href=\"register.html\">Register another user</a></p>";
echo "</body></html>";

?>
