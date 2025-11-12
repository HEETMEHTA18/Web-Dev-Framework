<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Event.php';
require_once __DIR__ . '/../classes/Registration.php';
require_once __DIR__ . '/../classes/User.php';

requireAdmin();

$event = new Event();
$registration = new Registration();
$user = new User();

// Get statistics
$stats = $registration->getStatistics();

// Get all events
$events = $event->getAllEvents(false);

// Handle event creation/deletion
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request";
    } else {
        try {
            $action = $_POST['action'] ?? '';

            if ($action === 'create_event') {
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $date = $_POST['date'] ?? '';
                $venue = $_POST['venue'] ?? '';

                $event->createEvent($title, $description, $date, $venue);

                setFlashMessage('success', 'Event created successfully!');
                redirect($_SERVER['REQUEST_URI']);
            } elseif ($action === 'delete_event') {
                $eventId = (int)($_POST['event_id'] ?? 0);
                $event->deleteEvent($eventId);

                setFlashMessage('success', 'Event deleted successfully!');
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
    <title>Admin Dashboard - CHARUSAT Event Management System</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 32px;
        }

        .admin-badge {
            display: inline-block;
            background: #fff;
            color: #667eea;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #999;
            font-size: 14px;
        }

        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .section h2 {
            font-size: 24px;
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
        input[type="datetime-local"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="datetime-local"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
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
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-small {
            padding: 8px 12px;
            font-size: 14px;
        }

        .btn-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .btn-danger:hover {
            background: #fcc;
        }

        .btn-export {
            background: #4CAF50;
        }

        .btn-export:hover {
            background: #45a049;
        }

        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .events-table th {
            background: #f9f9f9;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }

        .events-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .events-table tr:hover {
            background: #f9f9f9;
        }

        .event-title {
            font-weight: 600;
            color: #333;
        }

        .event-actions {
            display: flex;
            gap: 10px;
        }

        .event-date {
            color: #667eea;
            font-weight: 600;
        }

        .upcoming-badge {
            display: inline-block;
            background: #efe;
            color: #3c3;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .past-badge {
            display: inline-block;
            background: #f9f9f9;
            color: #999;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-cancel {
            background: #f0f0f0;
            color: #333;
        }

        .btn-cancel:hover {
            background: #e0e0e0;
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
            <span>👤 <?php echo htmlspecialchars($_SESSION['name']); ?> <span class="admin-badge">Admin</span></span>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
        </div>

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

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                <div class="stat-label">Total Users</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value"><?php echo $stats['total_events']; ?></div>
                <div class="stat-label">Total Events</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-value"><?php echo $stats['total_registrations']; ?></div>
                <div class="stat-label">Registrations</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⏰</div>
                <div class="stat-value"><?php echo $stats['upcoming_events']; ?></div>
                <div class="stat-label">Upcoming Events</div>
            </div>
        </div>

        <!-- Create Event Section -->
        <div class="section">
            <h2>Create New Event</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="create_event">

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Event Title</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            placeholder="Enter event title"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="venue">Venue</label>
                        <input 
                            type="text" 
                            id="venue" 
                            name="venue" 
                            placeholder="Enter event venue"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Enter event description"
                        required
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="date">Date & Time</label>
                    <input 
                        type="datetime-local" 
                        id="date" 
                        name="date" 
                        required
                    >
                </div>

                <button type="submit" class="btn">Create Event</button>
            </form>
        </div>

        <!-- Events Table Section -->
        <div class="section">
            <h2>All Events</h2>
            <?php if (empty($events)): ?>
                <p style="color: #999; text-align: center; padding: 20px;">No events yet. Create your first event!</p>
            <?php else: ?>
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Event Title</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Participants</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $evt): ?>
                            <?php $isPast = strtotime($evt['date']) < time(); ?>
                            <tr>
                                <td class="event-title"><?php echo htmlspecialchars($evt['title']); ?></td>
                                <td class="event-date"><?php echo date('M d, Y H:i', strtotime($evt['date'])); ?></td>
                                <td><?php echo htmlspecialchars($evt['venue']); ?></td>
                                <td><?php echo $evt['participant_count']; ?></td>
                                <td>
                                    <?php if ($isPast): ?>
                                        <span class="past-badge">Past Event</span>
                                    <?php else: ?>
                                        <span class="upcoming-badge">Upcoming</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="event-actions">
                                        <a href="event-details.php?id=<?php echo $evt['id']; ?>" class="btn btn-small">View</a>
                                        <a href="participants.php?id=<?php echo $evt['id']; ?>" class="btn btn-small">Participants</a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this event?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="action" value="delete_event">
                                            <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                            <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
