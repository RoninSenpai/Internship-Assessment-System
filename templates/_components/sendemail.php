<?php
    include '../../database/database.php';

    file_put_contents("log.txt", "Request method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    file_put_contents("log.txt", "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);    
    file_put_contents("debug_log.txt", "Reached PHP script at " . date("H:i:s") . "\n", FILE_APPEND);

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    echo "PHP FILE IS WORKING, BURUNYUU~ 🐾";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../vendor/autoload.php';

    $user = htmlspecialchars($_GET['user'] ?? 'guest');
    $role = $_GET['role'] ?? 'none';

    echo "\n\nWelcome, $user! You're here as a $role\n";

    // if (isset($_GET['status']) && $_GET['status'] === 'sent') {
    //     echo 'Email sent!';
    // }

    // $recepientemail = "jvaquino@student.apc.edu.ph";
    // echo $_POST['email'];

    echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
    echo "POST send_email: " . (isset($_POST['send_email']) ? $_POST['send_email'] : 'NOT SET') . "\n";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
        // validate email exists FIRST, or crash like a Windows ME
        if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $recepientemail = $_POST['email']; // 💌 THE REAL EMAIL
            // $recepientemail = 'ramsinternshipassessmentsystem@gmail.com'; // 💌 THE REAL EMAIL
            echo "Email: $recepientemail\n";
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 2; // or 3 for even MORE chaos
            // $mail->Debugoutput = function($str, $level) {
            //     file_put_contents('smtp_debug.log', "SMTP [$level]: $str\n", FILE_APPEND);
            // };
            
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ramsinternshipassessmentsystem@gmail.com';
                $mail->Password = 'fwzx meft avbo sroe';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
    
                $mail->setFrom('ramsinternshipassessmentsystem@gmail.com', 'rias');
                $mail->addAddress('ramsinternshipassessmentsystem@gmail.com'); // 👈💯 Don't touch it later
                $mail->Subject = 'Your Special Link';
    
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
                echo "Email: $recepientemail\n";
                $stmt->bind_param("s", $recepientemail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    echo "💩 User not found or not linked with schooluser_id.";
                    exit;
                }

                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];
                $schooluser_id = $row['schooluser_id'];

                // 🧙 Generate OTP
                $otp_code = rand(100000, 999999);
                $created_at = date('Y-m-d H:i:s');
                $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                $is_used = 0;

                // Insert OTP into DB
                $otp_stmt = $mysqli->prepare("INSERT INTO otps (schooluser_id, otp_code, otp_date_created, otp_date_expiry, otp_is_used) 
                                            VALUES (?, ?, ?, ?, ?)");
                $otp_stmt->bind_param("isssi", $schooluser_id, $otp_code, $created_at, $expires_at, $is_used);

                if (!$otp_stmt->execute()) {
                    echo "🥴 OTP insert failed: " . $mysqli->error;
                    exit;
                }

                // 🦊 Compose the link
                $link = "$ipaddress/rias/templates/supervisor/index.php?otp=$otp_code";

                $mail->Body = "Here is your otp: $otp_code";

                // 💌 Send the email
                if ($mail->send()) {
                    echo "📨 Email sent successfully!";
                } else {
                    echo "🧨 Email sending failed: " . $mail->ErrorInfo;
                }
    
                // Purge the post
                // header("Location: ?user=" . urlencode($user) . "&role=" . urlencode($role) . "&status=sent");
                exit();
            } catch (Exception $e) {
                echo "💀 PHPMailer DIED! Reason: {$mail->ErrorInfo}";
                file_put_contents("log.txt", "PHPMailer error: {$mail->ErrorInfo}\n", FILE_APPEND);
            }
        } else {
            echo 'Invalid or missing email address, you adorable failure. 💀';
        }
    }
    

    exit(); // Prevent further execution after
?>

    <!-- Form to submit and email the link -->
    <!-- <form action="?user=<?= urlencode($user) ?>&role=<?= urlencode($role) ?>" method="POST">
        <button type="submit" name="send_email">Send Me The Link!</button>
    </form>

    <script>
        // Prevent default form submission and use fetch for AJAX
        document.getElementById('emailForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from reloading the page

            const formData = new FormData(this);

            // Use fetch to send the form data without page refresh
            fetch('', {  // Submit to the current page
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Optionally, show a message to the user
                alert(data);  // You can remove this alert for a cleaner user experience
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong!');
            });
        });
    </script> -->
