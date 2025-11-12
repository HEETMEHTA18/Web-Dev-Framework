<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/Event.php';
require_once __DIR__ . '/../classes/Registration.php';

requireAdmin();

$eventId = (int)($_GET['id'] ?? 0);

if ($eventId === 0) {
    redirect('dashboard.php');
}

$event = new Event();
$registration = new Registration();

$eventDetails = $event->getEventById($eventId);

if (!$eventDetails) {
    redirect('dashboard.php');
}

$error = null;
$success = null;

// Handle event update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request";
    } else {
        try {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['date'] ?? '';
            $venue = $_POST['venue'] ?? '';

            $event->updateEvent($eventId, $title, $description, $date, $venue);

            setFlashMessage('success', 'Event updated successfully!');
            redirect($_SERVER['REQUEST_URI']);
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
    <title>Edit Event - CHARUSAT Event Management System</title>
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

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
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
        textarea {
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
        textarea:focus {
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

        @media (max-width: 600px) {
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

        .action-links {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-link {
            padding: 10px 20px;
            background: white;
            color: #667eea;
            border: 1px solid #667eea;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-link:hover {
            background: #f0f0ff;
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
            <h1>Edit Event</h1>
        </div>

        <div class="form-card">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Event Title</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            value="<?php echo htmlspecialchars($eventDetails['title']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="venue">Venue</label>
                        <input 
                            type="text" 
                            id="venue" 
                            name="venue" 
                            value="<?php echo htmlspecialchars($eventDetails['venue']); ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        required
                    ><?php echo htmlspecialchars($eventDetails['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="date">Date & Time</label>
                    <input 
                        type="datetime-local" 
                        id="date" 
                        name="date" 
                        value="<?php echo date('Y-m-d\TH:i', strtotime($eventDetails['date'])); ?>"
                        required
                    >
                </div>

                <button type="submit" class="btn">Update Event</button>

                <div class="action-links">
                    <a href="participants.php?id=<?php echo $eventId; ?>" class="action-link">View Participants</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
