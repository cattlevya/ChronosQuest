<?php
header('Content-Type: application/json');
require 'db.php';

// Ambil input JSON
$input = json_decode(file_get_contents("php://input"));

if (!isset($input->todo_id)) {
    echo json_encode(["success" => false, "message" => "Todo ID tidak ditemukan"]);
    exit;
}

$todo_id = (int)$input->todo_id;

// Delete dari Database
$stmt = $conn->prepare("DELETE FROM user_todo WHERE id = ?");
$stmt->bind_param("i", $todo_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Tugas berhasil dihapus"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menghapus tugas"]);
}

$stmt->close();
$conn->close();
?>
