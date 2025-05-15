<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../../database/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $internship_id = $_POST["internship_id"];
    $email = $_POST["Email"];
    $name = $_POST["Name"];
    $gender = $_POST["Gender"];
    $birthdate = $_POST["Birthdate"];
    $address = $_POST["Address"];
    $description = $_POST["update_description"];

    if (!$internship_id || !$description) {
        http_response_code(400);
        echo json_encode(["error" => "Missing internship_id or update_description"]);
        exit;
    }

    // 🧠 Step 1: Get user_id from internship_id
    $user_id = null;
    $stmt0 = $mysqli->prepare("
        SELECT su.user_id
        FROM internships i
        JOIN interns it ON i.intern_id = it.intern_id
        JOIN school_users su ON it.schooluser_id = su.schooluser_id
        WHERE i.internship_id = ?
    ");
    $stmt0->bind_param("i", $internship_id);
    $stmt0->execute();
    $stmt0->bind_result($user_id);
    $stmt0->fetch();
    $stmt0->close();

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid internship_id — no matching user found"]);
        exit;
    }

    // 💥 Step 2: Split name into last, first
    $nameParts = explode(",", $name);
    $last_name = trim($nameParts[0]);
    $first_name = trim($nameParts[1]);

    // 🌍 Step 3: Parse address into city, province, postal, country
    $city = $province = $postal = $country = "";
    $addressParts = explode(",", $address);
    if (count($addressParts) >= 4) {
        $city = trim($addressParts[0]);
        $province = trim($addressParts[1]);
        $postal = trim($addressParts[2]);
        $country = trim($addressParts[3]);
    }

    try {
        $mysqli->begin_transaction();

        // ✏️ Update `users`
        $stmt1 = $mysqli->prepare("UPDATE users SET user_first_name = ?, user_last_name = ?, user_email = ?, user_date_updated = NOW() WHERE user_id = ?");
        $stmt1->bind_param("sssi", $first_name, $last_name, $email, $user_id);
        $stmt1->execute();

        // ✏️ Update `interns` info
        $stmt2 = $mysqli->prepare("
            UPDATE interns 
            JOIN school_users ON interns.schooluser_id = school_users.schooluser_id 
            SET intern_birthdate = ?, intern_gender = ?, 
                intern_city = ?, intern_province_or_state = ?, intern_postal_code = ?, intern_country = ?
            WHERE school_users.user_id = ?
        ");
        $stmt2->bind_param("ssssssi", $birthdate, $gender, $city, $province, $postal, $country, $user_id);
        $stmt2->execute();

        // 🪵 Log the update
        $type = "Manual Edit";
        $stmt3 = $mysqli->prepare("INSERT INTO user_updates (user_id, update_type, update_description) VALUES (?, ?, ?)");
        $stmt3->bind_param("iss", $user_id, $type, $description);
        $stmt3->execute();

        $mysqli->commit();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        $mysqli->rollback();
        http_response_code(500);
        echo json_encode(["error" => "Update failed", "details" => $e->getMessage()]);
    }
}
