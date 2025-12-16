<?php
// php/materi_data.php

// 1. SETTINGS & ERROR HANDLING
ini_set('display_errors', 0);
error_reporting(0);
ob_start(); // Buffer output

header('Content-Type: application/json');

// 2. CHECK DB CONNECTION
if (!file_exists('db.php')) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "File db.php missing"]);
    exit;
}
require 'db.php';

$response = [];

// 3. FETCH MATERIALS
// Fetch all materials, ordered by ID (or you can add an 'order_index' column later)
$sql = "SELECT id, title, description, category, content_url FROM materials ORDER BY id ASC";
$result = $conn->query($sql);

$materials = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
}

$response['materials'] = $materials;

// 4. SEND RESPONSE
ob_clean();
echo json_encode(["success" => true, "data" => $response]);

$conn->close();
?>