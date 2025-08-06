<?php
$correct_number = 66; 

?>
<!DOCTYPE html>
<html>
<head>
    <title> f6789b89</title>
</head>
<body>
    <h1>Number Guessing Game</h1>
    
    <?php
    // Check if guess parameter exists
    if (!isset($_GET['guess'])) {
        echo "<p>Missing guess parameter</p>";
    }
    // Check if guess parameter is empty
    elseif ($_GET['guess'] === '') {
        echo "<p>Your guess is too short</p>";
    }
    // Check if guess is numeric
    elseif (!is_numeric($_GET['guess'])) {
        echo "<p>Your guess is not a number</p>";
    }
    else {
        // Convert to integer for comparison
        $guess = (int)$_GET['guess'];
        
        // Compare guess with correct number
        if ($guess < $correct_number) {
            echo "<p>Your guess is too low</p>";
        }
        elseif ($guess > $correct_number) {
            echo "<p>Your guess is too high</p>";
        }
        else {
            echo "<p>Congratulations - You are right</p>";
        }
    }
    ?>
    
    <p>Try guessing a number by adding ?guess=NUMBER to the URL</p>
    <p>For example: guess.php?guess=25</p>
</body>
</html>
