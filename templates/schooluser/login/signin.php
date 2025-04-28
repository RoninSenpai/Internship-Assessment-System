<?php
<<<<<<< HEAD
session_start();
include '../../../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // First check if user exists in users table
    $stmt = $mysqli->prepare("SELECT u.*, s.schooluser_password, s.schooluser_id 
=======
    session_start();
    include '../../../database/database.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        // First check if user exists in users table
        $stmt = $mysqli->prepare("SELECT u.*, s.schooluser_password, s.schooluser_id 
>>>>>>> 9e06e47a3821a44208dc07d4f3e07ad1e743802d
                            FROM users u 
                            JOIN school_users s ON u.user_id = s.user_id 
                            WHERE u.user_email = ? AND u.user_is_archived = 0 
                            LIMIT 1");
<<<<<<< HEAD
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Use password_verify to check the hashed password
        // if (password_verify($password, $user['schooluser_password'])) {
        if ($password == $user['schooluser_password']) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['schooluser_id'] = $user['schooluser_id'];
            $_SESSION['role'] = $user['user_role'];
            $_SESSION['name'] = $user['user_first_name'] . ' ' . $user['user_last_name'];

            // Generate a random session token
            $session_token = bin2hex(random_bytes(32)); // Generate a 64-character token

            // Insert the token into the user_sessions table
            $insert_token_stmt = $mysqli->prepare("INSERT INTO `user_sessions` (`user_id`, `session_token`, `expires_at`) 
                                                  VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
            $insert_token_stmt->bind_param("is", $user['user_id'], $session_token);
            $insert_token_stmt->execute();
            $insert_token_stmt->close();

            // Respond with the session token and user role
            echo json_encode([
                'status' => 'success',
                'role' => $user['user_role'],
                'session_token' => $session_token // Send the session token to the client
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
=======
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Use password_verify to check the hashed password
            // if (password_verify($password, $user['schooluser_password'])) {
            if ($password == $user['schooluser_password']) {
                // Password is correct, create session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['schooluser_id'] = $user['schooluser_id'];
                $_SESSION['role'] = $user['user_role'];
                $_SESSION['name'] = $user['user_first_name'] . ' ' . $user['user_last_name'];
                
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
>>>>>>> 9e06e47a3821a44208dc07d4f3e07ad1e743802d
