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
    echo "INVALID TOKEN, FOOL 💢 (masterlist.php)";
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
                    $internship_year = htmlspecialchars($row['internship_year']);
                    $intern_id = htmlspecialchars($row['intern_id']);

                    $sql = "
                        SELECT ig.supervisor_grade
                        FROM internship_grades ig
                        INNER JOIN internships i ON i.internship_id = ig.internship_id
                        WHERE i.intern_id = ?
                    ";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("i", $intern_id);
                    $stmt->execute();
                    $result2 = $stmt->get_result();
                    
                    $supervisor_graded = null;
                    
                    if ($row2 = $result2->fetch_assoc()) { // <--- DIFFERENT VARIABLE NAME
                        if (is_null($row2['supervisor_grade'])) {
                            $supervisor_graded = false;
                        } else {
                            $supervisor_graded = true;
                        }
                    } else {
                        $supervisor_graded = null;
                    }
                    $stmt->close();

                    $internshipSql = "
                        SELECT internship_id 
                        FROM internships 
                        WHERE intern_id = ?
                    ";
                    $stmt = $mysqli->prepare($internshipSql);
                    $stmt->bind_param("i", $intern_id);
                    $stmt->execute();
                    $result3 = $stmt->get_result();
                    $internship_id = null;

                    if ($row3 = $result3->fetch_assoc()) { // <--- DIFFERENT VARIABLE NAME
                        $internship_id = $row3['internship_id'];
                    }
                    $stmt->close();

                    $continueStatus = false;

                    if ($internship_id) {
                        $sql1 = "
                            SELECT 1 
                            FROM assessment_grades 
                            WHERE internship_id = ?
                            LIMIT 1
                        ";
                        $stmt1 = $mysqli->prepare($sql1);
                        $stmt1->bind_param("i", $internship_id);
                        $stmt1->execute();
                        $result1 = $stmt1->get_result();
                        if ($result1->num_rows > 0) {
                            $continueStatus = true;
                        }
                        $stmt1->close();

                        if (!$continueStatus) {
                            $sql2 = "
                                SELECT 1 
                                FROM feedback 
                                WHERE internship_id = ?
                                LIMIT 1
                            ";
                            $stmt2 = $mysqli->prepare($sql2);
                            $stmt2->bind_param("i", $internship_id);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
                            if ($result2->num_rows > 0) {
                                $continueStatus = true;
                            }
                            $stmt2->close();
                        }
                    }

                    if ($supervisor_graded) {
                        $dataStatus = 'done';
                        $dataStatusText = 'DONE';
                    } else {
                        $dataStatus = ($continueStatus === true) ? 'continue' : 'evaluate';
                        $dataStatusText = ($continueStatus === true) ? 'CONTINUE' : 'EVALUATE';
                    }

                    $iframeLink = "../../templates/supervisor/evaluation.php?token={$token}&intern_id={$intern_id}&data-status={$dataStatus}";

                    echo <<<HTML
                    <article class="card">
                        <img src="https://static.vecteezy.com/system/resources/previews/002/534/006/original/social-media-chatting-online-blank-profile-picture-head-and-body-icon-people-standing-icon-grey-background-free-vector.jpg" alt="Profile picture of {$full_name}" />
                        <h3 class="name">{$full_name}</h3>
                        <p class="course">{$program}</p>
                        <p class="internship-year">{$internship_year}</p>
                        <div class="evaluation-button-container">
                            <a onclick="changeIframe('{$iframeLink}')" 
                                class="evaluation-btn" 
                                data-status="{$dataStatus}">
                                $dataStatusText
                            </a>                            
                        </div>
                    </article>
                    HTML;
                }
            ?>

            </section>
            <p id="noResults" style="display: none; text-align: center; margin-top: 20px;">No results found.</p>
        </div>
    </div>
</div>

<script src="../../templates/_components/sidebar.js"></script>
<script src="../../templates/_components/content.js"></script>
<script src="../../templates\supervisor\masterlist.js"></script>
