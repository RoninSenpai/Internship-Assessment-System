<?php
    include '../../../database/database.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../../vendor/autoload.php';
    
    $ipaddress = '192.168.0.2'; // Change this to your server IP
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
        if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $recepientemail = $_POST['email'];
            
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ramsinternshipassessmentsystem@gmail.com';
                $mail->Password = 'wacd pbka okmt bipn';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
    
                $mail->setFrom('ramsinternshipassessmentsystem@gmail.com', 'rias');
                $mail->addAddress('ramsinternshipassessmentsystem@gmail.com'); // Send to the user
                $mail->Subject = 'Reset Your Password';
    
                // Check if the email exists in the `users` table
                $stmt = $mysqli->prepare("
                    SELECT
                        u.user_id,
                        su.schooluser_id
                    FROM
                        users u
                    INNER JOIN school_users su ON
                        u.user_id = su.user_id
                    WHERE
                        u.user_email = ?
                ");
                $stmt->bind_param("s", $recepientemail);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows === 0) {
                    echo "❌ Email not found or user not linked to school_users table.";
                    exit;
                }
    
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];
                $schooluser_id = $row['schooluser_id'];
    
                // 🔐 Generate unique token
                $token = bin2hex(random_bytes(16));
                $created_at = date('Y-m-d H:i:s');
                
                // 🔥 Set expiry time for 10 minutes from now
                $expiry_date = date('Y-m-d H:i:s', strtotime('+5 minutes')); 
                $is_used = 0;
    
                // Store in passwordresets table with expiry date
                $insert_stmt = $mysqli->prepare("
                    INSERT INTO passwordresets (schooluser_id, passreset_token, passreset_date_created, passreset_date_expiry, passreset_is_used)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $insert_stmt->bind_param("ssssi", $schooluser_id, $token, $created_at, $expiry_date, $is_used);
    
                if (!$insert_stmt->execute()) {
                    echo "💣 Token insert failed: " . $mysqli->error;
                    exit;
                }
    
                // 📨 Prepare the password reset link
                $link = "http://$ipaddress/rias/templates/schooluser/login/forgotpassword.php?token=$token";
                $mail->Body = "Click this link to reset your password:\n\n$link\n\nLink will expire in 5 minutes.";
    
                // 🔥 Send the email
                if ($mail->send()) {
                    echo "✅ Password reset link sent successfully!";
                } else {
                    echo "📉 Failed to send email: " . $mail->ErrorInfo;
                }
    
                exit();
            } catch (Exception $e) {
                echo "💀 Mailer Exception: {$mail->ErrorInfo}";
                file_put_contents("log.txt", "PHPMailer error: {$mail->ErrorInfo}\n", FILE_APPEND);
            }
        } else {
            echo "🚫 Invalid or missing email address!";
        }
    }
    
    exit();
    
?>
