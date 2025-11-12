<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/User.php';

requireAdmin();

$user = new User();
$users = $user->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - CHARUSAT Event Management System</title>
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
            background: rgba(102, 126, 234, 0.2);
            color: white;
            border: 1px solid rgba(102, 126, 234, 0.5);
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(102, 126, 234, 0.4);
            border-color: #667eea;
        }

        .container {
            max-width: 1000px;
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

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #999;
            font-size: 14px;
            margin-top: 5px;
        }

        .users-table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .users-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .users-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .users-table tr:hover {
            background: #f9f9f9;
        }

        .users-table tr:last-child td {
            border-bottom: none;
        }

        .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .user-badge {
            display: inline-block;
            background: #f0f0f0;
            color: #999;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 10px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">🎉 CHARUSAT Events</div>
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
        </div>
        <div class="user-menu">
            <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

        <div class="header">
            <h1>Users Management</h1>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($users); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['role'] === 'admin')); ?></div>
                <div class="stat-label">Admins</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($users, fn($u) => $u['role'] === 'user')); ?></div>
                <div class="stat-label">Regular Users</div>
            </div>
        </div>

        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                <h2>No users yet</h2>
                <p>Users will appear here once they register.</p>
            </div>
        <?php else: ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Member Since</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $usr): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usr['name']); ?></td>
                            <td><?php echo htmlspecialchars($usr['email']); ?></td>
                            <td>
                                <?php if ($usr['role'] === 'admin'): ?>
                                    <span class="admin-badge">Admin</span>
                                <?php else: ?>
                                    <span class="user-badge">User</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($usr['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
