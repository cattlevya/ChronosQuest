<?php
// php/quest_data.php

// 1. Config & Headers
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// 2. Koneksi Database
if (!file_exists('db.php')) {
    echo json_encode(["success" => false, "message" => "Database connection file missing"]);
    exit;
}
require 'db.php';

// 3. Ambil Input (User ID)
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);
$user_id = $input['user_id'] ?? $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "Sesi login tidak valid (User ID missing)."]);
    exit;
}

$user_id = $conn->real_escape_string($user_id);

// 4. Query Data (Menggabungkan Quest dengan Progress User)
$sql = "
    SELECT 
        q.id, 
        q.title, 
        q.description, 
        q.target_count, 
        q.is_locked,
        q.reward_points,
        COALESCE(uq.current_progress, 0) as current_progress
    FROM quests q
    LEFT JOIN user_quests uq ON q.id = uq.quest_id AND uq.user_id = '$user_id'
    ORDER BY q.is_locked ASC, q.id ASC
";

$result = $conn->query($sql);
$quests = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Hitung persentase (opsional, untuk visual bar nanti)
        $target = (int)$row['target_count'];
        $curr = (int)$row['current_progress'];
        $row['percent'] = ($target > 0) ? round(($curr / $target) * 100) : 0;
        
        $quests[] = $row;
    }
    echo json_encode(["success" => true, "data" => ["quests" => $quests]]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal mengambil data quest."]);
}

$conn->close();
?>