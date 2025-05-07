<?php
    session_start();
    include '../../../database/database.php';

    $token = $_SESSION['user_id'] ?? '';

    $query = "
        SELECT
            COUNT(*) AS total_users,
            SUM(user_role = 'Industry Supervisor') AS total_industry_supervisors,
            SUM(user_role = 'Executive Director') AS total_executive_directors,
            SUM(user_role = 'Program Director') AS total_program_directors,
            SUM(user_role = 'Student Intern') AS total_student_interns,
            SUM(user_role = 'Admin') AS total_admins,

            SUM(user_is_archived = 1) AS total_archived_users,
            SUM(user_is_archived = 1 AND user_role = 'Industry Supervisor') AS archived_industry_supervisors,
            SUM(user_is_archived = 1 AND user_role = 'Executive Director') AS archived_executive_directors,
            SUM(user_is_archived = 1 AND user_role = 'Program Director') AS archived_program_directors,
            SUM(user_is_archived = 1 AND user_role = 'Student Intern') AS archived_student_interns,
            SUM(user_is_archived = 1 AND user_role = 'Admin') AS archived_admins
        FROM users;
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $stats = [];

    if ($result->num_rows > 0) {
        $stats = $result->fetch_assoc();
    }
    $stmt->close();

    // Companies table stats
    $stmt = $mysqli->prepare("
        SELECT
            COUNT(*) AS total_companies,
            SUM(partnership_status = 0) AS pending_companies
        FROM companies;
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $companyStats = $result->fetch_assoc();
        $stats = array_merge($stats, $companyStats);
    }
    $stmt->close();

    // Schools table stats
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS total_schools FROM schools");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $schoolStats = $result->fetch_assoc();
        $stats = array_merge($stats, $schoolStats);
    }
    $stmt->close();

    $mysqli->close();
?>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">
    <title>Internship Assessment System</title>
    <link rel="stylesheet" href="../../../static/css/components.css">
    <link rel="stylesheet" href="masterlist.css" />
</head>

<body>
    <div class="box-shadow">
        <div class="content-card">
            <div class="cards">
                <div class="card" onclick="changeIframe('../../../templates/schooluser/faculty/masterlist_users.php?token=<?php echo urlencode($token); ?>')">
                    <h3>Users</h3>
                    <p>Total Student Interns: <?php echo $stats['total_student_interns']; echo " (" . $stats['archived_student_interns']; ?> archived)</p>
                    <p>Total Industry Partners: <?php echo $stats['total_industry_supervisors']; echo " (" . $stats['archived_industry_supervisors']; ?> archived)</p>
                </div>
                <div class="card">
                    <h3>Schools</h3>
                    <p><?php echo $stats['total_schools']; ?></p>
                </div>
                <div class="card">
                    <h3>Companies</h3>
                    <p><?php echo $stats['total_companies']; echo " (" . $stats['pending_companies']; ?> inactive)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="box-shadow">
        <div class="content-card">
            <div class="back">
                <button onclick="window.location.href='../../../templates/schooluser/faculty/home.html'">Back</button>
            </div>
            <div class="mainpanel">
            <div class="sidepanel">
                <h1>Student Masterlist</h1>
                <div class="search-bar">
                    <form action="/supervisor/masterlist" method="GET">
                        <input type="text" name="search" placeholder="Search by student name..." required>
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="list-panel">
                    <ul class="custom-tree">
                        <li>
                            <details>
                                <summary>▶ School of Engineering</summary>
                                <ul>
                                    <li>Computer Engineering</li>
                                    <li>Civil Engineering</li>
                                    <li>Electronics Engineering</li>
                                </ul>
                            </details>
                        </li>
                        <li>
                            <details>
                                <summary>▶ School of Computing and Information Technology</summary>
                                <ul>
                                    <li>Computer Science</li>
                                    <li>Information Technology</li>
                                    <li>Computer Technology</li>
                                </ul>
                            </details>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="information-panel">
            <table class="styled-table">
                <colgroup>
                    <col style="width: 3%;">
                    <col style="width: 50%;">
                    <col style="width: 30%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Intern School Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Denbert Veloria</td>
                        <td>INTERN 1 2024-2025</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Marvin Kurumphang</td>
                        <td>INTERN 2 2024-2025</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Julius Aquino</td>
                        <td>INTERN 2 2024-2025</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Ronin Abonita</td>
                        <td>INTERN 2 2024-2025</td>
                    </tr>
                </tbody>
            </table>
            <div class="information-data-panel-header">
                <h2>SCHOOL OF ENGINEERING</h2>
                <div class="information-data-professors">
                    <p><strong>Executive Director:</strong> Leonardo Samaniego, Jr.</p>
                    <p><strong>Program Director:</strong> Serge Peruda, Jr.</p>
                    <hr>
                    <p><strong>INTERN 1 Students:</strong> 130 ↗</p>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../../../templates/_components/sidebar.js"></script>
<script src="../../../templates/_components/content.js"></script>