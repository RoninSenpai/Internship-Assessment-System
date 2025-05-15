<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include '../../../database/database.php';
    header('Content-Type: application/json');

    $load = $_GET['load'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $load === 'all') {
        $data = [
            'schools' => [],
            'programs' => [],
            'companies' => [],
            'departments' => [],
            'supervisors' => [],
            'batch' => null
        ];

        // SCHOOLS
        $schoolQuery = "SELECT school_id, school_name FROM schools";
        $schoolResult = $mysqli->query($schoolQuery);
        while ($row = $schoolResult->fetch_assoc()) {
            $data['schools'][] = $row;
        }

        // PROGRAMS
        $programQuery = "SELECT program_id, program_name, school_id FROM programs";
        $programResult = $mysqli->query($programQuery);
        while ($row = $programResult->fetch_assoc()) {
            $data['programs'][] = $row;
        }

        // COMPANIES
        $companyQuery = "SELECT company_id, company_name, company_email, company_website, company_address FROM companies";
        $companyResult = $mysqli->query($companyQuery);
        while ($row = $companyResult->fetch_assoc()) {
            $data['companies'][] = $row;
        }

        // DEPARTMENTS
        $deptQuery = "SELECT department_id, department_name, company_id FROM departments";
        $deptResult = $mysqli->query($deptQuery);
        while ($row = $deptResult->fetch_assoc()) {
            $data['departments'][] = $row;
        }

        // SUPERVISORS (fixed)
        $supQuery = "
            SELECT 
                s.supervisor_id,
                s.user_id AS supervisor_user_id,
                u.user_id AS user_table_user_id,
                CONCAT(u.user_first_name, ' ', u.user_last_name) AS user_name,
                u.user_email,
                s.supervisor_contact_no,
                s.supervisor_job_role,
                s.department_id
            FROM supervisors s
            LEFT JOIN users u ON s.user_id = u.user_id;
        ";
        $supResult = $mysqli->query($supQuery);
        while ($row = $supResult->fetch_assoc()) {
            $data['supervisors'][] = $row;
        }

        // BATCH
        $result = $mysqli->query("SELECT batch FROM internships WHERE batch IS NOT NULL ORDER BY internship_id DESC LIMIT 1");
        $row = $result->fetch_assoc();
        $latestBatch = $row ? $row['batch'] : null;

        if ($latestBatch && preg_match('/^(\d{4})-(\d{4})$/', $latestBatch, $matches)) {
            $year = $matches[1];
            $number = str_pad(((int)$matches[2]) + 1, 4, '0', STR_PAD_LEFT);
            $newBatch = "$year-$number";
        } else {
            $newBatch = date('Y') . "-0001";
        }

        $data['batch'] = $newBatch;

        // USERS
        $userQuery = "SELECT user_id, user_first_name, user_last_name, user_email FROM users";
        $userResult = $mysqli->query($userQuery);
        $data['users'] = [];
        while ($row = $userResult->fetch_assoc()) {
            $data['users'][] = $row;
        }

        echo json_encode($data);
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $firstName = $_POST["first-name"];
        $lastName = $_POST["last-name"];
        $gender = $_POST["gender"];
        $birthdate = $_POST["birthdate"];
        $city = $_POST["city"];
        $province = $_POST["province"];
        $postal = $_POST["postal"];
        $country = $_POST["country"];
        $batch = $_POST["batch"];
        $internshipStart = $_POST["internship_start"];
        $internshipEnd = $_POST["internship_end"];
        $schoolId = $_POST["school"];
        $programId = $_POST["program"];
        $companyId = $_POST["company"]; // still unused
        $departmentId = $_POST["department"]; // still unused
        $supervisorId = $_POST["supervisor"];
        $jobRole = $_POST["job_role"];
        $defaultPassword = password_hash("intern123", PASSWORD_DEFAULT); // set default pw if needed
        $schoolGivenId = uniqid("stu_"); // generate dummy ID for now
    
        $mysqli->begin_transaction();
    
        try {
            // 1. Insert into users
            $stmt = $mysqli->prepare("INSERT INTO users (
                user_first_name, 
                user_last_name, 
                user_email, 
                user_role, 
                user_is_archived
            ) VALUES (?, ?, ?, 'intern', 0)");
            $stmt->bind_param("sss", $firstName, $lastName, $email);
            $stmt->execute();
            $userId = $stmt->insert_id;
    
            // 2. Insert into school_users
            $stmt2 = $mysqli->prepare("INSERT INTO school_users (
                user_id, 
                school_given_id, 
                schooluser_password
            ) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $userId, $schoolGivenId, $defaultPassword);
            $stmt2->execute();
            $schooluserId = $stmt2->insert_id;
    
            // 3. Insert into interns
            $stmt3 = $mysqli->prepare("INSERT INTO interns (
                schooluser_id, 
                program_id, 
                intern_birthdate, 
                intern_gender, 
                intern_city, 
                intern_province_or_state, 
                intern_postal_code, 
                intern_country
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt3->bind_param("iissssss", $schooluserId, $programId, $birthdate, $gender, $city, $province, $postal, $country);
            $stmt3->execute();
            $internId = $stmt3->insert_id;
    
            // 4. Insert into internships
            $stmt4 = $mysqli->prepare("INSERT INTO internships (
                intern_id,
                supervisor_id,
                schooluser_id,
                internship_year,
                internship_date_started,
                internship_date_ended,
                internship_job_role,
                batch
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt4->bind_param("iiisssss", $internId, $supervisorId, $schooluserId, $batch, $internshipStart, $internshipEnd, $jobRole, $batch);
            $stmt4->execute();
    
            $mysqli->commit();
            echo json_encode(["success" => true]);
        } catch (Exception $e) {
            $mysqli->rollback();
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    
        exit;
    }
        
    echo json_encode(["error" => "Invalid request"]);
?>
        