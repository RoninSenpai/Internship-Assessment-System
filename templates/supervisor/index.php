<?php
    include '../../database/database.php';
    $token = $_GET['token'] ?? '';

    if (!$token) {
        die("💢 No token provided! Access denied.");
    }

    // Debugging log: check if token is being passed correctly
    // echo "Token received: $token<br>";

    $stmt = $mysqli->prepare("
        SELECT sa.supervisor_id, i.internship_date_ended
        FROM send_assessments sa
        JOIN internships i ON sa.supervisor_id = i.supervisor_id
        WHERE sa.sendass_token = ? AND i.internship_date_ended > NOW()
    ");

    $stmt->bind_param("s", $token);
    $stmt->execute();

    $result = $stmt->get_result();

    $supervisorId = null; // Initialize supervisorId to null
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $supervisorId = $userData['supervisor_id']; // Now the supervisorId is set!
        // echo "🔓 Access granted! Supervisor #{$userData['supervisor_id']} is good to go!";
    } else {
        // Log the error
        echo "No valid token found or expired token. Try again!";
    }

    $stmt->close();
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAMS Internship Assessment System - Industry Supervisor</title>
    <link rel="stylesheet" href="../../static/css/components.css">
</head>
<body class="body">

    <!-- Header -->
    <iframe src="..\../templates\_components\header.html" class="header" id="headerFrame"></iframe>

    <div class="container">
        <!-- Sidebar -->
        <iframe src="../../templates/_components/sidebar.php?token=<?php echo urlencode($token); ?>" class="sidebar" id="sidebarFrame"></iframe>

        <!-- Main Content -->
        <iframe id="content" src="../../templates/supervisor/home.php?token=<?php echo urlencode($token); ?>" class="content"></iframe>
        
        <script>
            const userRole = "supervisor"; // Replace with actual role-checking logic

            document.getElementById("sidebarFrame").addEventListener("load", function() {
                this.contentWindow.postMessage({ role: userRole }, "*");
            });

            const userRole2 = "INDUSTRY SUPERVISOR";

            document.getElementById("headerFrame").addEventListener("load", function() {
                this.contentWindow.postMessage({ role: userRole2 }, "*");
            });
        </script>
    </div>

    <!-- Footer -->
    <iframe src="../../templates/_components/footer.html" class="footer"></iframe>
</body>
</html>