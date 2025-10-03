<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    $entry = "Name: $name | Email: $email | Message: $message\n";
    file_put_contents("data.txt", $entry, FILE_APPEND);

    $csv = fopen("data.csv", "a");
    fputcsv($csv, [$name, $email, $message]);
    fclose($csv);

    $jsonFile = "data.json";
    $existingData = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
    $existingData[] = ["name" => $name, "email" => $email, "message" => $message];
    file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT));

    echo "<h2>Form Submitted Successfully!</h2>";
    echo "<p>Thank you, <b>$name</b>. Your data has been saved.</p>";
    echo "<a href='form.html'>Go Back</a>";
} else {
    echo "<h2>Error: Invalid Request</h2>";
    echo "<p>Please submit the form.</p>";
    echo "<p><a href='form.html'>Go Back</a></p>";
}
?>
