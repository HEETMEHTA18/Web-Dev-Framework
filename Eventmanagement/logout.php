<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/User.php';

$user = new User();
$user->logout();

setFlashMessage('success', 'Logged out successfully!');
redirect('login.php');
?>
