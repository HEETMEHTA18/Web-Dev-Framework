<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes/Event.php';
require_once __DIR__ . '/classes/Registration.php';

requireLogin();

$eventId = (int)($_GET['id'] ?? 0);

if ($eventId === 0) {
    setFlashMessage('error', 'Event not found');
    redirect('index.php');
}

$event = new Event();
$registration = new Registration();

$eventDetails = $event->getEventById($eventId);

if (!$eventDetails) {
    setFlashMessage('error', 'Event not found');
    redirect('index.php');
}

$isRegistered = $registration->isUserRegistered($_SESSION['user_id'], $eventId);
$isPast = strtotime($eventDetails['date']) < time();

// Handle registration/unregistration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid request');
    } else {
        try {
            $action = $_POST['action'] ?? '';

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
    <title><?php echo htmlspecialchars($eventDetails['title']); ?> - CHARUSAT Event Management System</title>
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
        }

        .navbar-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .navbar-menu a:hover {
            opacity: 0.8;
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
            max-width: 900px;
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

        .event-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .event-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .event-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #eee;
        }

        @media (max-width: 600px) {
            .event-meta {
                grid-template-columns: 1fr;
            }
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .meta-icon {
            font-size: 24px;
        }

        .meta-content {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
        }

        .meta-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .event-description {
            margin: 30px 0;
            line-height: 1.8;
            color: #555;
            font-size: 16px;
        }

        .stats-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 30px 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 600px) {
            .stats-section {
                grid-template-columns: 1fr;
            }
        }

        .stat-item {
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

        .action-section {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
            flex: 1;
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-unregister {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .btn-unregister:hover {
            background: #fcc;
        }

        .past-event-notice {
            background: #ffe8e8;
            border: 1px solid #ffcccc;
            color: #c33;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .registration-status {
            background: #efe;
            border: 1px solid #cfc;
            color: #3c3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
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

        <div class="event-card">
            <?php if ($isPast): ?>
                <div class="past-event-notice">
                    📌 This event has already ended
                </div>
            <?php endif; ?>

            <?php if ($isRegistered): ?>
                <div class="registration-status">
                    ✓ You are registered for this event
                </div>
            <?php endif; ?>

            <h1 class="event-title"><?php echo htmlspecialchars($eventDetails['title']); ?></h1>

            <div class="event-meta">
                <div class="meta-item">
                    <div class="meta-icon">📅</div>
                    <div class="meta-content">
                        <div class="meta-label">Date</div>
                        <div class="meta-value"><?php echo date('l, M d, Y', strtotime($eventDetails['date'])); ?></div>
                    </div>
                </div>

                <div class="meta-item">
                    <div class="meta-icon">🕐</div>
                    <div class="meta-content">
                        <div class="meta-label">Time</div>
                        <div class="meta-value"><?php echo date('h:i A', strtotime($eventDetails['date'])); ?></div>
                    </div>
                </div>

                <div class="meta-item">
                    <div class="meta-icon">📍</div>
                    <div class="meta-content">
                        <div class="meta-label">Venue</div>
                        <div class="meta-value"><?php echo htmlspecialchars($eventDetails['venue']); ?></div>
                    </div>
                </div>

                <div class="meta-item">
                    <div class="meta-icon">👥</div>
                    <div class="meta-content">
                        <div class="meta-label">Participants</div>
                        <div class="meta-value"><?php echo $eventDetails['participant_count']; ?> registered</div>
                    </div>
                </div>
            </div>

            <div class="event-description">
                <h2 style="margin-bottom: 15px; color: #333;">Event Description</h2>
                <?php echo nl2br(htmlspecialchars($eventDetails['description'])); ?>
            </div>

            <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $eventDetails['participant_count']; ?></div>
                    <div class="stat-label">Total Participants</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo max(0, strtotime($eventDetails['date']) - time()); ?></div>
                    <div class="stat-label">Days Until Event</div>
                </div>
            </div>

            <div class="action-section">
                <?php if ($isRegistered): ?>
                    <form method="POST" style="flex: 1;">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="unregister">
                        <button type="submit" class="btn btn-unregister">Unregister</button>
                    </form>
                <?php else: ?>
                    <?php if (!$isPast): ?>
                        <form method="POST" style="flex: 1;">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="register">
                            <button type="submit" class="btn">Register for Event</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
