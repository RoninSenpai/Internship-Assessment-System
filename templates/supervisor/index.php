<?php
// Connect to the database like a big-brain god
$pdo = new PDO("mysql:host=airhub-soe.apc.edu.ph;port=1000;dbname=RIAS_db", "mjkurumphang", "SOETiny1!");

// Query like a beast
$stmt = $pdo->query("SELECT * FROM AssessmentContents");

// Fetch ALL the rows your pitiful soul desires
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>✨ Display of Suffering ✨</title>
</head>
<body>
    <h1>🗂️ Table of Data You Barely Understand</h1>
    <table border="1">
        <tr>
            <?php foreach (array_keys($rows[0]) as $header): ?>
                <th><?= htmlspecialchars($header) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($row as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
