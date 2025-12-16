<?php
// php/quiz_data.php
header('Content-Type: application/json');
require 'db.php';

$quest_id = $_GET['id'] ?? null;

if (!$quest_id) {
    echo json_encode(["success" => false, "message" => "Quest ID missing"]);
    exit;
}

$quest_id = $conn->real_escape_string($quest_id);

// Ambil info Quest
$questSql = "SELECT title FROM quests WHERE id = '$quest_id'";
$questRes = $conn->query($questSql);
$questData = $questRes->fetch_assoc();

if (!$questData) {
    echo json_encode(["success" => false, "message" => "Quest not found"]);
    exit;
}

// Ambil Soal (Limit 5)
$sql = "SELECT id, question_text, option_a, option_b, option_c, option_d, correct_option FROM quiz_questions WHERE quest_id = '$quest_id' LIMIT 5";
$result = $conn->query($sql);

$questions = [];
while ($row = $result->fetch_assoc()) {
    // Kita sembunyikan jawaban benar dari frontend agar tidak mudah diintip (validasi nanti di server atau simple logic JS)
    // Untuk demo simple ini, kita kirim correct_option ke JS tapi nanti bisa dienkripsi/disembunyikan logicnya.
    $questions[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => [
        "title" => $questData['title'],
        "questions" => $questions
    ]
]);

$conn->close();
?>