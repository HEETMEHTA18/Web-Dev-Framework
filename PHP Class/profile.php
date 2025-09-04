<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
</head>
<body>
    <h1>User Profile</h1>
    <?php
    if (isset($_SESSION["username"])) {
        echo "Welcome to the profile page<br>";
        echo "Username (Session): " . htmlspecialchars($_SESSION["username"]) . "<br>";
    } else {
        echo "No user data submitted.<br>";
    }

    // Show username from cookie if available
    if (isset($_COOKIE["username"])) {
        echo "Username (Cookie): " . htmlspecialchars($_COOKIE["username"]) . "<br>";
    }
    
    if (isset($_COOKIE["rememberme"])) {
        echo "Remember Me (Cookie): " . htmlspecialchars($_COOKIE["rememberme"]) . "<br>";
    }
    ?>
    <br>
    <a href="login.php?logout=1">Logout</a>
</body>
</html>