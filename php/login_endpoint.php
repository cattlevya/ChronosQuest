<?php
// php/login_endpoint.php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php';

$data = json_decode(file_get_contents("php://input"));

// Login requires Email & Password
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Email dan Password wajib diisi"]);
    exit;
}

$email = $conn->real_escape_string($data->email);
$password = $data->password;

// Select berdasarkan Email
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verifikasi Password
    if (password_verify($password, $user['password'])) {
        // Return data session (sesuai kolom DB)
        echo json_encode([
            "success" => true, 
            "message" => "Login berhasil",
            "data" => [
                "id" => $user['id'],
                "nickname" => $user['nickname'], // Pakai nickname
                "email" => $user['email'],
                "role" => $user['role'],
                "class_type" => $user['class_type']
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Password salah"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Email tidak ditemukan"]);
}

$conn->close();
?>