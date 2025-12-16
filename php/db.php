<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'chronosquest';

// Nyalakan laporan error untuk koneksi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Jika gagal, matikan script dan kirim JSON error
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "message" => "DB Connect Error: " . $e->getMessage()]));
}
