<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Event Management</h2>
    <a href="add_event.php">+ Add Event</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Title</th><th>Date</th><th>Location</th><th>Status</th><th>Poster</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM events");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['event_id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['date']}</td>
                <td>{$row['location']}</td>
                <td>{$row['status']}</td>
                <td>" . ($row['poster'] ? "<img src='upload/{$row['poster']}' width='80'>" : "No Poster") . "</td>
                <td>
                    <a href='edit_event.php?id={$row['event_id']}'>Edit</a> |
                    <a href='delete_event.php?id={$row['event_id']}' onclick='return confirm(\"Delete this event?\");'>Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
