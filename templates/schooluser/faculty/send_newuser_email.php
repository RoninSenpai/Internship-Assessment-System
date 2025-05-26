<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../../vendor/autoload.php';
    include '../../../database/database.php';

    function sendPasswordSetupEmail($email, $schooluser_id) {
        global $mysqli, $ipaddress;

        $token = bin2hex(random_bytes(16));
        $created_at = date('Y-m-d H:i:s');
        $expiry_date = date('Y-m-d H:i:s', strtotime('+15 minutes')); // 💣 Longer than reset
        $is_used = 0;

        $stmt = $mysqli->prepare("INSERT INTO passwordresets (schooluser_id, passreset_token, passreset_date_created, passreset_date_expiry, passreset_is_used) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $schooluser_id, $token, $created_at, $expiry_date, $is_used);

        if (!$stmt->execute()) {
            error_log("Token insert failed: " . $mysqli->error);
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ramsinternshipassessmentsystem@gmail.com';
            $mail->Password = 'fwzx meft avbo sroe';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
    
            $mail->setFrom('ramsinternshipassessmentsystem@gmail.com', 'rias');
            $mail->addAddress('ramsinternshipassessmentsystem@gmail.com'); // Send to the user
            $mail->Subject = 'Set Your Password';

            $link = "http://$ipaddress/rias/templates/schooluser/login/changepassword.php?token=$token";
            $mail->Body = "Welcome to the Internship Assessment System $email! 🐣\n\nClick the link below to set your password:\n\n$link\n\nThis link will expire in 15 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
?>
