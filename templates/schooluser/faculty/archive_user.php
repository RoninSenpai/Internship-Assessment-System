<?php
include '../../../database/database.php';

$internship_id = $_POST['internship_id'] ?? null;
$update_description = $_POST['update_description'] ?? '';

if (!$internship_id || !$update_description) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing internship ID or reason']);
    exit;
}

$mysqli->begin_transaction();

try {
    // Step 1: Get the user_id from the internship_id
    $stmt = $mysqli->prepare("
        SELECT u.user_id 
        FROM users u
        JOIN school_users su ON su.user_id = u.user_id
        JOIN interns i ON i.schooluser_id = su.schooluser_id
        JOIN internships it ON it.intern_id = i.intern_id
        WHERE it.internship_id = ?
    ");
    $stmt->bind_param("i", $internship_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        throw new Exception("User not found for internship_id = $internship_id");
    }

    // Step 2: Archive user
    $stmt = $mysqli->prepare("UPDATE users SET user_is_archived = 1 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Step 3: Insert log into user_updates table
    $update_type = "ARCHIVE";
    $stmtLog = $mysqli->prepare("
        INSERT INTO user_updates (user_id, update_type, update_description)
        VALUES (?, ?, ?)
    ");
    $stmtLog->bind_param("iss", $user_id, $update_type, $update_description);
    $stmtLog->execute();
    $stmtLog->close();

    $mysqli->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $mysqli->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error archiving: ' . $e->getMessage()]);
}
