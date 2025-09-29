<?php
session_start();

session_destroy();

// if(isset($_SESSION["username"]))
// {
//     echo "Session 'username' is still set.";
//     session_destroy();
//     session_
// }

setcookie('username', '', time() - 3600, "/");
setcookie('rememberme', '', time() - 3600, "/");


header("Location: login.php");
exit();
?>