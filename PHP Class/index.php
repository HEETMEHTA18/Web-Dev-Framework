<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - CRUD</title>
</head>
<body>
    <h1>PHP - CRUD</h1>
    <h2>   <?php
    $name = "Heet";
    $id = "24ce064";
    $class = "Ce";
    echo " Hello World My Name is ".$name. "<br>" ."Id is :".$id . "<br>" ."Class:".$class;
    echo "<br>";
    print($name);
    $fruits = ["Mehta", "Banana", "Mango"];
    echo "$fruits[0]". "<br>";

    $students = ["name"=>"Heet","Age"=>"20","Id"=>"24ce064"];
    echo $students["name"];
    echo $students["Age"]="21";
    echo $students["Id"];
    foreach($students as $key => $value)
    {
        echo $key .":" . $value . "<br>";
    }
    echo time();
    echo date("1");

    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        $name = $_POST["name"];
        $id = $_POST["id"];
        $age = $_POST["age"];
        echo "Name: " . $name . "<br>";
        echo "Id: " . $id . "<br>";
        echo "Age: " . $age . "<br>";
    }
?>
 </h2>   
    <h1>Sign In Form</h1>
    <!-- <input type="textarea" accept="onlclick()"> -->
    
    <!-- <form action="Enter the name ">First Name 
    <input type="text" name="First Name" placeholder="Enter the First Name">
    <br>
    <form action="Enter the name ">Second Name 
    <input type="text" name="First Name" placeholder="Enter the First Name">
    <br>
    <form action="Enter the name ">Input Id 
    <input type="text" name="Second Name" placeholder="Enter the Second Name">
    <br>
    </form>  -->
    <form method="post">
        Enter Your Name:
        <input type="text" name="name" placeholder="Enter Your Name">
        Enter Your Id:
        <input type="text" name="id" placeholder="Enter Your Id">
        Enter Your Age:
        <input type="text" name="age" placeholder="Enter Your Age">
        <button type="submit">Submit</button>
    </form>

</body>
</html>