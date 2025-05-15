<?php
include '../../../database/database.php';

$internship_id = $_POST["internship_id"] ?? null;
$email = $_POST["email"] ?? null;
$first_name = $_POST["first_name"] ?? null;
$last_name = $_POST["last_name"] ?? null;
$gender = $_POST["gender"] ?? null;
$birthdate = $_POST["birthdate"] ?? null;
$address = $_POST["address"] ?? null;
$description = $_POST["update_description"] ?? "";

if (!$internship_id || !$email || !$first_name || !$last_name) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields: internship_id, email, first_name, or last_name"]);
    exit;
}

// Step 1: Get intern_id from internship_id
$intern_id = null;
$stmt0 = $mysqli->prepare("SELECT intern_id FROM internships WHERE internship_id = ?");
$stmt0->bind_param("i", $internship_id);
$stmt0->execute();
$stmt0->bind_result($intern_id);
$stmt0->fetch();
$stmt0->close();

if (!$intern_id) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid internship_id — no matching internship found"]);
    exit;
}

// Step 2: Create a new user (users table)
$stmt1 = $mysqli->prepare("INSERT INTO users (user_first_name, user_last_name, user_email, user_role, user_date_created, user_date_updated, user_is_archived) VALUES (?, ?, ?, 'intern', NOW(), NOW(), 0)");
$stmt1->bind_param("sss", $first_name, $last_name, $email);
if (!$stmt1->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert user"]);
    exit;
}
$new_user_id = $stmt1->insert_id;
$stmt1->close();

// Step 3: Link new user in school_users table (you'll need to decide or pass schooluser_id, here assuming you create a record)
// Assuming you have a schooluser_id or you want to insert one, example insert:

$stmt2 = $mysqli->prepare("INSERT INTO school_users (user_id) VALUES (?)");
$stmt2->bind_param("i", $new_user_id);
if (!$stmt2->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert school_user"]);
    exit;
}
$schooluser_id = $stmt2->insert_id;
$stmt2->close();

// Step 4: Update interns table with this schooluser_id for that intern_id
$stmt3 = $mysqli->prepare("UPDATE interns SET schooluser_id = ? WHERE intern_id = ?");
$stmt3->bind_param("ii", $schooluser_id, $intern_id);
if (!$stmt3->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update intern record"]);
    exit;
}
$stmt3->close();

// Step 5: Insert user update note
$stmt4 = $mysqli->prepare("INSERT INTO user_updates (user_id, update_type, update_description) VALUES (?, 'Add User', ?)");
$stmt4->bind_param("is", $new_user_id, $description);
if (!$stmt4->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to insert user update record"]);
    exit;
}
$stmt4->close();

echo json_encode(["success" => true]);
?>
