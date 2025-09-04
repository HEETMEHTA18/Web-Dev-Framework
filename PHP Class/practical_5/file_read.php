<?php

$filename = "data.txt";
if(file_exists($filename))
{
    $file = fopen($filename, "w");
    fwrite($file, "File is created");
    fclose($file);

    // Reopen for reading
    $file = fopen($filename, "r");
    while(!feof($file))
    {
        $line = fgets($file);
        echo $line . "<br>";
    }
    fclose($file);
}
else
{
    echo ("File not found.");
}