<?php
$conn = new mysqli('db', 'fos_user', 'fos_password', 'fos_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
