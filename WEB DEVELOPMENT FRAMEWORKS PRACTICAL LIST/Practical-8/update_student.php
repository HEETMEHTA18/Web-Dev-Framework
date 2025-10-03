<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['student_id']);
    $year = intval($_POST['year']);

    $sql = "UPDATE students SET year=$year WHERE student_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Student Year Updated!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Update Student</title>
</head>
<body style="font-family: Arial; padding:20px; background:#f9f9f9;">
  <h2>Update Student Year</h2>
  <form method="POST" action="">
    Student ID: <input type="number" name="student_id" required><br><br>
    New Year: <input type="number" name="year" required><br><br>
    <button type="submit">Update</button>
  </form>
  <br>
  <a href="index.php">Back to List</a>
</body>
</html>
