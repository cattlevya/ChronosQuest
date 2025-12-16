<?php
require 'db.php';

// Check if column exists first
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'avatar'");
if ($check->num_rows == 0) {
    $sql = "ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL";
    if ($conn->query($sql) === TRUE) {
        echo "Column 'avatar' added successfully.";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column 'avatar' already exists.";
}
$conn->close();
?>
