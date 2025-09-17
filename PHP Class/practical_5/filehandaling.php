<?php
$path = __DIR__ . '/example.txt';
$content = file_get_contents($path);
if($content) {
echo($content);
}
$content1 = file_put_contents($path, "New content");
if($content1) {
    echo $content1;
    echo"<br>";
    echo "File updated successfully.";
}

$filename = "newfile.txt";
$content = "This is some content for the new file.";

// Open the file in write mode ('w') - creates the file if it doesn't exist, overwrites if it does
$file_handle = fopen($filename, "w");

if ($file_handle) {
    fwrite($file_handle, $content); // Write content to the file
    fclose($file_handle); // Close the file
    echo "File '$filename' created and content written successfully.";
} else {
    echo "Error creating or opening file '$filename'.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


    <form action="upload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>
    
</body>
</html>