<?php
header('Content-Type: application/json');
require 'db.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

$username = $conn->real_escape_string($data->username);
$password = $data->password;

// Query user
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // In a real app, use password_verify($password, $user['password'])
    // For this demo with plain text/simple passwords:
    if ($password === $user['password']) {
        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$conn->close();
?>
