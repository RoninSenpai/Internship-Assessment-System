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
    <title>RAMS Internship Assessment System - ADMIN</title>
    <link rel="stylesheet" href="../../../static/css/components.css">
</head>
<body class="body">

    <!-- Header -->
    <iframe src="../../_components/header.html" class="header" id="headerFrame"></iframe>

    <div class="container">
        <!-- Sidebar -->
        <iframe src="../../_components/sidebar.php" class="sidebar" id="sidebarFrame"></iframe>

        <script>
            const userRole = "admin";

            document.getElementById("sidebarFrame").addEventListener("load", function() {
                this.contentWindow.postMessage({ role: userRole }, "*");
            });

            const userRole2 = "ADMIN";

            document.getElementById("headerFrame").addEventListener("load", function() {
                this.contentWindow.postMessage({ role: userRole2 }, "*");
            });
        </script>

        <!-- Main Content -->
        <iframe id="content" src="./home.php" class="content"></iframe>

        <script>
            document.getElementById("content").addEventListener("load", function () {
            const iframe = this;

            try {
                // This only works if SAME ORIGIN!!! No cross-domain peepin!
                // console.log("hello");
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                if (iframeDoc.body.innerText.includes("404")) {
                    // console.log("Iframe is deader than your social life 😹");
                    window.location.href = "/this-page-definitely-does-not-exist-nyahaha.html";
                // Maybe show an error in the parent page, nyahaha~
                }
            } catch (err) {
                console.warn("Can't access iframe contents, baka. Cross-origin maybe?");
            }
            });
        </script>
    </div>

    <!-- Footer -->
    <iframe src="../../_components/footer.html" class="footer"></iframe>
</body>
</html>
