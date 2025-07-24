<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My First PHP App</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #9bc3ffff;
            margin: 0;
        }
        h1 {
            color: #333;
            border: 2px solid #ddd;
            padding: 20px 40px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.98);
        }
    </style>
</head>
<body>
    <h1>
        <?php
            // This is a PHP comment. It's ignored by the processor.
            // The 'echo' command outputs text to the browser.
            echo "Hello, World!";
            echo "<br>";
            echo "Welcome to my first PHP application!";
            echo "<br>";
            echo "This is a simple PHP script.";
        ?>
    </h1>
</body>
</html>
