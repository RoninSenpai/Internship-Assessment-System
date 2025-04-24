<?php
    include '../../database/database.php';
    $token = $_GET['token'] ?? '';
?>

<head>
    <script>
        const authToken = "<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
</head>

<div class="sidebar-content" id="sidebar-content">
    <div class="sidebar-item" onclick="toggleSidebar()">
    </div>
</div>

<link rel="stylesheet" href="../../static/css/components.css">
<script src="sidebar.js"></script>