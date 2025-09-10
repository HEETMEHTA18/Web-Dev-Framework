<?php
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Update these with your DB credentials
$host = "localhost";
$user = "root";
$pass = "";
$db = "assets_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$name = $conn->real_escape_string($data["name"]);
$type = $conn->real_escape_string($data["type"]);
$invest = floatval($data["invest"]);
$current = floatval($data["current"]);
$location = $conn->real_escape_string($data["location"]);
$date = $conn->real_escape_string($data["date"]);

$sql = "INSERT INTO assets (name, type, invest, current, location, date) VALUES ('$name', '$type', $invest, $current, '$location', '$date')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => $conn->error]);
}
$conn->close();
?>