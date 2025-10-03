<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Add Event</title></head>
<body>
<h2>Add New Event</h2>
<form method="POST" enctype="multipart/form-data">
    Title: <input type="text" name="title" required><br>
    Date: <input type="date" name="date" required><br>
    Location: <input type="text" name="location" required><br>
    Status: 
    <select name="status">
        <option value="open">Open</option>
        <option value="closed">Closed</option>
    </select><br>
    Poster: <input type="file" name="poster"><br><br>
    <input type="submit" name="submit" value="Save Event">
</form>

<?php
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    
    $poster = null;
    if (!empty($_FILES['poster']['name'])) {
        $poster = time() . "_" . $_FILES['poster']['name'];
        move_uploaded_file($_FILES['poster']['tmp_name'], "upload/" . $poster);
    }

    $sql = "INSERT INTO events (title,date,location,status,poster) 
            VALUES ('$title','$date','$location','$status','$poster')";

    if ($conn->query($sql)) {
        echo "<p style='color:green;'>Event added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>
</body>
</html>
