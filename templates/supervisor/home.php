<?php
include '../../database/database.php';

// SQL query to join the tables and get the data
$sql = "
    SELECT 
        u.user_first_name, u.user_last_name, u.user_email,
        s.supervisor_job_role, d.department_name, c.company_name
    FROM supervisors s
    INNER JOIN users u ON s.user_id = u.user_id
    INNER JOIN departments d ON s.department_id = d.department_id
    INNER JOIN companies c ON d.company_id = c.company_id
    LIMIT 1
";

$result = $conn->query($sql);

// Initialize variables to store the data
$user_first_name = "Unknown";
$user_last_name = "Unknown";
$user_email = "Unknown";
$supervisor_job_role = "Unknown";
$department_name = "Unknown";
$company_name = "Unknown";

// If the query is successful and data is found
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Assign the fetched data to the variables
    $user_first_name = $row['user_first_name'];
    $user_last_name = $row['user_last_name'];
    $user_email = $row['user_email'];
    $supervisor_job_role = $row['supervisor_job_role'];
    $department_name = $row['department_name'];
    $company_name = $row['company_name'];
}
?>

<head>
  <link rel="stylesheet" href="../../static/css/components.css">
  <link rel="stylesheet" href="home.css">
</head>

<body class="content">
  <h1 id="greeting">Good day, Mr. <?php echo htmlspecialchars($user_last_name); ?>!</h1>

  <p class="date">Wednesday, March 12, 2025</p>
  <script src="../../templates/_components/date.js"></script>

  <div class="box-shadow">
    <div class="content-card">
      <div class="info-card">
        <h2>SUPERVISOR INFORMATION</h2>
        <ul>
          <li><strong>Name: </strong><?php echo htmlspecialchars($user_first_name . " " . $user_last_name); ?></li>
          <li><strong>Email: </strong><span class="censor-email"><?php echo htmlspecialchars($user_email); ?></span></li>

          <script>
            function censorEmail(text) {
              return text.replace(/(.{2})(.*)(?=@)/, (_, a, b) => a + "*".repeat(b.length));
            }
          
            document.querySelectorAll('.censor-email').forEach(el => {
              const original = el.textContent.trim();
              el.textContent = censorEmail(original);
            });
          </script>

          <li><strong>Position: </strong><?php echo htmlspecialchars($supervisor_job_role); ?></li>
          <li><strong>Department: </strong><?php echo htmlspecialchars($department_name); ?></li>
          <li><strong>Company: </strong><?php echo htmlspecialchars($company_name); ?></li>
        </ul>
        <div style="text-align: end;">
          <a href="#">Is the information incorrect? Click here to request an edit</a>
        </div>
      </div>
    </div>
  </div>
  <div class="box-shadow">
    <div class="content-card">
      <section class="dashboard-options">
        <button class="main-btn" onclick="changeIframe('../../templates/supervisor/masterlist.html')">🔍 View Assigned Interns</button>
        <p>You have <strong>4</strong> interns under your supervision.</p>
        
        <hr style="border: none; border-top: 2px dashed gray; margin: 20px 0;">

        <div class="quick-links">
          <h2>QUICK ACTIONS</h2>
          <ul>
            <li><a href="#">📞 Internship Officer</a></li>
            <li><a href="#">📖 Evaluation Guide</a></li>
            <li><a href="#">🦆 Website Feedback</a></li>
          </ul>
        </div>
      </section>
    </div>
  </div>
</body>

<script src="../../templates/_components/sidebar.js"></script>
<script src="../../templates/_components/content.js"></script>