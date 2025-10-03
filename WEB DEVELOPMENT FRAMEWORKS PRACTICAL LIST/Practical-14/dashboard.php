<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    header('Content-Type: application/json');
    
    $user_id = (int)$_POST['user_id'];
    $new_status = $_POST['status'] === 'active' ? 'inactive' : 'active';
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND role != 'admin'");
        $stmt->execute([$new_status, $user_id]);
        
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    exit;
}

if ($_POST && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    header('Content-Type: application/json');
    
    $user_id = (int)$_POST['user_id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->execute([$user_id]);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, username, email, full_name, role, status, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $db_error = "Error fetching users: " . $e->getMessage();
}

try {
    $stmt = $pdo->prepare("SELECT 
        COUNT(*) as total_users,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
        SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_users,
        SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin_users
        FROM users");
    $stmt->execute();
    $stats = $stmt->fetch();
} catch (PDOException $e) {
    $stats = ['total_users' => 0, 'active_users' => 0, 'inactive_users' => 0, 'admin_users' => 0];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            color: #333;
            line-height: 1.6;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #3498db;
            display: block;
        }
        
        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }
        
        .users-section {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-title {
            font-size: 1.25rem;
            color: #2c3e50;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-badge:hover {
            opacity: 0.8;
        }
        
        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .role-admin {
            background: #ffeaa7;
            color: #d63031;
        }
        
        .role-user {
            background: #ddd6fe;
            color: #5b21b6;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($db_error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($db_error); ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number"><?php echo $stats['total_users']; ?></span>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $stats['active_users']; ?></span>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $stats['inactive_users']; ?></span>
                <div class="stat-label">Inactive Users</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $stats['admin_users']; ?></span>
                <div class="stat-label">Administrators</div>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="users-section">
            <div class="section-header">
                <h2 class="section-title">User Management</h2>
            </div>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr id="user-<?php echo $user['id']; ?>">
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <span class="status-badge status-<?php echo $user['status']; ?>" 
                                              onclick="toggleStatus(<?php echo $user['id']; ?>, '<?php echo $user['status']; ?>')">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-<?php echo $user['status']; ?>">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td class="actions">
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <button class="btn btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                            Delete
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function toggleStatus(userId, currentStatus) {
            const row = document.getElementById('user-' + userId);
            const statusBadge = row.querySelector('.status-badge');
                row.classList.add('loading');
            
            fetch('dashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=toggle_status&user_id=${userId}&status=${currentStatus}`
            })
            .then(response => response.json())
            .then(data => {
                row.classList.remove('loading');
                
                if (data.success) {
                    statusBadge.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    statusBadge.className = `status-badge status-${data.new_status}`;
                    statusBadge.setAttribute('onclick', `toggleStatus(${userId}, '${data.new_status}')`);
                    location.reload();
                } else {
                    alert('Error updating status: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                row.classList.remove('loading');
                alert('Network error: ' + error.message);
            });
        }
        
        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }
            const row = document.getElementById('user-' + userId);
            row.classList.add('loading');
            fetch('dashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete_user&user_id=${userId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                    location.reload();
                } else {
                    row.classList.remove('loading');
                    alert('Error deleting user: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                row.classList.remove('loading');
                alert('Network error: ' + error.message);
            });
        }
    </script>
</body>
</html>