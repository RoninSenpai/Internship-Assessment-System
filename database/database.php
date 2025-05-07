<?php    
    $ipaddress = '192.168.0.2';
    $host = "localhost";
    $user = "root";
    $password = ""; // DEAD INSIDE
    $database = "RIAS_db"; // Or your real DB name, dumdum
    $port = 3306;

    // APC
    // $host = "airhub-soe.apc.edu.ph";
    // $user = 'mjkurumphang';
    // $password = 'SOETiny1!';

    $mysqli = new mysqli($host, $user, $password, $database);

    // Connection check, because life is full of betrayals
    if ($mysqli->connect_error) {
        die("🔥 DATABASE CONNECTION FAILED, CRY HARDER: " . $mysqli->connect_error);
    }

    // Optional: uncomment if you need a little pat on the back
    // echo "😈 Connection to database successful, nyahahaha~!";

    // Set character set to utf8mb4
    $mysqli->set_charset("utf8mb4");

    // Set timezone
    date_default_timezone_set('Asia/Manila');
?>
