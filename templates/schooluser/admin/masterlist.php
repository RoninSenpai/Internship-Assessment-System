<?php
// Show errors (turn off on production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection
include '../../../database/database.php'; // Adjust this path if needed

// Fetch schools
$query = "SELECT school_id, school_name FROM schools ORDER BY school_id ASC";
$result = $mysqli->query($query);

// Create an array to store schools
$schools = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
} else {
    $error = "No schools found or query failed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Masterlist - Schools</title>
</head>
<body>
    <h1>🎓 School List</h1>

    <?php if (!empty($schools)): ?>
        <ul>
            <?php foreach ($schools as $school): ?>
                <li>
                    <strong>ID:</strong> <?= htmlspecialchars($school['school_id']) ?> -
                    <strong>Name:</strong> <?= htmlspecialchars($school['school_name']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color:red;"><?= $error ?? "Unknown error." ?></p>
    <?php endif; ?>
</body>
</html>
