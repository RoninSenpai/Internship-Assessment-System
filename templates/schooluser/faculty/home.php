<?php
    session_start();
    include '../../../database/database.php';

    $token = $_SESSION['user_id'] ?? '';
    $user_role = $_SESSION['user_roles'] ?? '';

    if (!$token || ($_SESSION['user_roles'] != 'Internship Officer' && $_SESSION['user_roles'] != 'Program Director' && $_SESSION['user_roles'] != 'Executive Director')) {
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
        $userFirstName = htmlspecialchars($row['user_first_name']);
    }

    $stmt->close();
    $mysqli->close();
?>

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Internship Officer Homepage</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="home.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>

<body>
    <input type="checkbox" id="menu-toggle" style="display:none;">

    <div class="main-card">

    

        <!-- Header Row -->
        <div class="header-row">
            <h2 class="main-date">03/12/2025</h2>
            <p class="last-login">
                You previously logged in on Saturday, March 09, 2025 at 01:59 PM
                <span style="margin-left:1rem;font-size:1.1rem;color:#888;">
                    <i class="far fa-clock"></i>
                </span>
                <a href="#" style="margin-left:1rem;font-size:1rem;color:#213B9A;text-decoration:underline;">Go to your last visited page: Master List</a>
            </p>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <p class="stat-value">214</p>
                <p class="stat-label">Total Users</p>
            </div>
            <div class="stat-card">
                <p class="stat-value">130</p>
                <p class="stat-label">Student Intern</p>
            </div>
            <div class="stat-card">
                <p class="stat-value">1</p>
                <p class="stat-label">Internship Officer</p>
            </div>
            <div class="stat-card">
                <p class="stat-value">36</p>
                <p class="stat-label">Program Director</p>
            </div>
            <div class="stat-card">
                <p class="stat-value">5</p>
                <p class="stat-label">Executive Director</p>
            </div>
            <div class="stat-card">
                <p class="stat-value">42</p>
                <p class="stat-label">Industry Partner</p>
            </div>
        </div>

       <!-- Calendar Section -->
<div class="calendar-section">
    <div class="calendar-header">
        <span class="calendar-title">Internship Calendar Events for the Academic Year</span>
        <span id="academicYearLabel" class="calendar-year"></span>
    </div>
    <table class="calendar-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Event</th>
                <th style="width:90px"></th>
            </tr>
        </thead>
        <tbody>
            <!-- Events will be rendered here -->
        </tbody>
    </table>
    <div class="calendar-actions">
        <button class="btn-link" id="setEventBtn">
            <i class="far fa-bell"></i> Set Event
        </button>
        <button class="btn-link" onclick="openYearModal()">
            <i class="far fa-calendar-alt"></i> Change Academic Year
        </button>
    </div>
</div>

        <!-- Modals -->
        <div id="yearModal" class="modal hidden">
            <div class="modal-content">
                <h2 class="dashboard-title">Change Academic Year</h2>
                <label>Start Date</label>
                <input type="date" id="startYearInput" class="input-field" />
                <label>End Date</label>
                <input type="date" id="endYearInput" class="input-field" />
                <div class="flex-row justify-end gap-2">
                    <button onclick="closeYearModal()" class="btn btn-gray">Cancel</button>
                    <button onclick="changeAcademicYear()" class="btn">Save</button>
                </div>
            </div>
        </div>

        <div id="eventModal" class="modal hidden">
            <div class="modal-content">
                <h2 class="dashboard-title">Set Internship Event</h2>
                <label>Start Date</label>
                <input type="date" id="eventStartDate" class="input-field" />
                <label>End Date</label>
                <input type="date" id="eventEndDate" class="input-field" />
                <input type="text" id="eventName" placeholder="Event Name" class="input-field" />
                <div class="flex-row justify-end gap-2">
                    <button onclick="closeEventModal()" class="btn btn-gray">Cancel</button>
                    <button id="addEventBtn" class="btn">Add Event</button>
                </div>
            </div>
        </div>

        <!-- Dashboard Row -->
<div class="dashboard-row">
    <!-- Left Column: INTERN1 + Profile -->
    <div style="display: flex; flex-direction: column; gap: 2rem; flex: 1 1 0; min-width: 260px;">
        <div class="dashboard-card">
            <h3 class="dashboard-title">INTERN1</h3>
            <div class="dashboard-stats">
                <div>
                    <p class="dashboard-value">10</p>
                    <p class="dashboard-label">Deployed</p>
                </div>
                <div>
                    <p class="dashboard-value">13</p>
                    <p class="dashboard-label">Interview/Waiting</p>
                </div>
                <div>
                    <p class="dashboard-value">3</p>
                    <p class="dashboard-label">Undeployed</p>
                </div>
                <div>
                    <p class="dashboard-value">31</p>
                    <p class="dashboard-label">Unlisted</p>
                </div>
            </div>
        </div>
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-img profile-img-outline">
                <svg width="70" height="70" viewBox="0 0 70 70" fill="none">
                    <circle cx="35" cy="35" r="32" stroke="#FBBF24" stroke-width="4" fill="#FEF9C3"/>
                    <circle cx="35" cy="30" r="13" stroke="#FBBF24" stroke-width="3" fill="none"/>
                    <ellipse cx="35" cy="50" rx="17" ry="10" stroke="#FBBF24" stroke-width="3" fill="none"/>
                </svg>
            </div>
            <div>
                <h4 class="profile-name">Ms. Angel Shire Nagatoro</h4>
                <p class="profile-role">Internship Officer of the year/s 2022 - 2026</p>
                <a href="#" class="profile-link"><i class="fas fa-arrow-up-right-from-square"></i> View Complete Profile</a>
                <br>
                <a href="#" class="profile-link"><i class="fas fa-image"></i> Show Photo</a>
            </div>
        </div>
    </div>
    <!-- Center Column: Progress -->
    <div class="dashboard-card">
        <h3 class="dashboard-title">Progress</h3>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:1rem;font-weight:500;">Evaluation Forms (Industry Partner)</span>
                    <span style="font-size:1rem;color:#f87171;font-weight:700;">Not yet</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:0.5rem;">
                    <span style="color:#213B9A;font-weight:700;">Total Submitted</span>
                    <span style="color:#213B9A;font-weight:700;">40</span>
                    <span style="color:#f87171;font-weight:700;">90</span>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:1rem;font-weight:500;">Monthly</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:0.5rem;">
                    <span style="color:#213B9A;font-weight:700;">Total Submitted</span>
                    <span style="color:#213B9A;font-weight:700;">40</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:0.5rem;">
                    <span style="color:#f87171;font-weight:700;">Accomplishment Report Not yet</span>
                    <span style="color:#f87171;font-weight:700;">90</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:0.5rem;">
                    <span style="color:#f87171;font-weight:700;">Attendance Report Not yet</span>
                    <span style="color:#f87171;font-weight:700;">90</span>
                </div>
                <div class="dashboard-action" style="margin-top:1rem;">
                    <button onclick="notifyRemaining()" class="btn btn-danger">Notify Remaining</button>
                </div>
            </div>
        </div>
    </div>
<!-- Right Column: March 2025 -->
<div class="dashboard-card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <span class="dashboard-title" style="margin-bottom:0;">March 2025</span>
        <span style="font-size:1.2rem;color:#888;"><i class="fas fa-chevron-left"></i> <i class="fas fa-chevron-right"></i></span>
    </div>
    <div style="font-size:1.3rem;font-weight:700;color:#213B9A;margin-bottom:0.5rem;">130 Interns</div>
<label for="excelFile" style="font-size:0.98rem;font-weight:500;margin-bottom:0.5rem;display:block;">Choose a file:</label>
<input type="file" id="excelFile" class="input-file" accept=".xlsx" />
<canvas id="schoolChart" class="chart-canvas" style="margin-top:24px;max-width:400px;"></canvas>
</div>
</div>

    <!-- Scripts -->
<script>
    // --- Academic Year/Event Logic ---
    let currentAcademicYear = "";
    let eventsByYear = JSON.parse(localStorage.getItem("eventsByYear")) || {};

    function openYearModal() {
        document.getElementById("yearModal").classList.remove("hidden");
    }
    function closeYearModal() {
        document.getElementById("yearModal").classList.add("hidden");
    }
    function openEventModal() {
        document.getElementById("eventModal").classList.remove("hidden");
    }
    function closeEventModal() {
        document.getElementById("eventModal").classList.add("hidden");
    }

    function changeAcademicYear() {
        const startDate = document.getElementById("startYearInput").value;
        const endDate = document.getElementById("endYearInput").value;

        if (!startDate || !endDate || new Date(endDate) <= new Date(startDate)) {
            alert("Please enter a valid date range.");
            return;
        }

        const start = new Date(startDate).getFullYear();
        const end = new Date(endDate).getFullYear();

        currentAcademicYear = `${start}-${end}`;
        document.getElementById("academicYearLabel").textContent = currentAcademicYear;

        if (!eventsByYear[currentAcademicYear]) {
            eventsByYear[currentAcademicYear] = [];
        }

        saveEventsToStorage();
        renderEvents();
        closeYearModal();
    }

    function saveEventsToStorage() {
        localStorage.setItem("eventsByYear", JSON.stringify(eventsByYear));
    }

    function renderEvents() {
        const table = document.querySelector(".calendar-table tbody");
        table.innerHTML = "";

        const events = eventsByYear[currentAcademicYear] || [];
        events.forEach((event, index) => {
            let rowClass = '';
            if (event.name.toLowerCase().includes('end')) rowClass = 'event-end';
            if (event.name.toLowerCase().includes('consult')) rowClass = 'event-consult';
            const newRow = document.createElement("tr");
            newRow.className = rowClass;
            newRow.innerHTML = `
                <td>${event.dateRange}</td>
                <td>${event.name}</td>
                <td>
                    <button onclick="editEvent(${index})" class="btn-link">Edit</button>
                    <button onclick="deleteEvent(${index})" class="btn-link btn-link-red">Delete</button>
                </td>
            `;
            table.appendChild(newRow);
        });
    }

    function deleteEvent(index) {
        if (confirm("Are you sure you want to delete this event?")) {
            eventsByYear[currentAcademicYear].splice(index, 1);
            saveEventsToStorage();
            renderEvents();
        }
    }

    function editEvent(index) {
        const event = eventsByYear[currentAcademicYear][index];
        const [startDateStr, endDateStr] = parseDateRange(event.dateRange);

        document.getElementById("eventStartDate").value = startDateStr;
        document.getElementById("eventEndDate").value = endDateStr;
        document.getElementById("eventName").value = event.name;

        openEventModal();

        document.getElementById("addEventBtn").onclick = function () {
            const newStart = document.getElementById("eventStartDate").value;
            const newEnd = document.getElementById("eventEndDate").value || newStart;
            const newName = document.getElementById("eventName").value;

            const dateRange = formatDateRange(newStart, newEnd);
            eventsByYear[currentAcademicYear][index] = { dateRange, name: newName };
            saveEventsToStorage();
            renderEvents();
            closeEventModal();

            document.getElementById("eventStartDate").value = '';
            document.getElementById("eventEndDate").value = '';
            document.getElementById("eventName").value = '';
        };
    }

    function formatDateRange(start, end) {
        const options = { month: 'long', day: 'numeric' };
        const startDate = new Date(start);
        const endDate = new Date(end);

        if (start === end || end === '') {
            return startDate.toLocaleDateString(undefined, options);
        }

        if (startDate.getMonth() === endDate.getMonth()) {
            return `${startDate.toLocaleDateString(undefined, { month: 'long' })} ${startDate.getDate()}–${endDate.getDate()}`;
        }

        return `${startDate.toLocaleDateString(undefined, options)}–${endDate.toLocaleDateString(undefined, options)}`;
    }

    function parseDateRange(rangeText) {
        const parts = rangeText.split("–");
        const todayYear = new Date().getFullYear();

        const start = new Date(`${parts[0]} ${todayYear}`);
        const end = parts[1] ? new Date(`${parts[1]} ${todayYear}`) : start;

        const toISOString = (d) => d.toISOString().split('T')[0];
        return [toISOString(start), toISOString(end)];
    }

    function addEvent() {
        const start = document.getElementById("eventStartDate").value;
        const end = document.getElementById("eventEndDate").value || start;
        const name = document.getElementById("eventName").value;

        if (!start || !name) {
            alert("Please enter at least a start date and event name.");
            return;
        }

        const dateRange = formatDateRange(start, end);

        const eventData = { dateRange, name };
        if (!eventsByYear[currentAcademicYear]) {
            eventsByYear[currentAcademicYear] = [];
        }

        eventsByYear[currentAcademicYear].push(eventData);
        saveEventsToStorage();
        renderEvents();
        closeEventModal();

        addNotification(`New event added: ${name} (${dateRange})`, { type: "event" });
        document.getElementById("eventStartDate").value = '';
        document.getElementById("eventEndDate").value = '';
        document.getElementById("eventName").value = '';
        window.parent.postMessage({
    type: "addNotification",
    message: `New event added: ${title} (${new Date(date).toLocaleDateString(undefined, { month: 'long', day: 'numeric' })})`,
    meta: { type: "event" }
}, "*");
    }

    // Modal open/close handlers
    document.getElementById("setEventBtn").onclick = openEventModal;
    document.getElementById("addEventBtn").onclick = addEvent;

    // --- Notification System ---
    function getNotifications() {
        return JSON.parse(localStorage.getItem("rias_notifications") || "[]");
    }
    function saveNotifications(notifs) {
        localStorage.setItem("rias_notifications", JSON.stringify(notifs));
    }
    function addNotification(message, meta = {}) {
        const notifs = getNotifications();
        notifs.unshift({
            message,
            meta,
            timestamp: Date.now(),
            read: false
        });
        saveNotifications(notifs);
        renderNotifications();
    }
    function renderNotifications() {
        const notifs = getNotifications();
        const list = document.getElementById("notificationList");
        if (!list) return;
        list.innerHTML = "";
        if (notifs.length === 0) {
            list.innerHTML = "<li class='notification-empty'>No notifications yet.</li>";
            return;
        }
        notifs.forEach((notif, idx) => {
            const li = document.createElement("li");
            li.className = "notification-item" + (notif.read ? " read" : "");
            li.innerHTML = `
                <div>
                    <span class="notification-msg">${notif.message}</span>
                    <span class="notification-time">${new Date(notif.timestamp).toLocaleString()}</span>
                </div>
                <button class="notification-dismiss" title="Dismiss">&times;</button>
            `;
            li.querySelector(".notification-dismiss").onclick = (e) => {
                e.stopPropagation();
                notifs.splice(idx, 1);
                saveNotifications(notifs);
                renderNotifications();
            };
            li.onclick = () => {
                notif.read = true;
                saveNotifications(notifs);
                renderNotifications();
            };
            list.appendChild(li);
        });
    }
    const clearBtn = document.getElementById("clearNotificationsBtn");
    if (clearBtn) {
        clearBtn.onclick = () => {
            saveNotifications([]);
            renderNotifications();
        };
    }
    function notifyRemaining() {
        alert("Reminder sent to those who haven’t submitted their evaluation forms.");
    }

    // --- Initial Page Load ---
    function init() {
        // Set default academic year if not set
        if (!currentAcademicYear) {
            const now = new Date();
            const year = now.getFullYear();
            currentAcademicYear = `${year}-${year + 1}`;
        }
        document.getElementById("academicYearLabel").textContent = currentAcademicYear;
        renderEvents();
        renderNotifications();
    }
    window.onload = init;
    
</script>



<script>
  let chart;

  document.getElementById('excelFile').addEventListener('change', handleFile, false);

  function handleFile(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();

    reader.onload = function (e) {
      const data = new Uint8Array(e.target.result);
      const workbook = XLSX.read(data, { type: 'array' });

      const sheet = workbook.Sheets[workbook.SheetNames[0]];
      const json = XLSX.utils.sheet_to_json(sheet);

      const schoolCounts = {};
      let submitted = 0, notYet = 0;

      json.forEach(row => {
        const school = row["School"];
        const status = row["Status"];

        if (school) {
          schoolCounts[school] = (schoolCounts[school] || 0) + 1;
        }

        if (status === "Submitted") submitted++;
        else if (status === "Not yet") notYet++;
      });

      // Set the intern count dynamically
      document.getElementById("internCount").textContent = json.length;

      renderChart(schoolCounts);
      renderStatusList(submitted, notYet);
    };

    reader.readAsArrayBuffer(file);
  }

  function renderChart(data) {
    const ctx = document.getElementById('schoolChart').getContext('2d');
    if (chart) chart.destroy();

    chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: Object.keys(data),
        datasets: [{
          data: Object.values(data),
          backgroundColor: ['#60A5FA', '#FBBF24', '#34D399', '#F87171', '#A78BFA'],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });
  }

  function renderStatusList(submitted, notYet) {
    const list = document.getElementById("statusList");
    if (list) {
      list.innerHTML = `
        <li><strong>Submitted:</strong> ${submitted}</li>
        <li><strong>Not yet:</strong> ${notYet}</li>
      `;
    }
  }
</script>
</body>