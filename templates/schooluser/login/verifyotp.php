<?php
// verifyotp.php
    include '../../../database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($email) || empty($otp)) {
        echo "missing-fields";
        exit;
    }

    $stmt = $mysqli->prepare("
        SELECT o.otp_id, o.otp_is_used, o.otp_date_expiry
        FROM users u
        INNER JOIN school_users su ON u.user_id = su.user_id
        INNER JOIN otps o ON su.schooluser_id = o.schooluser_id
        WHERE u.user_email = ? AND o.otp_code = ?
        ORDER BY o.otp_date_created DESC
        LIMIT 1
    ");

    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['otp_is_used']) {
            echo "otp-already-used";
        } else if (strtotime($row['otp_date_expiry']) < time()) {
            echo "otp-expired";
        } else {
            // Mark OTP as used
            $update = $mysqli->prepare("UPDATE otps SET otp_is_used = 1 WHERE otp_id = ?");
            $update->bind_param("i", $row['otp_id']);
            $update->execute();
            echo "verified";
        }
    } else {
        echo "invalid-otp";
    }
} else {
    echo "invalid-request";
}
?>
