<?php include 'db.php'; ?>
<?php
$id = $_GET['id'];
$event = $conn->query("SELECT * FROM events WHERE event_id=$id")->fetch_assoc();

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    
    $poster = $event['poster'];
    if (!empty($_FILES['poster']['name'])) {
        $poster = time() . "_" . $_FILES['poster']['name'];
        move_uploaded_file($_FILES['poster']['tmp_name'], "upload/" . $poster);
    }

    $sql = "UPDATE events SET title='$title', date='$date', location='$location', status='$status', poster='$poster' WHERE event_id=$id";

    if ($conn->query($sql)) {
        echo "<p style='color:green;'>Event updated successfully!</p>";
        header("Refresh:1; url=index.php");
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Event</title></head>
<body>
<h2>Edit Event</h2>
<form method="POST" enctype="multipart/form-data">
    Title: <input type="text" name="title" value="<?php echo $event['title']; ?>"><br>
    Date: <input type="date" name="date" value="<?php echo $event['date']; ?>"><br>
    Location: <input type="text" name="location" value="<?php echo $event['location']; ?>"><br>
    Status: 
    <select name="status">
        <option value="open" <?php if($event['status']=='open') echo 'selected'; ?>>Open</option>
        <option value="closed" <?php if($event['status']=='closed') echo 'selected'; ?>>Closed</option>
    </select><br>
    Current Poster: <?php echo $event['poster'] ? "<img src='upload/{$event['poster']}' width='100'>" : "None"; ?><br>
    New Poster: <input type="file" name="poster"><br><br>
    <input type="submit" name="update" value="Update Event">
</form>
</body>
</html>
