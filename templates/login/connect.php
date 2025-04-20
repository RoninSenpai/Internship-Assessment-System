<?php
$host = 'localhost';
$user = 'root';
$password = 'pretty_girl';
$database = 'rias_db';

// Create connection with error handling
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Manila');
?>