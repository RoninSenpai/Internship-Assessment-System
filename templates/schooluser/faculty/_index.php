<?php
    session_start();
    include '../../../database/database.php';

    // Step 2: Use the token from session instead of URL
    $token = $_SESSION['user_id'] ?? '';

    if (!$token) {
        http_response_code(404);
        exit;
    }

    // echo "Token received: $token<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAMS Internship Assessment System - INTERNSHIP OFFICER</title>
    <link rel="stylesheet" href="../../../static/css/components.css">
    <link rel="icon" type="image/png" href="../../../static/images/components\rias_icon.png">
</head>
<body class="body">

    <!-- Header -->
    <iframe src="../../../templates/_components/header.html" class="header" id="headerFrame"></iframe>
    
    <div class="container">
        <!-- Sidebar -->
        <iframe src="../../../templates/_components/sidebar.php" class="sidebar" id="sidebarFrame"></iframe>

        <script>
            const userRole = "faculty";

            document.getElementById("sidebarFrame").addEventListener("load", function() {
                this.contentWindow.postMessage({ role: userRole }, "*");
            });

            const userRole2 = "INTERNSHIP OFFICER";

            document.getElementById("headerFrame").addEventListener("load", function() {
                this.contentWindow.postMessage({ role: userRole2 }, "*");
            });
        </script>

        <!-- Main Content -->
        <iframe id="content" src="../../../templates/schooluser/faculty/home.php" class="content"></iframe>

        <script>
            document.getElementById("content").addEventListener("load", function () {
            const iframe = this;

            try {
                // This only works if SAME ORIGIN!!! No cross-domain peepin!
                // console.log("hello");
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                if (iframeDoc.body.innerText.includes("404")) {
                    // console.log("Iframe is deader than your social life 😹");
                    window.location.href = "/404_pagenotfound";
                // Maybe show an error in the parent page, nyahaha~
                }
            } catch (err) {
                console.warn("Can't access iframe contents, baka. Cross-origin maybe?");
            }
            });
        </script>
    </div>

    <!-- Footer -->
    <iframe src="../../../templates/_components/footer.html" class="footer"></iframe>
</body>
</html>
