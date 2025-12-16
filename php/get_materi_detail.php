<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents("php://input"));

if (!isset($input->id)) {
    echo json_encode(["success" => false, "message" => "ID materi tidak ditemukan"]);
    exit;
}

$id = (int)$input->id;

$stmt = $conn->prepare("SELECT id, title, category, description, content FROM materials WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $materi = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $materi]);
} else {
    echo json_encode(["success" => false, "message" => "Materi tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
