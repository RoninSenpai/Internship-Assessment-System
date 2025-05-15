<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();
    include '../../../database/database.php';

    $token = $_SESSION['user_id'] ?? '';

    if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
        header('Content-Type: application/json');


        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
        $searchableColumns = $_GET['visible_columns'] ?? [];
        $searchClause = "";

        if (!empty($search)) {
            $escapedSearch = '%' . $mysqli->real_escape_string(trim($_GET['search'])) . '%';

            // 🔬 Match front-end values with correct SQL fields
            $searchableMap = [
                'Email' => "stu.user_email",
                'Name' => "CONCAT(stu.user_last_name, ', ', stu.user_first_name)",
                'Batch' => "inst.batch",
                'School and Program' => "CONCAT(sch.school_acr, ' – ', pr.program_name)",
                'Internship Year' => "inst.internship_year",
                'Internship Start' => "inst.internship_date_started",
                'Internship End' => "inst.internship_date_ended",
                'Company' => "comp.company_name",
                'Department' => "dept.department_name",
                'Job Role' => "inst.internship_job_role",
                'Supervisor' => "CONCAT(supu.user_first_name, ' ', supu.user_last_name)",
                'Supervisor Email' => "supu.user_email",
                'Supervisor Contact No' => "sup.supervisor_contact_no",
                'Gender' => "intern.intern_gender",
                'Birthdate' => "intern.intern_birthdate",
                'Address' => "CONCAT_WS(', ', intern.intern_city, intern.intern_province_or_state, intern.intern_postal_code, intern.intern_country)",
                'Date Added' => "stu.user_date_created",
                'Date Updated' => "stu.user_date_updated"
            ];

            // Fields that should always be searched
            $alwaysSearchFields = [
                "CONCAT(stu.user_last_name, ', ', stu.user_first_name)",
                "stu.user_first_name",
                "stu.user_last_name",
                "stu.user_email"
            ];

            $likeClauses = array_map(fn($field) => "$field LIKE '$escapedSearch'", $alwaysSearchFields);

            // Add optional fields if checkboxes are checked
            foreach ($searchableColumns as $column) {
                if (isset($searchableMap[$column])) {
                    $likeClauses[] = $searchableMap[$column] . " LIKE '$escapedSearch'";
                }
            }

            if (!empty($likeClauses)) {
                $searchClause = " AND (" . implode(" OR ", $likeClauses) . ")";
            }
        }

        $sortBy = $_GET['sort_by'] ?? '';
        $sortDir = ($_GET['sort_dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

        $sortableFields = [
            // Basic Info
            'name' => "CONCAT(stu.user_last_name, ', ', stu.user_first_name)",
            'email' => 'stu.user_email',
        
            // School Info
            'batch' => 'inst.batch',
            'school_and_program' => "CONCAT(sch.school_acr, ' – ', pr.program_name)",
            'internship_year' => 'inst.internship_year',
            'internship_start' => 'inst.internship_date_started',
            'internship_end' => 'inst.internship_date_ended',
        
            // Internship Info
            'company' => 'comp.company_name',
            'department' => 'dept.department_name',
            'job_role' => 'inst.internship_job_role',
            'supervisor' => "CONCAT(supu.user_first_name, ' ', supu.user_last_name)",
            'supervisor_email' => 'supu.user_email',
            'supervisor_contact_no' => 'supu.user_contact_no',
        
            // User Info
            'gender' => 'intern.intern_gender',
            'birthdate' => 'intern.intern_birthdate',
            'address' => "CONCAT_WS(', ', intern.intern_city, intern.intern_province_or_state, intern.intern_postal_code, intern.intern_country)",
            'date_added' => 'stu.user_date_created',
            'date_updated' => 'stu.user_date_updated',
        ];

        $orderClause = '';
        if (array_key_exists($sortBy, $sortableFields)) {
            $orderClause = " ORDER BY " . $sortableFields[$sortBy] . " $sortDir";
        }

        $countQuery = "
            SELECT COUNT(*) AS total
            FROM internships AS inst
            INNER JOIN interns AS intern ON inst.intern_id = intern.intern_id
            INNER JOIN school_users AS scu ON intern.schooluser_id = scu.schooluser_id
            INNER JOIN users AS stu ON scu.user_id = stu.user_id
            INNER JOIN programs AS pr ON intern.program_id = pr.program_id
            INNER JOIN schools AS sch ON pr.school_id = sch.school_id
            LEFT JOIN supervisors AS sup ON inst.supervisor_id = sup.supervisor_id
            LEFT JOIN users AS supu ON sup.user_id = supu.user_id
            LEFT JOIN departments AS dept ON sup.department_id = dept.department_id
            LEFT JOIN companies AS comp ON dept.company_id = comp.company_id
            WHERE stu.user_is_archived = 0 $searchClause
        ";

        $countResult = $mysqli->query($countQuery);
        $totalCount = $countResult->fetch_assoc()['total'];

        $query = "
            SELECT 
                inst.internship_id AS id,
                CONCAT(stu.user_last_name, ', ', stu.user_first_name) AS Name,
                CONCAT(sch.school_acr, ' – ', pr.program_name) AS School,
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
                supu.user_email AS supervisor_email,
                sup.supervisor_contact_no AS supervisor_contact_no,
                inst.internship_job_role,
                inst.batch
            FROM internships AS inst
            INNER JOIN interns AS intern ON inst.intern_id = intern.intern_id
            INNER JOIN school_users AS scu ON intern.schooluser_id = scu.schooluser_id
            INNER JOIN users AS stu ON scu.user_id = stu.user_id
            INNER JOIN programs AS pr ON intern.program_id = pr.program_id
            INNER JOIN schools AS sch ON pr.school_id = sch.school_id
            LEFT JOIN supervisors AS sup ON inst.supervisor_id = sup.supervisor_id
            LEFT JOIN users AS supu ON sup.user_id = supu.user_id
            LEFT JOIN departments AS dept ON sup.department_id = dept.department_id
            LEFT JOIN companies AS comp ON dept.company_id = comp.company_id
            WHERE stu.user_is_archived = 0 $searchClause $orderClause LIMIT $limit OFFSET $offset
        ";

        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
            exit;
        }

        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
            exit;
        }

        $stmt->bind_result(
            $internship_id, $name, $school_program,
            $year, $start, $end, $course, $gender, $birthdate,
            $email, $address, $created, $updated,
            $company, $department, $supervisor,
            $supervisor_email, $supervisor_contact_no, $job_role,
            $batch
        );

        $rows = [];
        while ($stmt->fetch()) {
            $rows[] = [
                'internship_id' => $internship_id,
                'name' => $name,
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
                'supervisor_email' => $supervisor_email,
                'supervisor_contact_no' => $supervisor_contact_no,
                'job_role' => $job_role,
                'batch' => $batch
            ];
        }

        echo json_encode([
            'totalCount' => (int)$totalCount,
            'data' => $rows
        ]);
        exit;
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

            <div class="edit-buttons">
                <button id="addUserButton">Add User</button>
                <button disabled data-tooltip="Select only one user first." id="viewUserButton">View/Edit User</button>
                <button disabled data-tooltip="Select only one user first." id="viewBatchButton">View Batch</button>
                <button onclick="updateTable(undefined, true);">Refresh Table</button>
            </div>

            <h2>User Masterlist</h2>
            <p>Here you can view the list of users in the system.</p>

            <div class="search-bar">
                <input
                    id="search"
                    placeholder="Search for interns..."
                />
                <span>Total Results: <strong id="totalCount">0</strong></span>
            </div>

            <div id="pagination">
                <label for="limitInput">Result Limit:</label>
                <input type="number" id="limitInput" name="limit" min="1" value="10" style="width: 60px;" />
                <button id="resetButton">Reset Limit</button>
                <button id="maxButton">Max Limit</button>

                <label for="pageInput">Goto Page:</label>
                <input type="number" id="pageInput" name="page" min="1" value="1" style="width: 60px;" />

                <button id="prevButton">Previous</button>
                <button id="nextButton">Next</button>
            </div>

            <div class="table-wrapper" style="overflow-x: auto;">
                <table border="1" id="allColumns">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all-rows"></th>
                            <th data-column="#">#</th>

                            <!-- Sortable Columns -->
                            <th class="sortable" data-key="email" data-column="Email">Email <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="name" data-column="Name">Name <span class="sort-icon"></span></th>

                            <!-- School Information -->
                            <th class="sortable" data-key="batch" data-column="Batch">Batch <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="school_and_program" data-column="School and Program">School and Program <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="internship_year" data-column="Internship Year">Internship Year <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="internship_start" data-column="Internship Start">Internship Start <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="internship_end" data-column="Internship End">Internship End <span class="sort-icon"></span></th>

                            <!-- Internship Information -->
                            <th class="sortable" data-key="company" data-column="Company">Company <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="department" data-column="Department">Department <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="job_role" data-column="Job Role">Job Role <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="supervisor" data-column="Supervisor">Supervisor <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="supervisor_email" data-column="Supervisor Email">Supervisor Email <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="supervisor_contact_no" data-column="Supervisor Contact No">Supervisor Contact No <span class="sort-icon"></span></th>

                            <!-- User Information -->
                            <th class="sortable" data-key="gender" data-column="Gender">Gender <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="birthdate" data-column="Birthdate">Birthdate <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="address" data-column="Address">Address <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="date_added" data-column="Date Added">Date Added <span class="sort-icon"></span></th>
                            <th class="sortable" data-key="date_updated" data-column="Date Updated">Date Updated <span class="sort-icon"></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS will load chaos here -->
                    </tbody>
                </table>
            </div>

            <div id="columnControls">
                <details style="pointer-events:none" open>
                    <summary style="list-style:none"><input type="checkbox" class="select-all" style="pointer-events:auto"> Toggle All</summary>
                    <div class="checkbox-container">
                        <details style="pointer-events:auto">
                            <summary><input type="checkbox" class="select-all"> School Information</summary>
                            <div class="checkbox-container">
                                <label><input type="checkbox" class="option" data-column="Batch"> Batch</label><br>
                                <label><input type="checkbox" class="option" data-column="School and Program"> School and Program</label><br>
                                <label><input type="checkbox" class="option" data-column="Internship Year"> Internship Year</label><br>
                                <label><input type="checkbox" class="option" data-column="Internship Start"> Internship Start</label><br>
                                <label><input type="checkbox" class="option" data-column="Internship End"> Internship End</label>
                            </div>
                        </details>
                        <details style="pointer-events:auto">
                            <summary><input type="checkbox" class="select-all"> Internship Information</summary>
                            <div class="checkbox-container">
                                <label><input type="checkbox" class="option" data-column="Company"> Company</label><br>
                                <label><input type="checkbox" class="option" data-column="Department"> Department</label><br>
                                <label><input type="checkbox" class="option" data-column="Job Role"> Job Role</label><br>
                                <label><input type="checkbox" class="option" data-column="Supervisor"> Supervisor</label><br>
                                <label><input type="checkbox" class="option" data-column="Supervisor Email"> Supervisor Email</label><br>
                                <label><input type="checkbox" class="option" data-column="Supervisor Contact No"> Supervisor Contact No</label><br>
                            </div>
                        </details>
                        <details style="pointer-events:auto">
                            <summary><input type="checkbox" class="select-all"> User Information</summary>
                            <div class="checkbox-container">
                                <label><input type="checkbox" class="option" data-column="Gender"> Gender</label><br>
                                <label><input type="checkbox" class="option" data-column="Birthdate"> Birthdate</label><br>
                                <label><input type="checkbox" class="option" data-column="Address"> Address</label><br>
                                <label><input type="checkbox" class="option" data-column="Date Added"> Date Added</label><br>
                                <label><input type="checkbox" class="option" data-column="Date Updated"> Date Updated</label><br>
                            </div>
                        </details>
                    </div>
                </details>
            </div>
        </div>
    </div>
</body>

<div id="floating-backdrop"></div>
<div id="user-floating-pane" class="floating-pane hidden">
    <div class="floating-pane-content">
        <button class="close-pane">×</button>
        <h2>User Information</h2>
        <div class="user-details">
            <!-- Inject data here -->
        </div>
    </div>
</div>

<script src="../../../templates/_components/sidebar.js"></script>
<script src="../../../templates/_components/content.js"></script>
<script src="masterlist_users.js"></script>