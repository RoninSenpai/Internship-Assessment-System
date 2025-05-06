<?php
session_start();
include '../../../database/database.php';

// Validate session and user role
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    http_response_code(403);
    echo "<div style='text-align:center; font-size:24px; color:red;'>Access Denied</div>";
    exit;
}

$token = $_SESSION['user_id'];

// Fetch user first name
$stmt = $mysqli->prepare("SELECT user_first_name FROM users WHERE user_id = ?");
$stmt->bind_param("i", $token);
$stmt->execute();
$result = $stmt->get_result();

$userFirstName = "Unknown User";
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userFirstName = htmlspecialchars($row['user_first_name']);
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>
<body>
    <div class="admin-container">
        <header class="top-info">
            <h1>Welcome, <?= $userFirstName ?>!</h1>
            <div style="text-align:right; margin-bottom:20px;">
                <div class="dropdown" style="display:inline-block;position:relative;">
                    <button id="registerDropdownBtn" style="background:#213B9A;color:#fff;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;font-size:16px;">Register ▼</button>
                    <div id="registerDropdownMenu" style="display:none;position:absolute;right:0;top:100%;background:#fff;border:1px solid #ccc;border-radius:5px;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:2000;min-width:200px;">
                        <div class="dropdown-item" data-modal="user" style="padding:10px 20px;cursor:pointer;">Register User</div>
                        <div class="dropdown-item" data-modal="school" style="padding:10px 20px;cursor:pointer;">Register School</div>
                        <div class="dropdown-item" data-modal="program" style="padding:10px 20px;cursor:pointer;">Register Program</div>
                        <div class="dropdown-item" data-modal="company" style="padding:10px 20px;cursor:pointer;">Register Company</div>
                        <div class="dropdown-item" data-modal="department" style="padding:10px 20px;cursor:pointer;">Register Department</div>
                    </div>
                </div>
                <div class="dropdown" style="display:inline-block;position:relative;">
                    <button id="manageDropdownBtn" style="background:#34c9ad;color:#fff;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;font-size:16px;margin-left:10px;">Manage ▼</button>
                    <div id="manageDropdownMenu" style="display:none;position:absolute;right:0;top:100%;background:#fff;border:1px solid #ccc;border-radius:5px;box-shadow:0 2px 8px rgba(0,0,0,0.15);z-index:2000;min-width:200px;">
                        <div class="dropdown-item" data-manage="users" style="padding:10px 20px;cursor:pointer;">Manage Users</div>
                        <div class="dropdown-item" data-manage="schools" style="padding:10px 20px;cursor:pointer;">Manage Schools</div>
                        <div class="dropdown-item" data-manage="programs" style="padding:10px 20px;cursor:pointer;">Manage Programs</div>
                        <div class="dropdown-item" data-manage="companies" style="padding:10px 20px;cursor:pointer;">Manage Companies</div>
                        <div class="dropdown-item" data-manage="departments" style="padding:10px 20px;cursor:pointer;">Manage Departments</div>
                    </div>
                </div>
            </div>
        </header>

        <section class="role-stats">
            <div class="stat"><span>Total Users</span><strong><br>214</strong></div>
            <div class="stat"><span>Student Intern</span><strong><br>130</strong></div>
            <div class="stat"><span>Internship Officer</span><strong><br>1</strong></div>
            <div class="stat"><span>Program Director</span><strong><br>36</strong></div>
            <div class="stat"><span>Executive Director</span><strong><br>5</strong></div>
            <div class="stat"><span>Industry Partner</span><strong><br>42</strong></div>
        </section>

        <section class="calendar-section">
            <h2>Internship Calendar Events for the Academic Year <strong>2024-2025</strong></h2>
            
            <div id="calendar" style="max-width: 100%; margin-bottom: 1rem; padding: 1rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);"></div>

            <table>
                <thead>
                    <tr><th>Date</th><th>Event</th></tr>
                </thead>
                <tbody id="event-table-body">
                </tbody>
            </table>

            <div class="calendar-options">
                <button id="openSetEvent">📅 Set Event</button>
                <a href="#" id="openChangeYear">📅 Change Academic Year</a>
            </div>
        </section>

        <section class="activity-login-section">
            <div class="log-activity">
                <h3>Recent Log Activity</h3>
                <div class="tags">
                    <span class="tag green">Student Intern   25</span>
                    <span class="tag blue">Program Director   7</span>
                    <span class="tag pink">Internship Officer   5</span>
                    <span class="tag orange">Industry Partner   3</span>
                    <span class="tag yellow">Executive Director   2</span>
                    <span class="tag gray">Admin   4</span>
                </div>

                <ul class="logs">
                    <li>
                        <span class="log-dot">📌</span>
                        Internship Officer changed the master-list
                        <small>About a minute ago - 03/12/2025</small>
                    </li>
                    <li>
                        <span class="log-dot">📌</span>
                        CpE Program director commented on the student outcomes
                        <small>About a month ago - 02/12/2025</small>
                    </li>
                </ul>
            </div>

            <div class="user-logins">
                <h3>User Logins</h3>
                <canvas id="loginChart" width="400" height="250"></canvas>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateDateTime() {
                const now = new Date();
                const options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' };
                const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                document.getElementById('current-date').textContent = now.toLocaleDateString(undefined, options);
                document.getElementById('current-datetime').textContent = now.toLocaleTimeString(undefined, timeOptions);
            }
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>

    <script>
        const ctx = document.getElementById('loginChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['PD', 'IO', 'SI', 'EXD', 'IP'],
                datasets: [{
                    label: 'INTERN1',
                    data: [25, 10, 50, 30, 60],
                    backgroundColor: '#f15a5a'
                }, {
                    label: 'INTERN2',
                    data: [30, 15, 55, 35, 65],
                    backgroundColor: '#34c9ad'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarEl = document.getElementById('calendar');
            const eventTableBody = document.getElementById('event-table-body');
            const modal = document.getElementById('setEventModal');
            const openBtn = document.getElementById('openSetEvent');
            const closeBtn = document.getElementById('closeSetEvent');
            const eventForm = document.getElementById('eventForm');
            const titleInput = document.getElementById('eventTitle');
            const dateInput = document.getElementById('eventDate');

            const openChangeBtn = document.getElementById('openChangeYear');
            const closeChangeBtn = document.getElementById('closeChangeYear');
            const changeYearModal = document.getElementById('changeYearModal');
            const yearForm = document.getElementById('yearForm');
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const yearHeader = document.querySelector('.calendar-section h2 strong');
            const warning = document.getElementById('yearWarning');

            let events = [];

            const storedStart = localStorage.getItem('startDate');
            const storedEnd = localStorage.getItem('endDate');
            if (storedStart && storedEnd) {
                yearHeader.textContent = formatAcademicYear(storedStart, storedEnd);
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                events: events,
            });

            calendar.render();

            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                titleInput.focus();
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                eventForm.reset();
            });

            eventForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const title = titleInput.value.trim();
                const date = dateInput.value;

                if (title && date) {
                    const newEvent = { title, start: date, color: '#e2f5dc' };
                    events.push(newEvent);
                    calendar.addEvent(newEvent);
                    renderEventTable(events);
                    modal.classList.add('hidden');
                    eventForm.reset();
                }
            });

            openChangeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                changeYearModal.classList.remove('hidden');
            });

            closeChangeBtn.addEventListener('click', () => {
                changeYearModal.classList.add('hidden');
            });

            yearForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const start = startDateInput.value;
                const end = endDateInput.value;

                const startYear = new Date(start).getFullYear();
                const endYear = new Date(end).getFullYear();

                if (startYear === endYear) {
                    warning.style.display = 'block';
                    return;
                }

                warning.style.display = 'none';
                localStorage.setItem('startDate', start);
                localStorage.setItem('endDate', end);
                yearHeader.textContent = formatAcademicYear(start, end);
                changeYearModal.classList.add('hidden');
            });

            function formatAcademicYear(start, end) {
                const startYear = new Date(start).getFullYear();
                const endYear = new Date(end).getFullYear();
                return `${startYear}-${endYear}`;
            }

            function renderEventTable(events) {
                eventTableBody.innerHTML = '';
                const sorted = [...events].sort((a, b) => new Date(a.start) - new Date(b.start));
                sorted.forEach(evt => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(evt.start).toLocaleDateString(undefined, { month: 'long', day: 'numeric' })}</td>
                        <td>${evt.title}</td>
                    `;
                    row.style.backgroundColor = evt.color || '#f9f9f9';
                    eventTableBody.appendChild(row);
                });
            }
        });
    </script>
</body>
</html>

