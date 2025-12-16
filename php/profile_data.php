<?php
// php/profile_data.php

ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

if (!file_exists('db.php')) {
    echo json_encode(["success" => false, "message" => "DB config missing"]);
    exit;
}
require 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "User ID Required"]);
    exit;
}

$user_id = $conn->real_escape_string($user_id);

// Query data user + hitung total quest yang sudah selesai (claimed)
$sql = "
    SELECT 
        u.nickname, 
        u.email,
        u.class_type, 
        u.level, 
        u.points,
        u.avatar,
        (SELECT COUNT(*) FROM user_quests WHERE user_id = u.id AND is_claimed = 1) as total_quests
    FROM users u
    WHERE u.id = '$user_id'
";

try {
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $data]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found (ID: $user_id)"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Query Exception: " . $e->getMessage()]);
}

$conn->close();
?>