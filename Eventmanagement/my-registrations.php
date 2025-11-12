<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Registration.php';

requireLogin();

$registration = new Registration();
$userRegistrations = $registration->getUserRegistrations($_SESSION['user_id']);

// Handle unregistration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid request');
    } else {
        try {
            $eventId = (int)($_POST['event_id'] ?? 0);
            $registration->unregisterUserFromEvent($_SESSION['user_id'], $eventId);
            setFlashMessage('success', 'Unregistered from event successfully!');
            redirect($_SERVER['REQUEST_URI']);
        } catch (Exception $e) {
            setFlashMessage('error', $e->getMessage());
            redirect($_SERVER['REQUEST_URI']);
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
    <title>My Registrations - CHARUSAT Event Management System</title>
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

        .registrations-table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .registrations-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .registrations-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .registrations-table tr:hover {
            background: #f9f9f9;
        }

        .registrations-table tr:last-child td {
            border-bottom: none;
        }

        .event-title {
            font-weight: 600;
            color: #333;
        }

        .event-date {
            color: #667eea;
            font-weight: 600;
        }

        .past-event {
            background: #f9f9f9;
            color: #999;
        }

        .past-event .event-title {
            color: #999;
        }

        .btn-unregister {
            padding: 8px 12px;
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-unregister:hover {
            background: #fcc;
        }

        .btn-unregister:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        <a href="index.php" class="back-link">← Back to Events</a>

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

        <div class="header">
            <h1>My Registrations</h1>
        </div>

        <?php if (empty($userRegistrations)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🎫</div>
                <h2>No registrations yet</h2>
                <p><a href="index.php" style="color: #667eea;">Browse events and register now!</a></p>
            </div>
        <?php else: ?>
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Registered On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userRegistrations as $reg): ?>
                        <?php 
                            $isPast = strtotime($reg['date']) < time();
                            $rowClass = $isPast ? 'past-event' : '';
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td class="event-title"><?php echo htmlspecialchars($reg['title']); ?></td>
                            <td class="event-date"><?php echo date('M d, Y H:i', strtotime($reg['date'])); ?></td>
                            <td><?php echo htmlspecialchars($reg['venue']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($reg['registered_at'])); ?></td>
                            <td>
                                <?php if (!$isPast): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="event_id" value="<?php echo $reg['id']; ?>">
                                        <button type="submit" class="btn-unregister">Unregister</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #999; font-size: 14px;">Event ended</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
