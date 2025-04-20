<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists and is not archived
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_archived = '0' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Successful login
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            echo "success";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found or archived";
    }
    $stmt->close();
} else {
    echo "Invalid request method";
}
$conn->close();
?>