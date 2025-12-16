<?php
header('Content-Type: application/json');
require 'db.php';

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    
    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "User ID required"]);
        exit;
    }

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directory for upload
            $uploadFileDir = '../assets/avatars/';
            
            // Create directory if not exists
            if (!file_exists($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            // Generate unique name
            $newFileName = 'avatar_' . $user_id . '_' . time() . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;
            
            // Relative path for database
            $db_path = '../assets/avatars/' . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                
                // Update Database
                $sql = "UPDATE users SET avatar = '$db_path' WHERE id = '$user_id'";
                if ($conn->query($sql)) {
                    $response['success'] = true;
                    $response['message'] = "Upload berhasil!";
                    $response['avatar_url'] = $db_path;
                } else {
                    $response['message'] = "Gagal update database: " . $conn->error;
                }
            } else {
                $response['message'] = "Terjadi error saat memindahkan file.";
            }
        } else {
            $response['message'] = "Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.";
        }
    } else {
        $response['message'] = "Tidak ada file yang diupload atau terjadi error.";
    }
} else {
    $response['message'] = "Invalid Request Method";
}

echo json_encode($response);
$conn->close();
?>
