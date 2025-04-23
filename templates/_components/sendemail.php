<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

$user = htmlspecialchars($_GET['user'] ?? 'guest');
$role = $_GET['role'] ?? 'none';

echo "Welcome, $user! You're here as a $role.<br>";

if (isset($_GET['status']) && $_GET['status'] === 'sent') {
    echo 'Email sent!';
}

// Replace with the actual recipient email address
$recepientemail = 'mjkurumphang@student.apc.edu.ph';

// Open Command Prompt, type:
// ipconfig
// Look for the line like:
// IPv4 Address. . . . . . . . . . . : 192.168.0.9
// Replace with the actual IP address of the server
$ipaddress = '192.168.0.9';

// localhost makes it work on your local machine
// ipaddress makes it work on your local network


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ramsinternshipassessmentsystem@gmail.com';
        $mail->Password = 'efir bldq mzlz ebrp';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('ramsinternshipassessmentsystem@gmail.com', 'noreply');
        $mail->addAddress($recepientemail);
        $mail->Subject = 'Your Special Link';

        $link = "http://" . $ipaddress . "/Internship-Assessment-System/templates/supervisor/index.php?user=" . urlencode($user) . "&role=" . urlencode($role);
        $mail->Body = "Here is your link: $link";

        $mail->send();

        // NYAH REDIRECT TO PURGE THE POST
        header("Location: ?user=" . urlencode($user) . "&role=" . urlencode($role) . "&status=sent");
        exit();
    } catch (Exception $e) {
        echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
    }
}

?>

<!-- Form to submit and email the link -->
<form action="?user=<?= urlencode($user) ?>&role=<?= urlencode($role) ?>" method="POST">
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
</script>
