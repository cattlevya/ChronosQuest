<?php
header('Content-Type: application/json');
require 'db.php';

// Ambil input JSON
$input = json_decode(file_get_contents("php://input"));

if (!isset($input->user_id) || !isset($input->task_name)) {
    echo json_encode(["success" => false, "message" => "Input tidak lengkap"]);
    exit;
}

$user_id = (int)$input->user_id;
$task_name = trim($conn->real_escape_string($input->task_name));

if (empty($task_name)) {
    echo json_encode(["success" => false, "message" => "Nama tugas tidak boleh kosong"]);
    exit;
}

// Insert ke Database
$stmt = $conn->prepare("INSERT INTO user_todo (user_id, task_name, is_checked) VALUES (?, ?, 0)");
$stmt->bind_param("is", $user_id, $task_name);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Tugas berhasil ditambahkan"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan tugas"]);
}

$stmt->close();
$conn->close();
?>