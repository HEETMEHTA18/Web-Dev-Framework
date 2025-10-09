<?php include("db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Student</title>
</head>
<body>
<h2>Add New Student</h2>

<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Age: <input type="number" name="age" required><br><br>
    Department ID: <input type="number" name="dept_id" required><br><br>
    <button type="submit" name="insert">Insert</button>
</form>

<?php
if (isset($_POST['insert'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $dept = $_POST['dept_id'];

    $sql = "INSERT INTO students (name, age, department_id) VALUES ('$name', '$age', '$dept')";
    if (mysqli_query($conn, $sql)) {
        echo "<p>✅ Student inserted successfully!</p>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>
<br>
<a href="index.php">⬅ Back to Student List</a>
</body>
</html>
