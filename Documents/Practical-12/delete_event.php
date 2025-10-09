<?php include 'db.php'; ?>
<?php
$id = $_GET['id'];
$sql = "DELETE FROM events WHERE event_id=$id";

if ($conn->query($sql)) {
    echo "<p style='color:green;'>Event deleted successfully!</p>";
    header("Refresh:1; url=index.php");
} else {
    echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
}
?>
