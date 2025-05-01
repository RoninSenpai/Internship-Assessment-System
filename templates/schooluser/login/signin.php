<?php
    session_start();
    include '../../../database/database.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // echo "\n";
        // echo $inputOtp . "\n";

        if ($_POST['otp'] != 0) {
            $inputOtp = trim($_POST['otp']);
            // echo "first if \n";
            // Prepare statement to check OTP validity
            $otpStmt = $mysqli->prepare("SELECT * FROM otps 
                WHERE otp_code = ?
                LIMIT 1");
            $otpStmt->bind_param("s", $inputOtp);
            $otpStmt->execute();
            $otpResult = $otpStmt->get_result();

            if ($otpResult->num_rows === 1) {
                // echo "second if\n";
                $otpRow = $otpResult->fetch_assoc();

                // OTP is valid, now fetch user data via schooluser_id
                $schooluserId = $otpRow['schooluser_id'];
                $userStmt = $mysqli->prepare("SELECT u.*, s.schooluser_password 
                    FROM users u
                    JOIN school_users s ON u.user_id = s.user_id
                    WHERE s.schooluser_id = ? AND u.user_is_archived = 0
                    LIMIT 1");
                $userStmt->bind_param("i", $schooluserId);
                $userStmt->execute();
                $userResult = $userStmt->get_result();

                if ($userResult->num_rows === 1) {
                    // echo "third if\n";
                    $user = $userResult->fetch_assoc();

                    // Invalidate OTP
                    $invalidateStmt = $mysqli->prepare("UPDATE otps SET otp_is_used = 1 WHERE otp_id = ?");
                    $invalidateStmt->bind_param("i", $otpRow['otp_id']);
                    $invalidateStmt->execute();
                    $invalidateStmt->close();

                    // Set up session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_role'] = $user['user_role'];

                    echo json_encode([
                        'status' => 'success',
                        'role' => $user['user_role']
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
        
        // First check if user exists in users table
        $stmt = $mysqli->prepare("SELECT u.*, s.schooluser_password, s.schooluser_id 
                            FROM users u 
                            JOIN school_users s ON u.user_id = s.user_id 
                            WHERE u.user_email = ? AND u.user_is_archived = 0 
                            LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Use password_verify to check the hashed password
            if (password_verify($password, $user['schooluser_password'])) {
            // if ($password == $user['schooluser_password']) {
                // Password is correct, create session
                // $_SESSION['token'] = bin2hex(random_bytes(32)); // Generate a more secure token
                $_SESSION['user_id'] = $user['user_id'];
                // $_SESSION['schooluser_id'] = $user['schooluser_id'];
                $_SESSION['user_role'] = $user['user_role'];
                // $_SESSION['name'] = $user['user_first_name'] . ' ' . $user['user_last_name'];
                
                // Log the successful login in login_logs table
                // $log_stmt = $mysqli->prepare("INSERT INTO login_logs (user_id) VALUES (?)");
                // $log_stmt->bind_param("i", $user['user_id']);
                // $log_stmt->execute();
                // $log_stmt->close();

                echo json_encode([
                    'status' => 'success',
                    'role' => $user['user_role']
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
