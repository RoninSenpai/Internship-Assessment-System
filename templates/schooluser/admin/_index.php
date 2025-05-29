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
    <link rel="icon" type="image/png" href="../../../static/images/components\rias_icon.png">
    <link rel="stylesheet" href="../../../static/css/components.css">
    <link rel="stylesheet" href="style_admin.css">
    <style>
        /* Style to position dropdown outside header */
        #notificationDropdown {
            position: absolute;
            top: 60px; /* Adjust as needed */
            right: 20px; /* Adjust as needed */
            z-index: 1000; /* Ensure it's above other elements */
        }
    </style>
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
                    window.location.href = "/404_pagenotfound";
                // Maybe show an error in the parent page, nyahaha~
                }
            } catch (err) {
                console.warn("Can't access iframe contents, baka. Cross-origin maybe?");
            }
            });
        </script>

        <script>
            window.addEventListener("message", function(event) {
                console.log("Parent: received message from content iframe", event.data);
                // Forward notification messages to the header iframe
                if (event.data && event.data.type === "addNotification") {
                    const headerFrame = document.getElementById("headerFrame");
                    if (headerFrame && headerFrame.contentWindow) {
                        console.log("Parent: forwarding message to header iframe", event.data);
                        headerFrame.contentWindow.postMessage(event.data, "*");
                    }
                }
            });
        </script>

    </div>

    <!-- Footer -->
    <iframe src="../../_components/footer.html" class="footer"></iframe>

    <script>
        // Function to move the notification dropdown outside the header iframe
        function moveNotificationDropdown() {
            const headerFrame = document.getElementById("headerFrame");
            if (headerFrame && headerFrame.contentDocument) {
                const dropdown = headerFrame.contentDocument.getElementById("notificationDropdown");
                if (dropdown) {
                    document.body.appendChild(dropdown);
                    dropdown.style.position = "absolute";
                    dropdown.style.top = "60px"; // Adjust as needed
                    dropdown.style.right = "20px"; // Adjust as needed
                    dropdown.style.zIndex = "1000"; // Ensure it's above other elements
                }
            }
        }

        // Call the function after the header iframe has loaded
        document.getElementById("headerFrame").addEventListener("load", moveNotificationDropdown);
    </script>
</body>
</html>
