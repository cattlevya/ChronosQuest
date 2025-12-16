<?php
// php/admin_quiz.php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// --- 1. READ (GET) ---
if ($method === 'GET') {
    $quest_id = $_GET['quest_id'] ?? null;
    
    if (!$quest_id) {
        echo json_encode(["success" => false, "message" => "Quest ID Missing"]);
        exit;
    }

    $quest_id = $conn->real_escape_string($quest_id);
    
    // Ambil Judul Quest & Soal-soalnya
    $quest = $conn->query("SELECT title FROM quests WHERE id = '$quest_id'")->fetch_assoc();
    $q_res = $conn->query("SELECT * FROM quiz_questions WHERE quest_id = '$quest_id'");
    
    $questions = [];
    while($r = $q_res->fetch_assoc()) {
        $questions[] = $r;
    }

    echo json_encode([
        "success" => true, 
        "quest_title" => $quest['title'],
        "data" => $questions
    ]);
    exit;
}

// --- 2. CREATE / UPDATE / DELETE (POST) ---
$input = json_decode(file_get_contents("php://input"));

// A. DELETE
if (isset($input->action) && $input->action === 'delete') {
    $id = (int)$input->id;
    if ($conn->query("DELETE FROM quiz_questions WHERE id = $id")) {
        echo json_encode(["success" => true, "message" => "Soal dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal hapus."]);
    }
    exit;
}

// B. CREATE & UPDATE
if (isset($input->question_text)) {
    $quest_id = (int)$input->quest_id;
    $q_text   = $conn->real_escape_string($input->question_text);
    $opt_a    = $conn->real_escape_string($input->option_a);
    $opt_b    = $conn->real_escape_string($input->option_b);
    $opt_c    = $conn->real_escape_string($input->option_c);
    $opt_d    = $conn->real_escape_string($input->option_d);
    $correct  = $conn->real_escape_string($input->correct_option);

    if (isset($input->id) && !empty($input->id)) {
        // UPDATE
        $id = (int)$input->id;
        $sql = "UPDATE quiz_questions SET question_text='$q_text', option_a='$opt_a', option_b='$opt_b', option_c='$opt_c', option_d='$opt_d', correct_option='$correct' WHERE id=$id";
        $msg = "Soal diperbarui!";
    } else {
        // INSERT
        $sql = "INSERT INTO quiz_questions (quest_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES ($quest_id, '$q_text', '$opt_a', '$opt_b', '$opt_c', '$opt_d', '$correct')";
        $msg = "Soal ditambahkan!";
    }

    if ($conn->query($sql)) {
        // Update jumlah target soal di tabel quests (opsional, biar sinkron)
        $conn->query("UPDATE quests SET target_count = (SELECT COUNT(*) FROM quiz_questions WHERE quest_id=$quest_id) WHERE id=$quest_id");
        
        echo json_encode(["success" => true, "message" => $msg]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
    exit;
}
?>