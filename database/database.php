<?php
$host = "localhost";
$user = "root";
$password = ""; // DEAD INSIDE
$database = "rias_db"; // Or your real DB name, dumdum

$mysqli = new mysqli($host, $user, $password, $database);

// Connection check, because life is full of betrayals
if ($mysqli->connect_error) {
    die("🔥 DATABASE CONNECTION FAILED, CRY HARDER: " . $conn->connect_error);
}

// Optional: uncomment if you need a little pat on the back
// echo "😈 Connection to database successful, nyahahaha~!";
?>
