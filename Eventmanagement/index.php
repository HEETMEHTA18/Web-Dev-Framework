<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Event.php';
require_once __DIR__ . '/classes/Registration.php';
require_once __DIR__ . '/classes/User.php';

// Check for auto-login
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    $user = new User();
    if ($user->autoLogin($_COOKIE['remember_token'])) {
        // Auto-login successful
    } else {
        redirect('login.php');
    }
}

requireLogin();

$event = new Event();
$registration = new Registration();
$user = new User();

// Get all upcoming events
$events = $event->getAllEvents(true);

// Get user's registrations
$userRegistrations = $registration->getUserRegistrations($_SESSION['user_id']);
$registeredEventIds = array_column($userRegistrations, 'id');

// Handle registration/unregistration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid request');
    } else {
        try {
            $action = $_POST['action'] ?? '';
            $eventId = (int)($_POST['event_id'] ?? 0);

            if ($action === 'register') {
                $registration->registerUserForEvent($_SESSION['user_id'], $eventId);
                setFlashMessage('success', 'Registered for event successfully!');
            } elseif ($action === 'unregister') {
                $registration->unregisterUserFromEvent($_SESSION['user_id'], $eventId);
                setFlashMessage('success', 'Unregistered from event successfully!');
            }

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
    <title>Dashboard - CHARUSAT Event Management System</title>
    <script>
        document.cookie = "theme=dark; expires=Fri, 31 Dec 2025 12:00:00 UTC; path=/";
        console.log(document.cookie);

    </script>
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

        .user-info {
            font-size: 14px;
            color: #b0b0b0;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-logout {
            background: rgba(102, 126, 234, 0.2);
            color: white;
            border: 1px solid rgba(102, 126, 234, 0.5);
        }

        .btn-logout:hover {
            background: rgba(102, 126, 234, 0.4);
            border-color: #667eea;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
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
            background-color: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff6b6b;
        }

        .alert-success {
            background-color: rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.3);
            color: #4ade80;
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #a0a0a0;
            font-size: 16px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .event-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 10px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.5);
        }

        .event-title {
            font-size: 20px;
            font-weight: bold;
            color: #e0e0e0;
            margin-bottom: 10px;
        }

        .event-description {
            color: #b0b0b0;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .event-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #a0a0a0;
        }

        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .event-meta-icon {
            font-size: 16px;
        }

        .event-participants {
            background: rgba(102, 126, 234, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
            color: #667eea;
            font-weight: 600;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .event-actions {
            display: flex;
            gap: 10px;
        }

        .btn-register {
            flex: 1;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-unregister {
            flex: 1;
            padding: 10px;
            background: rgba(255, 0, 0, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(255, 0, 0, 0.2);
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-unregister:hover {
            background: #fee;
            border-color: #fcc;
        }

        .btn-view-details {
            flex: 1;
            padding: 10px;
            background: white;
            color: #667eea;
            border: 1px solid #667eea;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-view-details:hover {
            background: #f0f0ff;
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

        .registered-badge {
            display: inline-block;
            background: #efe;
            color: #3c3;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .admin-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .admin-link:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-menu {
                flex-direction: column;
                gap: 0.5rem;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">🎉 CHARUSAT Events</div>
        <div class="navbar-menu">
            <a href="index.php">Home</a>
            <?php if (isAdmin()): ?>
                <a href="admin/dashboard.php">Admin Panel</a>
            <?php endif; ?>
            <a href="my-registrations.php">My Registrations</a>
            <a href="profile.php">Profile</a>
        </div>
        <div class="user-menu">
            <div class="user-info">
                👤 <?php echo htmlspecialchars($_SESSION['name']); ?>
                <?php if (isAdmin()): ?>
                    <span style="background: rgba(255,255,255,0.3); padding: 2px 6px; border-radius: 3px; font-size: 12px; margin-left: 5px;">(Admin)</span>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
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
            <h1>Upcoming Events</h1>
            <p>Register for CHARUSAT events and stay updated with campus activities</p>
        </div>

        <?php if (empty($events)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📅</div>
                <h2>No events available</h2>
                <p>Check back soon for new events!</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($events as $evt): ?>
                    <div class="event-card">
                        <?php if (in_array($evt['id'], $registeredEventIds)): ?>
                            <div class="registered-badge">✓ Registered</div>
                        <?php endif; ?>

                        <div class="event-title"><?php echo htmlspecialchars($evt['title']); ?></div>
                        <div class="event-description"><?php echo htmlspecialchars(substr($evt['description'], 0, 100)); ?>...</div>

                        <div class="event-meta">
                            <div class="event-meta-item">
                                <span class="event-meta-icon">📅</span>
                                <span><?php echo date('M d, Y H:i', strtotime($evt['date'])); ?></span>
                            </div>
                            <div class="event-meta-item">
                                <span class="event-meta-icon">📍</span>
                                <span><?php echo htmlspecialchars($evt['venue']); ?></span>
                            </div>
                        </div>

                        <div class="event-participants">
                            👥 <?php echo $evt['participant_count']; ?> participants
                        </div>

                        <div class="event-actions">
                            <a href="event-details.php?id=<?php echo $evt['id']; ?>" class="btn-view-details">View Details</a>
                            <?php if (in_array($evt['id'], $registeredEventIds)): ?>
                                <form method="POST" style="flex: 1;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="unregister">
                                    <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                    <button type="submit" class="btn-unregister">Unregister</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="flex: 1;">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="action" value="register">
                                    <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                    <button type="submit" class="btn-register">Register</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isAdmin()): ?>
            <div style="margin-top: 3rem; text-align: center;">
                <a href="admin/dashboard.php" class="admin-link">Go to Admin Panel →</a>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>
