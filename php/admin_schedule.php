<?php
// php/admin_schedule.php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// 1. READ (GET)
if ($method === 'GET') {
    $sql = "SELECT * FROM schedules ORDER BY FIELD(day_name, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), time_start";
    $q = $conn->query($sql);
    $data = [];
    while($r = $q->fetch_assoc()) {
        $r['time_start'] = substr($r['time_start'], 0, 5);
        $r['time_end'] = substr($r['time_end'], 0, 5);
        $data[] = $r;
    }
    echo json_encode(["success" => true, "data" => $data]);
    exit;
}

// 2. CREATE / UPDATE / DELETE (POST)
$input = json_decode(file_get_contents("php://input"));

// DELETE
if (isset($input->action) && $input->action === 'delete') {
    $id = (int)$input->id;
    $conn->query("DELETE FROM schedules WHERE id = $id");
    echo json_encode(["success" => true, "message" => "Jadwal dihapus."]);
    exit;
}

// SAVE (INSERT / UPDATE)
if (isset($input->subject)) {
    $day = $conn->real_escape_string($input->day);
    $subject = $conn->real_escape_string($input->subject);
    $start = $conn->real_escape_string($input->start);
    $end = $conn->real_escape_string($input->end);
    $room = $conn->real_escape_string($input->room);

    // LOGIKA EDIT: Jika ID dikirim, berarti UPDATE
    if (isset($input->id) && !empty($input->id)) {
        $id = (int)$input->id;
        $sql = "UPDATE schedules SET day_name='$day', subject_name='$subject', time_start='$start', time_end='$end', room='$room' WHERE id=$id";
        $msg = "Jadwal berhasil diperbarui!";
    } else {
        // LOGIKA TAMBAH BARU
        $sql = "INSERT INTO schedules (day_name, subject_name, time_start, time_end, room) VALUES ('$day', '$subject', '$start', '$end', '$room')";
        $msg = "Jadwal berhasil ditambahkan!";
    }
    
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => $msg]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
    exit;
}
?>