<?php
// php/admin_calendar.php

// 1. Matikan error display (agar tidak merusak JSON)
ini_set('display_errors', 0);
error_reporting(0);

// 2. Buffer output
ob_start();

header('Content-Type: application/json');

// 3. Cek Koneksi DB
if (!file_exists('db.php')) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "File db.php tidak ditemukan."]);
    exit;
}
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    // --- 1. READ (GET) ---
    if ($method === 'GET') {
        // REVISI: Hapus "WHERE user_id IS NULL" karena kolom itu tidak ada
        $sql = "SELECT * FROM calendar_events ORDER BY event_date ASC";
        
        $q = $conn->query($sql);
        if (!$q) throw new Exception("Query Failed: " . $conn->error);

        $data = [];
        while($r = $q->fetch_assoc()) {
            $data[] = $r;
        }
        
        ob_clean();
        echo json_encode(["success" => true, "data" => $data]);
        exit;
    }

    // --- 2. CREATE / UPDATE / DELETE (POST) ---
    $inputRaw = file_get_contents("php://input");
    $input = json_decode($inputRaw);

    if (!$input) throw new Exception("Invalid JSON Input");

    // A. DELETE
    if (isset($input->action) && $input->action === 'delete') {
        $id = (int)$input->id;
        $conn->query("DELETE FROM calendar_events WHERE id = $id");
        
        ob_clean();
        echo json_encode(["success" => true, "message" => "Event dihapus."]);
        exit;
    }

    // B. SAVE (INSERT / UPDATE)
    if (isset($input->title)) {
        $title = $conn->real_escape_string($input->title);
        $date = $conn->real_escape_string($input->date);
        $type = $conn->real_escape_string($input->type);

        // LOGIKA UPDATE / INSERT
        if (isset($input->id) && !empty($input->id)) {
            // UPDATE
            $id = (int)$input->id;
            // Tidak ada perubahan di sini karena update hanya field yang ada
            $sql = "UPDATE calendar_events SET title='$title', event_date='$date', type='$type' WHERE id=$id";
            $msg = "Event berhasil diperbarui!";
        } else {
            // INSERT (REVISI: Hapus user_id dari query insert)
            $sql = "INSERT INTO calendar_events (title, event_date, type) VALUES ('$title', '$date', '$type')";
            $msg = "Event berhasil ditambahkan!";
        }
        
        if ($conn->query($sql)) {
            ob_clean();
            echo json_encode(["success" => true, "message" => $msg]);
        } else {
            throw new Exception("DB Error: " . $conn->error);
        }
        exit;
    }

} catch (Exception $e) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Server Error: " . $e->getMessage()]);
}

$conn->close();
?>