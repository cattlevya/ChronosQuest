<?php
// 1. NYALAKAN ERROR REPORTING (Supaya ketahuan salahnya dimana)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set header JSON
header('Content-Type: application/json');

// 2. CEK & LOAD DB
if (!file_exists('db.php')) {
    die(json_encode(["success" => false, "message" => "File db.php hilang!"]));
}
require 'db.php';

session_start();
date_default_timezone_set('Asia/Jakarta');

// 3. TANGKAP INPUT
$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON);

// HARDCODE USER ID JIKA INPUT KOSONG (Untuk Test Lewat Browser Langsung)
$user_id = 1; // Default ID 1 untuk testing
if (isset($input->user_id)) {
    $user_id = $conn->real_escape_string($input->user_id);
}

$response = [];

try {
    // A. STATS
    $q = $conn->query("SELECT class_type, level, points, avatar FROM users WHERE id = '$user_id'");
    if (!$q || $q->num_rows === 0) {
        throw new Exception("User tidak ditemukan");
    }

    $user = $q->fetch_assoc();

    // Class type bisa kosong - tetap lanjutkan load data lainnya
    $classType = !empty($user['class_type']) ? $conn->real_escape_string($user['class_type']) : 'None';

    // Hitung Total Quest Selesai
    $qQuest = $conn->query("SELECT COUNT(*) as total FROM user_quests WHERE user_id = '$user_id' AND is_claimed = 1");
    $totalQuest = 0;
    if ($qQuest) {
        $rQuest = $qQuest->fetch_assoc();
        $totalQuest = $rQuest['total'];
    }

    $response['stats'] = [
        'level' => $user['level'] ?? 1,
        'points' => $user['points'] ?? 0,
        'total_quests' => $totalQuest,
        'avatar' => $user['avatar'] ?? ''
    ];
    $response['class_type'] = $classType;
    $response['avatar'] = $user['avatar'] ?? '';

    // B. TO-DO
    $todos = [];
    $q = $conn->query("SELECT id, task_name, is_checked FROM user_todo WHERE user_id = '$user_id'");
    if ($q) { while ($r = $q->fetch_assoc()) $todos[] = $r; }
    $response['todos'] = $todos;

    // C. MATERI
    $materi = [];
    // TAMBAHKAN 'description' KE DALAM SELECT
    $q = $conn->query("SELECT id, title, category, description FROM materials ORDER BY id DESC LIMIT 3");
    if ($q) { 
        while ($r = $q->fetch_assoc()) {
            // Potong deskripsi biar ga kepanjangan (max 50 karakter)
            if (strlen($r['description']) > 50) {
                $r['description'] = substr($r['description'], 0, 50) . "...";
            }
            $materi[] = $r; 
        } 
    }
    $response['materials'] = $materi;

    // D. JADWAL (Filter by Class)
    $sched = [];
    $userClass = $classType; // Assuming class_type is 'A','B','C'
    
    // Safety check just in case legacy data exists
    if (strlen($userClass) > 1) {
       // If legacy (e.g. 'ACT', 'CMS'), maybe map or default. 
       // For now, allow flexible query or fallback.
       // But assuming migration worked, it should be 'A', 'B', 'C'.
    }

    $q = $conn->query("
        SELECT day_name, subject_name, time_start, time_end, room
        FROM schedules
        WHERE class_char = '$userClass' OR class_char IS NULL
        ORDER BY FIELD(day_name,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), time_start ASC
    ");

    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $r['time_start'] = substr($r['time_start'], 0, 5);
            $r['time_end'] = substr($r['time_end'], 0, 5);
            $sched[] = $r;
        }
    }
    $response['schedules'] = $sched;

    // E. CALENDAR
    $cal = [];
    $currentMonth = date('m');
    $currentYear = date('Y');
    // Pastikan tabel calendar_events ada!
    $q = $conn->query("SELECT title, event_date, type FROM calendar_events WHERE user_id = '$user_id' AND MONTH(event_date) = '$currentMonth' AND YEAR(event_date) = '$currentYear'");
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $d = (int)date('d', strtotime($r['event_date']));
            $cal[$d][] = ['title' => $r['title'], 'type' => $r['type']];
        }
    }
    $response['calendar'] = $cal;

    // F. UPCOMING
    $upc = [];
    $todayDate = date('Y-m-d');
    $q = $conn->query("SELECT title, event_date FROM calendar_events WHERE user_id = '$user_id' AND event_date >= '$todayDate' ORDER BY event_date ASC LIMIT 10");
    if ($q) {
        while ($r = $q->fetch_assoc()) {
            $r['date_fmt'] = date('d M', strtotime($r['event_date']));
            $upc[] = $r;
        }
    }
    $response['upcoming'] = $upc;

    // FINAL OUTPUT
    echo json_encode(["success" => true, "data" => $response]);

} catch (Exception $e) {
    // Tangkap error fatal dan kirim sebagai JSON
    echo json_encode(["success" => false, "message" => "FATAL ERROR: " . $e->getMessage()]);
}

$conn->close();
?>