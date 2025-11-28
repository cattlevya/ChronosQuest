<?php
header('Content-Type: application/json');
require 'db.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$username = $conn->real_escape_string($data->username);
$email = $conn->real_escape_string($data->email);
$password = $data->password; // In a real app, hash this!

// Check if username or email already exists
$checkSql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username or Email already exists"]);
    exit;
}

// Insert new user
$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Registration successful! Please sign in."]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
