<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic File </title>
</head>
<body>
    <h1>Welcome to the Server Side Scripting</h1>
</body>
</html>
 <?php
    $str = "Hello, World!";
    $str1 = "Hello, PHP!"."<b></b><i></i>";
    echo $str ."<br>"."<b><i>".$str1."</i></b>"."<br>";


    function add($a, $b)
    {
     return $a+$b;
    }
  
    function multiply($a, $b)
    {
     return $a*$b;
    }
    function divide($a, $b)
    {
     return $a/$b;
    }
    $d = 1;
    function subtract($a, $b)
    {
        global $d;
       $c = $d - $a;    
     return $c;
    }

    echo "Addition of 2 and 3 is :".add(2,3)."<br>";
    echo "Multiplication of 2 and 3 is :".multiply(2,3)."<br>";
    echo "Subtraction of 2 and 10 is :".subtract(2,10)."<br>";
    echo "Division of 5 and 10 is :".divide(15,10)."<br>";   

    $a = 1;
?>