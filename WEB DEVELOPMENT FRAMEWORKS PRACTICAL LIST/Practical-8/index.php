<?php
include 'db.php';
$result = $conn->query("SELECT * FROM students");
?>
<!DOCTYPE html>
<html>
<head>
  <title>StudentHub - Students</title>
</head>
<body style="font-family: Arial; padding:20px; background:#f9f9f9;">
  <h2>StudentHub - Student List</h2>
  <a href="add_student.php">Add Student</a> | 
  <a href="update_student.php">Update Student</a>
  <br><br>

  <table border="1" cellpadding="8" cellspacing="0">
    <tr style="background:#ddd;">
      <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['student_id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['course']) ?></td>
        <td><?= $row['year'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
