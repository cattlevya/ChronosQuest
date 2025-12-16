<?php
// php/admin_users.php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// --- 1. READ (GET) ---
if ($method === 'GET') {
    // Ambil hanya user dengan role 'student'
    $q = $conn->query("SELECT id, fullname, username, class_type, level, points, created_at FROM users WHERE role='student' ORDER BY created_at DESC");
    $data = [];
    while($r = $q->fetch_assoc()) {
        $data[] = $r;
    }
    echo json_encode(["success" => true, "data" => $data]);
    exit;
}

// --- 2. UPDATE / DELETE (POST) ---
$input = json_decode(file_get_contents("php://input"));

// A. DELETE USER
if (isset($input->action) && $input->action === 'delete') {
    $id = (int)$input->id;
    $conn->query("DELETE FROM users WHERE id = $id");
    echo json_encode(["success" => true, "message" => "Siswa dihapus."]);
    exit;
}

// B. UPDATE USER (Edit Kelas / Reset Password)
if (isset($input->id) && isset($input->class_type)) {
    $id = (int)$input->id;
    $class = $conn->real_escape_string($input->class_type);
    
    // Cek apakah mau reset password juga?
    if (!empty($input->new_password)) {
        $pass = password_hash($input->new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET class_type='$class', password='$pass' WHERE id=$id";
        $msg = "Data & Password siswa berhasil diupdate!";
    } else {
        $sql = "UPDATE users SET class_type='$class' WHERE id=$id";
        $msg = "Kelas siswa berhasil diupdate!";
    }

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => $msg]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
    exit;
}
?>