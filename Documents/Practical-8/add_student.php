<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['student_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $course = $conn->real_escape_string($_POST['course']);
    $year = intval($_POST['year']);

    $sql = "INSERT INTO students (student_id, name, email, course, year)
            VALUES ($id, '$name', '$email', '$course', $year)";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Student Added Successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Student</title>
</head>
<body style="font-family: Arial; padding:20px; background:#f9f9f9;">
  <h2>Add New Student</h2>
  <form method="POST" action="">
    ID: <input type="number" name="student_id" required><br><br>
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Course: <input type="text" name="course" required><br><br>
    Year: <input type="number" name="year" required><br><br>
    <button type="submit">Add Student</button>
  </form>
  <br>
  <a href="index.php">Back to List</a>
</body>
</html>
