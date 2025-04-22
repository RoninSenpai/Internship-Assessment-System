<?php

include 'C:\xampp\htdocs\Internship-Assessment-System\templates\login\connect.php';

// Serve schools as JSON for the registration form
if (isset($_GET['get_schools'])) {
    $schools = [];
    $result = $conn->query("SELECT school_id, school_name FROM schools");
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($schools);
    exit;
}

// Serve programs as JSON for the registration form
if (isset($_GET['get_programs'])) {
    $programs = [];
    // For Program Director: only show programs not already assigned to a PD
    if (isset($_GET['pd_only'])) {
        $result = $conn->query("SELECT p.program_id, p.program_name FROM programs p LEFT JOIN programdirectors pd ON p.program_id = pd.program_id WHERE pd.program_id IS NULL");
    } else {
        $result = $conn->query("SELECT program_id, program_name FROM programs");
    }
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($programs);
    exit;
}

// Serve companies as JSON
if (isset($_GET['get_companies'])) {
    $companies = [];
    $result = $conn->query("SELECT company_id, company_name FROM companies");
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($companies);
    exit;
}

// Serve departments as JSON
if (isset($_GET['get_departments'])) {
    $departments = [];
    $result = $conn->query("SELECT department_id, department_name FROM departments");
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($departments);
    exit;
}

// Serve users as JSON for the Manage Users modal
if (isset($_GET['get_users'])) {
    $users = [];
    $result = $conn->query("SELECT user_id, user_first_name, user_last_name, user_role FROM users");
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}

// Handle school registration
if (isset($_POST['school_name'])) {
    $school_name = trim($_POST['school_name']);
    if ($school_name === '') {
        echo 'School name is required.';
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO schools (school_name) VALUES (?)");
    $stmt->bind_param("s", $school_name);
    if ($stmt->execute()) {
        echo 'School registered successfully.';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
    exit;
}

// Handle program registration
if (isset($_POST['program_name']) && isset($_POST['school_id'])) {
    $program_name = trim($_POST['program_name']);
    $school_id = intval($_POST['school_id']);
    if ($program_name === '' || !$school_id) {
        echo 'Program name and school are required.';
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO programs (program_name, school_id) VALUES (?, ?)");
    $stmt->bind_param("si", $program_name, $school_id);
    if ($stmt->execute()) {
        echo 'Program registered successfully.';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
    exit;
}

// Handle company registration
if (isset($_POST['company_name'])) {
    $company_name = trim($_POST['company_name']);
    $company_email = isset($_POST['company_email']) ? trim($_POST['company_email']) : null;
    if ($company_name === '') {
        echo 'Company name is required.';
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO companies (company_name, company_email) VALUES (?, ?)");
    $stmt->bind_param("ss", $company_name, $company_email);
    if ($stmt->execute()) {
        echo 'Company registered successfully.';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
    exit;
}

// Handle department registration
if (isset($_POST['department_name']) && isset($_POST['company_id'])) {
    $department_name = trim($_POST['department_name']);
    $company_id = intval($_POST['company_id']);
    if ($department_name === '' || !$company_id) {
        echo 'Department name and company are required.';
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO departments (department_name, company_id) VALUES (?, ?)");
    $stmt->bind_param("si", $department_name, $company_id);
    if ($stmt->execute()) {
        echo 'Department registered successfully.';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
    exit;
}

if (
    isset($_POST['fname']) &&
    isset($_POST['lname']) &&
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['role'])
) {
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $date = date('Y-m-d H:i:s');
    $is_archived = '0';
    $schooluser_given_id = 0; // TODO: Generate or get from form if needed
    $school_id = isset($_POST['school_id']) ? intval($_POST['school_id']) : null;
    $program_id = isset($_POST['program_id']) ? intval($_POST['program_id']) : null;

    // Validate school_id for roles that require it
    if (($role === 'Executive Director' || $role === 'Program Director' || $role === 'Internship Officer') && !$school_id) {
        echo "Please select a school.";
        exit;
    }
    // Validate program_id for Student Intern
    if ($role === 'Student Intern' && !$program_id) {
        echo "Please select a program.";
        exit;
    }

    // Check if the email already exists
    $check_email = $conn->prepare("SELECT * FROM users WHERE user_email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();
    if ($result->num_rows > 0) {
        echo "Email already exists";
    } else {
        // Insert into users table (without password)
        $stmt = $conn->prepare("INSERT INTO users (user_first_name, user_last_name, user_email, user_date_created, user_date_updated, user_role, user_is_archived) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $first_name, $last_name, $email, $date, $date, $role, $is_archived);
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            // Insert into schoolusers table (with password)
            $stmt2 = $conn->prepare("INSERT INTO schoolusers (user_id, schooluser_given_id, schooluser_password) VALUES (?, ?, ?)");
            $stmt2->bind_param("iis", $user_id, $schooluser_given_id, $password);
            if ($stmt2->execute()) {
                $schooluser_id = $conn->insert_id;
                // Insert into role-specific tables
                $roleInsertSuccess = true;
                $roleInsertError = '';
                switch ($role) {
                    case 'Executive Director':
                        $stmt3 = $conn->prepare("INSERT INTO executivedirectors (schooluser_id, school_id) VALUES (?, ?)");
                        $stmt3->bind_param("ii", $schooluser_id, $school_id);
                        if (!$stmt3->execute()) {
                            $roleInsertSuccess = false;
                            $roleInsertError = $stmt3->error;
                        }
                        $stmt3->close();
                        break;
                    case 'Program Director':
                        $program_id_pd = $program_id ? $program_id : 1; // fallback if not set
                        $stmt3 = $conn->prepare("INSERT INTO programdirectors (schooluser_id, school_id, program_id) VALUES (?, ?, ?)");
                        $stmt3->bind_param("iii", $schooluser_id, $school_id, $program_id_pd);
                        if (!$stmt3->execute()) {
                            $roleInsertSuccess = false;
                            $roleInsertError = $stmt3->error;
                        }
                        $stmt3->close();
                        break;
                    case 'Internship Officer':
                        $stmt3 = $conn->prepare("INSERT INTO internshipofficers (schooluser_id, school_id) VALUES (?, ?)");
                        $stmt3->bind_param("ii", $schooluser_id, $school_id);
                        if (!$stmt3->execute()) {
                            $roleInsertSuccess = false;
                            $roleInsertError = $stmt3->error;
                        }
                        $stmt3->close();
                        break;
                    case 'Student Intern':
                        $stmt3 = $conn->prepare("INSERT INTO interns (schooluser_id, program_id) VALUES (?, ?)");
                        $stmt3->bind_param("ii", $schooluser_id, $program_id);
                        if (!$stmt3->execute()) {
                            $roleInsertSuccess = false;
                            $roleInsertError = $stmt3->error;
                        }
                        $stmt3->close();
                        break;
                }
                if ($roleInsertSuccess) {
                    echo "Registration successful";
                } else {
                    echo "Registration failed for role table: $roleInsertError";
                }
            } else {
                echo "Error inserting into schoolusers: " . $stmt2->error;
            }
            $stmt2->close();
        } else {
            echo "Error inserting into users: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_email->close();
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle other POST actions (edit/delete school, program, etc.)
    // Edit user
    if (isset($_POST['edit_user_id'])) {
        $id = intval($_POST['edit_user_id']);
        $first = $_POST['edit_user_first_name'] ?? '';
        $last = $_POST['edit_user_last_name'] ?? '';
        $role = $_POST['edit_user_role'] ?? '';
        if ($first === '' || $last === '' || $role === '') {
            echo 'Error: All fields are required.';
            exit;
        }
        // Get current role and schooluser_id
        $result = $conn->query("SELECT user_role FROM users WHERE user_id = $id");
        $row = $result->fetch_assoc();
        $old_role = $row ? $row['user_role'] : '';
        $schooluser_id = null;
        $res2 = $conn->query("SELECT schooluser_id FROM schoolusers WHERE user_id = $id");
        if ($row2 = $res2->fetch_assoc()) $schooluser_id = $row2['schooluser_id'];
        // If role changed, remove old role-specific record and add new one
        if ($old_role !== $role && $schooluser_id) {
            switch ($old_role) {
                case 'Executive Director':
                    $conn->query("DELETE FROM executivedirectors WHERE schooluser_id = $schooluser_id");
                    break;
                case 'Program Director':
                    $conn->query("DELETE FROM programdirectors WHERE schooluser_id = $schooluser_id");
                    break;
                case 'Internship Officer':
                    $conn->query("DELETE FROM internshipofficers WHERE schooluser_id = $schooluser_id");
                    break;
                case 'Student Intern':
                    $conn->query("DELETE FROM interns WHERE schooluser_id = $schooluser_id");
                    break;
            }
            // Insert new role-specific record (with placeholder values)
            switch ($role) {
                case 'Executive Director':
                    $school_id = 1; // TODO: prompt/select in UI
                    $stmt3 = $conn->prepare("INSERT INTO executivedirectors (schooluser_id, school_id) VALUES (?, ?)");
                    $stmt3->bind_param("ii", $schooluser_id, $school_id);
                    $stmt3->execute();
                    $stmt3->close();
                    break;
                case 'Program Director':
                    $school_id = 1; $program_id = 1; // TODO: prompt/select in UI
                    $stmt3 = $conn->prepare("INSERT INTO programdirectors (schooluser_id, school_id, program_id) VALUES (?, ?, ?)");
                    $stmt3->bind_param("iii", $schooluser_id, $school_id, $program_id);
                    $stmt3->execute();
                    $stmt3->close();
                    break;
                case 'Internship Officer':
                    $school_id = 1; // TODO: prompt/select in UI
                    $stmt3 = $conn->prepare("INSERT INTO internshipofficers (schooluser_id, school_id) VALUES (?, ?)");
                    $stmt3->bind_param("ii", $schooluser_id, $school_id);
                    $stmt3->execute();
                    $stmt3->close();
                    break;
                case 'Student Intern':
                    $program_id = 1; // TODO: prompt/select in UI
                    $stmt3 = $conn->prepare("INSERT INTO interns (schooluser_id, program_id) VALUES (?, ?)");
                    $stmt3->bind_param("ii", $schooluser_id, $program_id);
                    $stmt3->execute();
                    $stmt3->close();
                    break;
            }
        }
        $stmt = $conn->prepare("UPDATE users SET user_first_name=?, user_last_name=?, user_role=? WHERE user_id=?");
        $stmt->bind_param("sssi", $first, $last, $role, $id);
        if ($stmt->execute()) {
            echo 'User updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Delete user
    if (isset($_POST['delete_user_id'])) {
        $id = intval($_POST['delete_user_id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'User deleted successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Edit school
    if (isset($_POST['edit_school_id'])) {
        $id = intval($_POST['edit_school_id']);
        $name = $_POST['edit_school_name'] ?? '';
        if ($name === '') {
            echo 'Error: School name is required.';
            exit;
        }
        $stmt = $conn->prepare("UPDATE schools SET school_name=? WHERE school_id=?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            echo 'School updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Delete school
    if (isset($_POST['delete_school_id'])) {
        $id = intval($_POST['delete_school_id']);
        $stmt = $conn->prepare("DELETE FROM schools WHERE school_id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'School deleted successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Edit program
    if (isset($_POST['edit_program_id'])) {
        $id = intval($_POST['edit_program_id']);
        $name = $_POST['edit_program_name'] ?? '';
        if ($name === '') {
            echo 'Error: Program name is required.';
            exit;
        }
        $stmt = $conn->prepare("UPDATE programs SET program_name=? WHERE program_id=?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            echo 'Program updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Delete program
    if (isset($_POST['delete_program_id'])) {
        $id = intval($_POST['delete_program_id']);
        $stmt = $conn->prepare("DELETE FROM programs WHERE program_id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'Program deleted successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Edit company
    if (isset($_POST['edit_company_id'])) {
        $id = intval($_POST['edit_company_id']);
        $name = $_POST['edit_company_name'] ?? '';
        if ($name === '') {
            echo 'Error: Company name is required.';
            exit;
        }
        $stmt = $conn->prepare("UPDATE companies SET company_name=? WHERE company_id=?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            echo 'Company updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Delete company
    if (isset($_POST['delete_company_id'])) {
        $id = intval($_POST['delete_company_id']);
        $stmt = $conn->prepare("DELETE FROM companies WHERE company_id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'Company deleted successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Edit department
    if (isset($_POST['edit_department_id'])) {
        $id = intval($_POST['edit_department_id']);
        $name = $_POST['edit_department_name'] ?? '';
        if ($name === '') {
            echo 'Error: Department name is required.';
            exit;
        }
        $stmt = $conn->prepare("UPDATE departments SET department_name=? WHERE department_id=?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            echo 'Department updated successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
    // Delete department
    if (isset($_POST['delete_department_id'])) {
        $id = intval($_POST['delete_department_id']);
        $stmt = $conn->prepare("DELETE FROM departments WHERE department_id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'Department deleted successfully.';
        } else {
            echo 'Error: ' . $stmt->error;
        }
        $stmt->close();
        exit;
    }
}

$conn->close();
?>