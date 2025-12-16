<?php
// php/register_endpoint.php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php';

$data = json_decode(file_get_contents("php://input"));

// 1. Validasi Input (Sesuai DB: nickname, email, password)
if (!isset($data->nickname) || !isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap (Nickname/Email/Password)"]);
    exit;
}

$nickname = $conn->real_escape_string($data->nickname);
$email = $conn->real_escape_string($data->email);
$passwordRaw = $data->password;
$passwordHash = password_hash($passwordRaw, PASSWORD_DEFAULT);

// 2. Cek Duplikasi Email
$checkSql = "SELECT id FROM users WHERE email = '$email'";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email sudah terdaftar!"]);
    exit;
}

// 3. Insert Data (Default: role='student', class_type='None')
$sql = "INSERT INTO users (nickname, email, password, role, class_type, level, points) 
        VALUES ('$nickname', '$email', '$passwordHash', 'student', 'None', 1, 0)";

if ($conn->query($sql) === TRUE) {
    $new_user_id = $conn->insert_id;
    echo json_encode([
        "success" => true, 
        "message" => "Registrasi berhasil!",
        "user_id" => $new_user_id
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Database Error: " . $conn->error]);
}

$conn->close();
?>