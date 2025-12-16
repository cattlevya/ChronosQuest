<?php
// php/admin_materi.php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// --- 1. READ (GET) ---
if ($method === 'GET') {
    $q = $conn->query("SELECT * FROM materials ORDER BY id ASC");
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
    $conn->query("DELETE FROM materials WHERE id = $id");
    echo json_encode(["success" => true, "message" => "Materi dihapus."]);
    exit;
}

// B. CREATE & UPDATE
if (isset($input->title)) {
    $title = $conn->real_escape_string($input->title);
    $category = $conn->real_escape_string($input->category);
    $desc = $conn->real_escape_string($input->description);
    $url = $conn->real_escape_string($input->content_url);
    
    // Cek apakah ini Update (ada ID) atau Insert (tidak ada ID)
    if (isset($input->id) && !empty($input->id)) {
        // --- LOGIKA UPDATE ---
        $id = (int)$input->id;
        $sql = "UPDATE materials SET title='$title', category='$category', description='$desc', content_url='$url' WHERE id=$id";
        $msg = "Materi berhasil diperbarui!";
    } else {
        // --- LOGIKA INSERT ---
        $sql = "INSERT INTO materials (title, category, description, content_url) VALUES ('$title', '$category', '$desc', '$url')";
        $msg = "Materi baru berhasil ditambahkan!";
    }
    
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => $msg]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
    exit;
}
?>