<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do app</title>

</head>
<body>
 <script>
    fetch('https://jsonplaceholder.typicode.com/todos/1')
    .then(response => response.json())
    .then(data => console.log(data));</script>


    <?php
    setcookie("user", "heet",time()+3600);
    echo "Hello World" . $_COOKIE["user"];
    
    ?>    
</body>
</html>
    
    