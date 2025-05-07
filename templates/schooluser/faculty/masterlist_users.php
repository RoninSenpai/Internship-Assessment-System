<?php
    session_start();
    include '../../../database/database.php';

    $token = $_SESSION['user_id'] ?? '';

    $query = "
    SELECT 
        inst.internship_id,
        stu.user_last_name,
        stu.user_first_name,
        CONCAT(sch.school_acr, ' – ', pr.program_name) AS school_program,
        inst.internship_year,
        inst.internship_date_started,
        inst.internship_date_ended,
        pr.program_name AS course,
        intern.intern_gender AS gender,
        intern.intern_birthdate AS birthdate,
        stu.user_email AS email,
        CONCAT_WS(', ',
        intern.intern_city,
        intern.intern_province_or_state,
        intern.intern_postal_code,
        intern.intern_country
        ) AS address,
        stu.user_date_created,
        stu.user_date_updated,
        comp.company_name,
        dept.department_name,
        CONCAT(supu.user_first_name, ' ', supu.user_last_name) AS supervisor_name,
        inst.internship_job_role,
        inst.batch
    FROM internships AS inst
    INNER JOIN interns AS intern
        ON inst.intern_id = intern.intern_id
    INNER JOIN users AS stu
        ON intern.schooluser_id = stu.user_id
    INNER JOIN programs AS pr
        ON intern.program_id = pr.program_id
    INNER JOIN schools AS sch
        ON pr.school_id = sch.school_id      /* HERE IT IS, BITCH! */
    LEFT JOIN supervisors AS sup
        ON inst.supervisor_id = sup.supervisor_id
    LEFT JOIN users AS supu
        ON sup.user_id = supu.user_id
    LEFT JOIN departments AS dept
        ON sup.department_id = dept.department_id
    LEFT JOIN companies AS comp
        ON dept.company_id = comp.company_id
    ORDER BY inst.internship_year ASC, stu.user_last_name ASC, stu.user_first_name ASC
    ";

    $stmt = $mysqli->prepare($query);
    $stmt->execute();

    $stmt->bind_result(
    $internship_id, $last, $first, $school_program,
    $year, $start, $end, $course, $gender, $birthdate,
    $email, $address, $created, $updated,
    $company, $department, $supervisor, $job_role, $batch
    );

    $rows = [];

    while ($stmt->fetch()) {
        $rows[] = [
            'internship_id' => $internship_id,
            'last' => $last,
            'first' => $first,
            'school_program' => $school_program,
            'year' => $year,
            'start' => $start,
            'end' => $end,
            'course' => $course,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'email' => $email,
            'address' => $address,
            'created' => $created,
            'updated' => $updated,
            'company' => $company,
            'department' => $department,
            'supervisor' => $supervisor,
            'job_role' => $job_role,
            'batch' => $batch
        ];
    }
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

<body class="content">
    <div class="box-shadow">
        <div class="content-card">
            <span class="back" onclick="changeIframe('../../../templates/schooluser/faculty/masterlist.php')"> &lt; Back</span>

            <h2>User Masterlist</h2>
            <p style="margin-bottom:20px">Here you can view the list of users in the system.</p>

            <div class="masterlist-view">
                <div class="table-wrapper">
                    <table border="1" class="masterlist">
                        <colgroup>
                            <col style="width: 35px;"> <!-- Fits content -->
                            <col> <!-- Fixed width -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="fit">#</th>
                                <th class="name-column">Interns</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rowNumber = 1;
                            foreach ($rows as $row) {
                                $dataAttrs = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                echo "<tr class='clickable-row' data-row='$dataAttrs'>";
                                echo    "<td>{$rowNumber}</td>";
                                echo    "<td class='name-column'>{$row['last']}, {$row['first']}</td>";
                                echo '<tr>';
                                $rowNumber++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="user-info">
                    <div class="user-info-content">
                        <h2>USER INFORMATION</h2>
                        <p>Select a user to view info.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box-shadow">
        <div class="content-card">
            <h2>User Masterlist</h2>
            <p>Here you can view the list of users in the system.</p>

            <div class="table-wrapper">
                <table border="1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>School and Program</th>
                            <th>Internship Year</th>
                            <th>Internship Start</th>
                            <th>Internship End</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Supervisor</th>
                            <th>Job Role</th>
                            <th>Batch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rowNumber = 1;
                        foreach ($rows as $row) {
                            echo '<tr>';
                            echo    "<td>{$rowNumber}</td>";
                            echo    "<td>{$row['last']}, {$row['first']}</td>";
                            echo    "<td>{$row['school_program']}</td>";
                            echo    "<td>{$row['year']}</td>";
                            echo    "<td>{$row['start']}</td>";
                            echo    "<td>{$row['end']}</td>";
                            echo    "<td>{$row['gender']}</td>";
                            echo    "<td>{$row['birthdate']}</td>";
                            echo    "<td>{$row['email']}</td>";
                            echo    "<td>{$row['address']}</td>";
                            echo    "<td>{$row['created']}</td>";
                            echo    "<td>{$row['updated']}</td>";
                            echo    "<td>{$row['company']}</td>";
                            echo    "<td>{$row['department']}</td>";
                            echo    "<td>{$row['supervisor']}</td>";
                            echo    "<td>{$row['job_role']}</td>";
                            echo    "<td>{$row['batch']}</td>";
                            echo '</tr>';
                            $rowNumber++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

<script src="../../../templates/_components/sidebar.js"></script>
<script src="../../../templates/_components/content.js"></script>
<script src="masterlist_users.js"></script>