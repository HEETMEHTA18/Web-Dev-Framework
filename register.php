<!DOCTYPE html>
<html>
<head>
  <title>Registration Form</title>
</head>
<body>
  <h2>User Registration</h2>
  <form action="register.php" method="POST">
    <label>Full Name:</label><br>
    <input type="text" name="fullname" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>
</body>
</html>

<?php

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = sanitize($_POST['fullname']);
    $email    = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);

    
    $file = fopen("users.txt", "a");
    if ($file) {
        fwrite($file, "$fullname | $email | $password\n");
        fclose($file);
        echo "<h2> Registration Successful!</h2>";
        echo "<p>Welcome, $fullname. Your data has been stored.</p>";
    } else {
        echo "<h2> Error: Could not save data.</h2>";
    }
} else {
    echo "<h2>Invalid Request</h2>";
}
?>
