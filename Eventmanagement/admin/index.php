<?php
/**
 * Admin Index Page - Redirects to Admin Dashboard
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/User.php';

// Check if user is logged in and is admin
requireAdmin();

// Redirect to admin dashboard
header('Location: dashboard.php');
exit();
?>
