<?php
require 'db.php';

try {
    // 1. Add user_id column if it doesn't exist
    $sql = "SHOW COLUMNS FROM calendar_events LIKE 'user_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 0) {
        // Add user_id column
        $alter = "ALTER TABLE calendar_events ADD COLUMN user_id INT(11) DEFAULT NULL AFTER id";
        if ($conn->query($alter) === TRUE) {
            echo "Column user_id added successfully.<br>";
            
            // Assign existing events to Admin (ID 1) or NULL
            $conn->query("UPDATE calendar_events SET user_id = 1 WHERE user_id IS NULL");
            
            // Add Foreign Key
            $fk = "ALTER TABLE calendar_events ADD CONSTRAINT calendar_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE";
            if ($conn->query($fk) === TRUE) {
                echo "Foreign Key added successfully.<br>";
            } else {
                echo "Error adding FK: " . $conn->error . "<br>";
            }
        } else {
            echo "Error adding column: " . $conn->error . "<br>";
        }
    } else {
        echo "Column user_id already exists.<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
