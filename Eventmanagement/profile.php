<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/User.php';

requireLogin();

$user = new User();
$userProfile = $user->getUserById($_SESSION['user_id']);

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request";
    } else {
        try {
            $action = $_POST['action'] ?? '';

            if ($action === 'update_profile') {
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';

                $user->updateProfile($_SESSION['user_id'], $name, $email);

                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;

                setFlashMessage('success', 'Profile updated successfully!');
                redirect($_SERVER['REQUEST_URI']);
            } elseif ($action === 'change_password') {
                $oldPassword = $_POST['old_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';

                $user->changePassword($_SESSION['user_id'], $oldPassword, $newPassword, $newPasswordConfirm);

                setFlashMessage('success', 'Password changed successfully!');
                redirect($_SERVER['REQUEST_URI']);
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CHARUSAT Event Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 50%, #16213e 100%);
            color: #e0e0e0;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .navbar-menu a {
            color: #e0e0e0;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .navbar-menu a:hover {
            color: #667eea;
        }

        .user-menu {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-logout {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        .alert-success {
            background-color: #efe;
            border: 1px solid #cfc;
            color: #3c3;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .profile-card h2 {
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .btn {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .profile-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .profile-info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-info-item:last-child {
            border-bottom: none;
        }

        .profile-info-label {
            font-weight: 600;
            color: #555;
        }

        .profile-info-value {
            color: #333;
        }

        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">🎉 CHARUSAT Events</div>
        <div class="navbar-menu">
            <a href="index.php">Home</a>
            <a href="my-registrations.php">My Registrations</a>
            <a href="profile.php">Profile</a>
        </div>
        <div class="user-menu">
            <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <a href="index.php" class="back-link">← Back to Home</a>

        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php if ($flash['type'] === 'error'): ?>
                    ⚠️
                <?php else: ?>
                    ✓
                <?php endif; ?>
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="header">
            <h1>My Profile</h1>
        </div>

        <!-- Profile Information Card -->
        <div class="profile-card">
            <h2>Profile Information</h2>
            <div class="profile-info">
                <div class="profile-info-item">
                    <span class="profile-info-label">Role</span>
                    <span class="profile-info-value">
                        <span class="role-badge"><?php echo ucfirst(htmlspecialchars($userProfile['role'])); ?></span>
                    </span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Member Since</span>
                    <span class="profile-info-value"><?php echo date('M d, Y', strtotime($userProfile['created_at'])); ?></span>
                </div>
            </div>
        </div>

        <!-- Edit Profile Card -->
        <div class="profile-card">
            <h2>Edit Profile</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_profile">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?php echo htmlspecialchars($userProfile['name']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($userProfile['email']); ?>"
                        required
                    >
                </div>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>

        <!-- Change Password Card -->
        <div class="profile-card">
            <h2>Change Password</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="change_password">

                <div class="form-group">
                    <label for="old_password">Current Password</label>
                    <input 
                        type="password" 
                        id="old_password" 
                        name="old_password" 
                        placeholder="Enter your current password"
                        required
                    >
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            placeholder="Enter new password (minimum 8 characters)"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirm">Confirm New Password</label>
                        <input 
                            type="password" 
                            id="new_password_confirm" 
                            name="new_password_confirm" 
                            placeholder="Confirm new password"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="btn">Change Password</button>
            </form>
        </div>
    </div>
</body>
</html>
