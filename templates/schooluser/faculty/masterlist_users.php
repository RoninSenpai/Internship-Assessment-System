<?php
    session_start();
    include '../../../database/database.php';

    $token = $_SESSION['user_id'] ?? '';

    // NO MORE TOKEN FILTER — WE WANT ALL THE INTERNS!!
    $query = "
    SELECT 
    inst.internship_id,
    stu.user_last_name,
    stu.user_first_name,
    CONCAT('School #', pr.school_id, ' – ', pr.program_name) AS school_program,
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
    LEFT JOIN supervisors AS sup
    ON inst.supervisor_id = sup.supervisor_id
    LEFT JOIN users AS supu
    ON sup.user_id = supu.user_id
    LEFT JOIN departments AS dept
    ON sup.department_id = dept.department_id
    LEFT JOIN companies AS comp
    ON dept.company_id = comp.company_id
    ORDER BY inst.internship_id DESC
    ";

    $stmt = $mysqli->prepare($query);
    // NO bind_param!!
    $stmt->execute();

    $stmt->bind_result(
    $internship_id, $last, $first, $school_program,
    $year, $start, $end, $course, $gender, $birthdate,
    $email, $address, $created, $updated,
    $company, $department, $supervisor, $job_role, $batch
    );
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
            <span class="back" onclick="changeIframe('../../../templates/schooluser/faculty/masterlist.php')"> &lt; Back</span>

            <h2>User Masterlist</h2>
            <p>Here you can view the list of users in the system.</p>

            <div class="table-wrapper">
                <table border="1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>School and Program</th>
                            <th>Internship Year</th>
                            <th>Internship Start</th>
                            <th>Internship End</th>
                            <th>Course</th>
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
                        while ($stmt->fetch()) {
                            echo '<tr>';
                            echo    "<td>{$internship_id}</td>";
                            echo    "<td>{$last}</td>";
                            echo    "<td>{$first}</td>";
                            echo    "<td>{$school_program}</td>";
                            echo    "<td>{$year}</td>";
                            echo    "<td>{$start}</td>";
                            echo    "<td>{$end}</td>";
                            echo    "<td>{$course}</td>";
                            echo    "<td>{$gender}</td>";
                            echo    "<td>{$birthdate}</td>";
                            echo    "<td>{$email}</td>";
                            echo    "<td>{$address}</td>";
                            echo    "<td>{$created}</td>";
                            echo    "<td>{$updated}</td>";
                            echo    "<td>{$company}</td>";
                            echo    "<td>{$department}</td>";
                            echo    "<td>{$supervisor}</td>";
                            echo    "<td>{$job_role}</td>";
                            echo    "<td>{$batch}</td>";
                            echo '</tr>';
                        }

                        $stmt->close();
                        $mysqli->close();
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>5</th>
                            <th>5</th>
                            <th>5</th>
                            <th>5</th>
                            <th>5</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

<script src="../../../templates/_components/sidebar.js"></script>
<script src="../../../templates/_components/content.js"></script>