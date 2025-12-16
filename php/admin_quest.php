<?php
// php/admin_quest.php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// --- 1. READ (GET) ---
if ($method === 'GET') {
    $q = $conn->query("SELECT * FROM quests ORDER BY id ASC");
    $data = [];
    while($r = $q->fetch_assoc()) {
        $data[] = $r;
    }
    echo json_encode(["success" => true, "data" => $data]);
    exit;
}

// --- 2. CREATE / UPDATE / DELETE (POST) ---
$input = json_decode(file_get_contents("php://input"));

// A. DELETE
if (isset($input->action) && $input->action === 'delete') {
    $id = (int)$input->id;
    $conn->query("DELETE FROM quests WHERE id = $id");
    echo json_encode(["success" => true, "message" => "Quest dihapus."]);
    exit;
}

// B. CREATE & UPDATE
if (isset($input->title)) {
    $title  = $conn->real_escape_string($input->title);
    $desc   = $conn->real_escape_string($input->description);
    $type   = $conn->real_escape_string($input->type);
    $target = (int)$input->target_count;
    $reward = (int)$input->reward_points;
    $locked = (int)$input->is_locked; // 1 or 0

    // Cek apakah Update atau Insert
    if (isset($input->id) && !empty($input->id)) {
        // UPDATE
        $id = (int)$input->id;
        $sql = "UPDATE quests SET title='$title', description='$desc', type='$type', target_count=$target, reward_points=$reward, is_locked=$locked WHERE id=$id";
        $msg = "Quest berhasil diperbarui!";
    } else {
        // INSERT
        $sql = "INSERT INTO quests (title, description, type, target_count, reward_points, is_locked) VALUES ('$title', '$desc', '$type', $target, $reward, $locked)";
        $msg = "Quest baru berhasil dibuat!";
    }

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => $msg]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
    exit;
}
?>