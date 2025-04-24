<?php
    include '../../database/database.php';

    $token = $_GET['token'] ?? null;
    if (!$token) {
    echo "Token not provided, baka!";
    exit;
    }

    $stmt = $mysqli->prepare("SELECT supervisor_id FROM send_assessments WHERE sendass_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
    echo "INVALID TOKEN, FOOL 💢";
    exit;
    }

    $supervisor_id = $result->fetch_assoc()['supervisor_id'];
    $stmt->close();

    // GET ALL CURRENT INTERNS FOR THE SUPERVISOR
    $stmt = $mysqli->prepare("
    SELECT 
        x.intern_id,    
        u.user_first_name, u.user_last_name,
        p.program_name,
        i.internship_year
    FROM internships i
    JOIN interns x ON i.intern_id = x.intern_id
    JOIN school_users su ON x.schooluser_id = su.schooluser_id
    JOIN users u ON su.user_id = u.user_id
    JOIN programs p ON x.program_id = p.program_id
    WHERE i.supervisor_id = ?
        AND i.internship_date_ended >= CURDATE()
    ");
    $stmt->bind_param("i", $supervisor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $mysqli->close();
?>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../static/css/components.css">
    <link rel="stylesheet" href="masterlist.css">
</head>

<div class="content">
    <p class="date">Wednesday, March 12, 2025</p>
    <script src="../../templates/_components/date.js"></script>

    <div class="box-shadow">
        <div class="content-card">
            <!-- <section class="title-container">
                <h1 class="title">Intern Masterlist</h1>
                <p>Thank you for evaluating the student-intern objectively and completely.</p>
            </section> -->
            
            <!-- <hr style="border: none; border-top: 2px solid #d3d3d3; margin: 20px 0;"> -->
            
            <h2 class="section-title">Your Interns for the School Year 2025 - 2026:</h2>

            <section class="top-bar">
            <form class="search-bar" role="search" onsubmit="return false;">
                <input type="text" id="userFilter" placeholder="FILTER INTERNS BY NAME, COURSE, OR INTERN YEAR..." />
            </form>
            <!-- <button type="button">Switch to Table View</button> -->
            </section>
            
            <section id="intern-cards" class="cards">
                <?php
                    while ($row = $result->fetch_assoc()) {
                        $full_name = strtoupper($row['user_last_name']) . ", " . strtoupper($row['user_first_name']);
                        $program = htmlspecialchars($row['program_name']);
                        $internship_year = htmlspecialchars($row['internship_year']); // This is INTERN1 or INTERN2 now
                        $intern_id = htmlspecialchars($row['intern_id']);

                        echo <<<HTML
                        <article class="card">
                            <img src="https://static.vecteezy.com/system/resources/previews/002/534/006/original/social-media-chatting-online-blank-profile-picture-head-and-body-icon-people-standing-icon-grey-background-free-vector.jpg" alt="Profile picture of {$full_name}" />
                            <h3 class="name">{$full_name}</h3>
                            <p class="course">{$program}</p>
                            <p class="internship-year">{$internship_year}</p>
                            <div class="evaluation-button-container">
                                <a onclick="changeIframe('../../templates/supervisor/evaluation.php?token={$token}&intern_id={$intern_id}')" class="evaluation-btn" data-status="evaluate">EVALUATE</a>
                            </div>
                        </article>
                        HTML;
                    }
                ?>
                <article class="card">
                    <img src="https://th.bing.com/th/id/OIP.UvgAzpk1q-n7gqp0r45KXgHaEK?rs=1&pid=ImgDetMain" alt="Profile picture of ABONITA, RONIN N." />
                    <h3 class="name">ABONITA, RONIN N.</h3>
                    <p class="course">BS Computer Engineering</p>
                    <p class="intern-id">INTERN1</p>
                    <div class="evaluation-button-container">
                        <a onclick="changeIframe('../../templates/supervisor/evaluation.html')" class="evaluation-btn" data-status="evaluate">EVALUATE</a>
                    </div>
                </article>
                <article class="card">
                    <img src="https://th.bing.com/th/id/OIP.UvgAzpk1q-n7gqp0r45KXgHaEK?rs=1&pid=ImgDetMain" alt="Profile picture of ABONITA, RONIN N." />
                    <h3 class="name">AQUINO, JULIUS ANTON V.</h3>
                    <p class="course">BS Computer Engineering</p>
                    <p class="intern-id">INTERN2</p>
                    <div class="evaluation-button-container">
                        <a onclick="changeIframe('evaluation.html')" class="evaluation-btn" data-status="continue">CONTINUE</a>
                    </div>
                </article>
                <article class="card">
                    <img src="https://th.bing.com/th/id/OIP.UvgAzpk1q-n7gqp0r45KXgHaEK?rs=1&pid=ImgDetMain" alt="Profile picture of ABONITA, RONIN N." />
                    <h3 class="name">KURUMPHANG, MARVIN J.</h3>
                    <p class="course">BS Civil Engineering</p>
                    <p class="intern-id">INTERN2</p>
                    <div class="evaluation-button-container">
                        <a onclick="changeIframe('evaluation.html')" class="evaluation-btn" data-status="done">DONE</a>
                    </div>
                </article>
                <article class="card">
                    <img src="https://th.bing.com/th/id/OIP.UvgAzpk1q-n7gqp0r45KXgHaEK?rs=1&pid=ImgDetMain" alt="Profile picture of ABONITA, RONIN N." />
                    <h3 class="name">VELORIA, DENBERT J.</h3>
                    <p class="course">BS Civil Engineering</p>
                    <p class="intern-id">INTERN1</p>
                    <div class="evaluation-button-container">
                        <a onclick="changeIframe('evaluation.html')" class="evaluation-btn" data-status="done">DONE</a>
                    </div>
                </article>
            </section>
            <p id="noResults" style="display: none; text-align: center; margin-top: 20px;">No results found.</p>
        </div>
    </div>
</div>

<script src="../../templates/_components/sidebar.js"></script>
<script src="../../templates/_components/content.js"></script>
<script src="../../templates\supervisor\masterlist.js"></script>
