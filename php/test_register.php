<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

echo "=== TEST REGISTER ===\n\n";

// Simulasi data register
$nickname = "TestUser";
$email = "testuser@example.com";
$passwordHash = password_hash("test123", PASSWORD_DEFAULT);

// Cek apakah email sudah ada
$checkSql = "SELECT id FROM users WHERE email = '$email'";
$checkResult = $conn->query($checkSql);

if ($checkResult === false) {
    echo "ERROR pada query cek email: " . $conn->error . "\n";
    exit;
}

if ($checkResult->num_rows > 0) {
    echo "Email sudah terdaftar, skip insert.\n";
} else {
    // Coba insert
    $sql = "INSERT INTO users (nickname, email, password, role, class_type, level, points) 
            VALUES ('$nickname', '$email', '$passwordHash', 'student', 'None', 1, 0)";
    
    echo "SQL Query: $sql\n\n";
    
    if ($conn->query($sql) === TRUE) {
        echo "SUCCESS! User berhasil ditambahkan dengan ID: " . $conn->insert_id . "\n";
    } else {
        echo "ERROR INSERT: " . $conn->error . "\n";
    }
}

// Tampilkan semua users
echo "\n=== DAFTAR USERS ===\n";
$result = $conn->query("SELECT id, nickname, email, role FROM users");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Nickname: {$row['nickname']}, Email: {$row['email']}, Role: {$row['role']}\n";
    }
}

$conn->close();
?>
