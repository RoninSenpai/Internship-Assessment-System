<?php
session_start();
include '../../../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['otp'] != 0) {
        $inputOtp = trim($_POST['otp']);

        $otpStmt = $mysqli->prepare("SELECT * FROM otps WHERE otp_code = ? LIMIT 1");
        $otpStmt->bind_param("s", $inputOtp);
        $otpStmt->execute();
        $otpResult = $otpStmt->get_result();

        if ($otpResult->num_rows === 1) {
            $otpRow = $otpResult->fetch_assoc();
            $schooluserId = $otpRow['schooluser_id'];

            $userStmt = $mysqli->prepare("
                SELECT u.*, s.schooluser_password, r.role_name 
                FROM users u
                JOIN school_users s ON u.user_id = s.user_id
                JOIN user_roles r ON u.user_id = r.user_id
                WHERE s.schooluser_id = ? AND u.user_is_archived = 0
                LIMIT 1
            ");
            $userStmt->bind_param("i", $schooluserId);
            $userStmt->execute();
            $userResult = $userStmt->get_result();

            if ($userResult->num_rows === 1) {
                $user = $userResult->fetch_assoc();

                $invalidateStmt = $mysqli->prepare("UPDATE otps SET otp_is_used = 1 WHERE otp_id = ?");
                $invalidateStmt->bind_param("i", $otpRow['otp_id']);
                $invalidateStmt->execute();
                $invalidateStmt->close();

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_roles'] = $user['role_name'];

                echo json_encode([
                    'status' => 'success',
                    'role' => $user['role_name']
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'User not found for this OTP.'
                ]);
            }
            $userStmt->close();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid or expired OTP.'
            ]);
        }
        $otpStmt->close();
        exit;
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];


$stmt = $mysqli->prepare("
    SELECT u.*, s.schooluser_password, s.schooluser_id, r.role_name 
    FROM users u 
    JOIN school_users s ON u.user_id = s.user_id 
    JOIN user_roles r ON u.user_id = r.user_id
    WHERE u.user_email = ? AND u.user_is_archived = 0 
    LIMIT 1
");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['schooluser_password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_roles'] = $user['role_name'];

            echo json_encode([
                'status' => 'success',
                'role' => $user['role_name']
            ]);
        } else {
            error_log("Password verification failed for user: " . $email);
            error_log("Stored hash: " . $user['schooluser_password']);
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid password'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not found or account is archived'
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
$mysqli->close();
?>
