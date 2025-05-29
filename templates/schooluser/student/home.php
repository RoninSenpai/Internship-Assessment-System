<?php
    session_start();
    include '../../../database/database.php';

    $token = $_SESSION['user_id'] ?? '';
    $user_role = $_SESSION['user_roles'] ?? '';

    // echo $_SESSION['user_role'];
    // echo $_SESSION['user_role'] != 'Student Intern';
    if (!$token || $_SESSION['user_roles'] != 'Student Intern') {
        echo "hi";
        http_response_code(404);
        echo "<div id='error-code'>404</div>";
        exit;
    }

    $stmt = $mysqli->prepare("SELECT user_first_name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    $userFirstName = "Unknown Goblin";

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userFirstName = htmlspecialchars($row['user_first_name']); // Always sanitize! 🔪
    }

    $stmt->close();
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .calendar-table th,
        .calendar-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .calendar-table th {
            background: #f1f1f1;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4">

    <div class="w-full bg-white p-10 rounded-xl card-shadow">
        <div class="flex justify-between items-start flex-wrap">
            <h1 class="text-4xl font-semibold">Good Morning <?php echo $userFirstName; ?>!</h1>
            <p class="text-md text-gray-500">Monday, December 16, 2024</p>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-semibold mb-4">Internship Calendar Events for the Academic Year <span class="font-bold">2024-2025</span></h2>
            <table class="calendar-table text-base">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>December 16</td>
                        <td>Internship consultation</td>
                    </tr>
                    <tr class="bg-gray-100">
                        <td>December 31</td>
                        <td>Monthly Report</td>
                    </tr>
                    <tr>
                        <td>January 30</td>
                        <td>Monthly Report</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-10 bg-white p-6 border rounded-xl flex flex-wrap items-center gap-6">
            <div class="border-4 border-yellow-400 rounded-xl p-6 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 1116.88 6.196a7 7 0 01-11.758 11.608z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-2xl"><?php echo $userFirstName; ?></h3>
                <p class="text-md mt-1">Computer Engineering Student Intern<br>Year/s 2024-2025</p>
                <div class="mt-3 text-blue-600 text-md space-x-6">
                    <a href="#" class="hover:underline">Show Photo</a>
                    <a href="#" class="hover:underline">View Complete Profile</a>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between flex-wrap text-md text-blue-600">
            <a href="#" class="hover:underline">You previously logged in on Saturday, November 30, 2024 at 11:59 PM</a>
            <a href="#" class="hover:underline">Go to your last visited page: Master List</a>
        </div>
    </div>

</body>
</html>
