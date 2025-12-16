<?php
header('Content-Type: application/json');
require 'db.php';

// Test 1: Cek koneksi
echo "=== TEST DATABASE ===\n\n";

// Test 2: Cek struktur tabel users
$result = $conn->query("DESCRIBE users");
if ($result) {
    echo "Struktur tabel 'users':\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "ERROR: Tabel 'users' tidak ditemukan!\n";
}

echo "\n";

// Test 3: Cek data di tabel users
$result2 = $conn->query("SELECT id, nickname, email, role FROM users LIMIT 5");
if ($result2) {
    echo "Data di tabel 'users':\n";
    while ($row = $result2->fetch_assoc()) {
        echo "- ID: " . $row['id'] . ", Nickname: " . $row['nickname'] . ", Email: " . $row['email'] . ", Role: " . $row['role'] . "\n";
    }
    if ($result2->num_rows == 0) {
        echo "- (kosong)\n";
    }
} else {
    echo "ERROR query: " . $conn->error . "\n";
}

$conn->close();
?>
