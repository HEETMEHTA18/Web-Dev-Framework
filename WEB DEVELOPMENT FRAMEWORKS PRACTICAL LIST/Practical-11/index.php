<?php include("db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Search Student</h2>
<form method="POST">
    <input type="text" name="search" placeholder="Enter name">
    <button type="submit">Search</button>
</form>

<h2>Student List</h2>
<table border="1">
<tr><th>ID</th><th>Name</th><th>Age</th><th>Department</th><th>Action</th></tr>

<?php
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM students WHERE name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM students";
}

$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>".$row['id']."</td>
            <td>".$row['name']."</td>
            <td>".$row['age']."</td>
            <td>".$row['department_id']."</td>
            <td><a href='delete.php?id=".$row['id']."'>Delete</a></td>
          </tr>";
}
?>
</table>

<br>
<a href="insert.php">➕ Add New Student</a>

</body>
</html>
