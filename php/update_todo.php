<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents("php://input"));

if (!isset($input->todo_id) || !isset($input->is_checked)) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$todo_id = (int)$input->todo_id;
$is_checked = (int)$input->is_checked; 

$stmt = $conn->prepare("UPDATE user_todo SET is_checked = ? WHERE id = ?");
$stmt->bind_param("ii", $is_checked, $todo_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Status updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update"]);
}

$stmt->close();
$conn->close();
?>