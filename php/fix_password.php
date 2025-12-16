<?php
require 'db.php';

// 1. Password yang diinginkan
$password_baru = "admin123";

// 2. Enkripsi password (Hashing)
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

// 3. Update database untuk user 'admin'
$sql = "UPDATE users SET password = '$password_hash', role = 'admin' WHERE username = 'admin'";

if ($conn->query($sql) === TRUE) {
    echo "<h1>BERHASIL!</h1>";
    echo "Password untuk user <b>'admin'</b> sudah di-reset menjadi: <b>$password_baru</b><br>";
    echo "Status Role juga sudah dipastikan menjadi: <b>admin</b><br><br>";
    echo "<a href='../login.html'>Klik disini untuk Login</a>";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>