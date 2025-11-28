<?php
$host = 'localhost';
$db   = 'steampunk_lms';
$user = 'root'; // Default XAMPP user
$pass = '';     // Default XAMPP password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}
?>
