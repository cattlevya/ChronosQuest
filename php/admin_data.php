<?php
// php/admin_data.php
ini_set('display_errors', 0);
error_reporting(0);
ob_start();

header('Content-Type: application/json');
require 'db.php';

// 1. Hitung Total Siswa
$studentCount = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")->fetch_assoc()['total'];

// 2. Hitung Total Materi
$materiCount = $conn->query("SELECT COUNT(*) as total FROM materials")->fetch_assoc()['total'];

// 3. Hitung Total Quest Aktif
$questCount = $conn->query("SELECT COUNT(*) as total FROM quests")->fetch_assoc()['total'];

// 4. Ambil 5 User Terbaru
$recentUsers = [];
$qUser = $conn->query("SELECT fullname, class_type, created_at FROM users WHERE role='student' ORDER BY id DESC LIMIT 5");
if($qUser) {
    while($r = $qUser->fetch_assoc()) $recentUsers[] = $r;
}

$response = [
    'counts' => [
        'students' => $studentCount,
        'materials' => $materiCount,
        'quests' => $questCount
    ],
    'recent_users' => $recentUsers
];

ob_clean();
echo json_encode(["success" => true, "data" => $response]);
$conn->close();
?>