<?php
// php/submit_quiz.php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents("php://input"), true);
$user_id = $input['user_id'] ?? null;
$quest_id = $input['quest_id'] ?? null;
$score = $input['score'] ?? 0;

if (!$user_id || !$quest_id) {
    echo json_encode(["success" => false, "message" => "Invalid Data"]);
    exit;
}

// Logic Penilaian (Misal KKM = 60)
$passed = ($score >= 60);

if ($passed) {
    // 1. Cek apakah sudah pernah selesai sebelumnya?
    $check = $conn->query("SELECT * FROM user_quests WHERE user_id='$user_id' AND quest_id='$quest_id'");
    
    if ($check->num_rows == 0) {
        // Belum pernah -> Insert Record
        $conn->query("INSERT INTO user_quests (user_id, quest_id, current_progress, is_claimed) VALUES ('$user_id', '$quest_id', 100, 1)");
        
        // Tambah Poin User Sesuai Quest
        $qReward = $conn->query("SELECT reward_points FROM quests WHERE id='$quest_id'");
        $reward = ($qReward && $qReward->num_rows > 0) ? intval($qReward->fetch_assoc()['reward_points']) : 100;

        $conn->query("UPDATE users SET points = points + $reward WHERE id='$user_id'");

        // Hitung & Update Level Baru (Ex: Level Up tiap 500 poin)
        $qUser = $conn->query("SELECT points FROM users WHERE id='$user_id'");
        if ($qUser && $qUser->num_rows > 0) {
            $currentPoints = intval($qUser->fetch_assoc()['points']);
            $newLevel = 1 + floor($currentPoints / 500);
            $conn->query("UPDATE users SET level = '$newLevel' WHERE id='$user_id'");
        }
        
        // Unlock Quest Berikutnya (ID Quest + 1)
        $next_quest = $quest_id + 1;
        $conn->query("UPDATE quests SET is_locked = 0 WHERE id='$next_quest'"); // Note: Ini cara simpel global unlock. Idealnya pakai tabel user_unlocks. 
        // Tapi untuk skema database simpel user_quests ini, kita asumsikan is_locked di quests tabel global (demo purpose).
        // Untuk perbaikan: Logika "is_locked" harusnya per user. 
        // Solusi cepat: Kita anggap user membuka quest berikutnya dengan insert row progress 0 ke user_quests.
        
        $conn->query("INSERT INTO user_quests (user_id, quest_id, current_progress, is_claimed) VALUES ('$user_id', '$next_quest', 0, 0)");
    }
    
    echo json_encode(["success" => true, "message" => "Quest Selesai! Poin bertambah.", "passed" => true]);
} else {
    echo json_encode(["success" => true, "message" => "Nilai belum cukup. Coba lagi!", "passed" => false]);
}

$conn->close();
?>