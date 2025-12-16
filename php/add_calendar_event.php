<?php
header('Content-Type: application/json');
require 'db.php';

// Ambil input JSON
$input = json_decode(file_get_contents("php://input"));

if (!isset($input->title) || !isset($input->event_date)) {
    echo json_encode(["success" => false, "message" => "Input tidak lengkap"]);
    exit;
}

$title = trim($conn->real_escape_string($input->title));
$event_date = $conn->real_escape_string($input->event_date);
$type = isset($input->type) ? $conn->real_escape_string($input->type) : 'custom';

if (empty($title)) {
    echo json_encode(["success" => false, "message" => "Judul event tidak boleh kosong"]);
    exit;
}

// Insert ke Database
$user_id = isset($input->user_id) ? $input->user_id : null;

// Insert ke Database
if ($user_id) {
    $stmt = $conn->prepare("INSERT INTO calendar_events (user_id, title, event_date, type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $event_date, $type);
} else {
    // Fallback for global events or legacy
    $stmt = $conn->prepare("INSERT INTO calendar_events (title, event_date, type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $event_date, $type);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Event berhasil ditambahkan", "id" => $conn->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan event: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>
