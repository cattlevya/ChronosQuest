<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit;
}

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require 'db.php';

$raw = file_get_contents("php://input");

file_put_contents(
    __DIR__ . '/debug_log.txt',
    "RAW: $raw\n",
    FILE_APPEND
);

$data = json_decode($raw, true);

if (!$data || !isset($data['user_id'], $data['class_type'])) {
    echo json_encode(["success"=>false,"msg"=>"EMPTY BODY","raw"=>$raw]);
    exit;
}

$user_id = $conn->real_escape_string($data['user_id']);
$class_type = $conn->real_escape_string($data['class_type']);

$conn->query("UPDATE users SET class_type='$class_type' WHERE id='$user_id'");

echo json_encode(["success"=>true]);
