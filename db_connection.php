<?php
// db_connection.php

$servername = "localhost"; // អាចជា IP address របស់ database server
$username = "root"; // ឈ្មោះ user សម្រាប់ database របស់អ្នក។
$password = ""; // password សម្រាប់ database របស់អ្នក។
$dbname = "fingerprint_db"; // ឈ្មោះ database ដែលអ្នកបានបង្កើត

// បង្កើតការភ្ជាប់
$conn = new mysqli($servername, $username, $password, $dbname);

// ពិនិត្យការភ្ជាប់
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
