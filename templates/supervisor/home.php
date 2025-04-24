<?php
  include '../../database/database.php';

  $token = $_GET['token'] ?? null;

  if (!$token) {
    echo json_encode(["error" => "token not provided"]);
    exit;
  }

  // Step 1: Get supervisor_id from token
  $stmt = $mysqli->prepare("
    SELECT supervisor_id FROM send_assessments WHERE sendass_token = ?
  ");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if (!$result || $result->num_rows === 0) {
    echo json_encode(["error" => "Invalid token! Begone! 💢"]);
    $stmt->close();
    $mysqli->close();
    exit;
  }

  $supervisor_id = $result->fetch_assoc()['supervisor_id'];
  $stmt->close();

  // Step 2: Fetch related data
  $stmt = $mysqli->prepare("
    SELECT 
      u.user_first_name, u.user_last_name, u.user_email,
      s.supervisor_job_role, d.department_name, c.company_name
    FROM supervisors s
    JOIN users u ON s.user_id = u.user_id
    JOIN departments d ON s.department_id = d.department_id
    JOIN companies c ON d.company_id = c.company_id
    WHERE s.supervisor_id = ?
    LIMIT 1
  ");
  $stmt->bind_param("i", $supervisor_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    // echo json_encode($data);
  } else {
    echo json_encode(["error" => "Supervisor data not found! ಠ_ಠ"]);
  }

  $stmt->close();
  $mysqli->close();
?>

<head>
  <link rel="stylesheet" href="../../static/css/components.css">
  <link rel="stylesheet" href="home.css">
</head>

<body class="content">
  <h1 id="greeting">Good day, Mr. <span id="last-name"><?php echo htmlspecialchars($data['user_last_name'] ?? ''); ?></span>!</h1>

  <p class="date">Wednesday, March 12, 2025</p>
  <script src="../../templates/_components/date.js"></script>

  <div class="box-shadow">
    <div class="content-card">
      <div class="info-card">
        <h2>SUPERVISOR INFORMATION</h2>
        <ul>
          <li>
            <strong>Name: </strong>
            <span id="first-name"><?php echo htmlspecialchars($data['user_first_name'] ?? ''); ?></span>
            <span id="last-name"><?php echo htmlspecialchars($data['user_last_name'] ?? ''); ?></span>
          </li>
          <li>
            <strong>Email: </strong>
            <span id="email" class="censor-email"><?php echo htmlspecialchars($data['user_email'] ?? ''); ?></span>
          </li>

          <script>
            function censorEmail(text) {
              return text.replace(/(.{2})(.*)(?=@)/, (_, a, b) => a + "*".repeat(b.length));
            }

            document.querySelectorAll('.censor-email').forEach(el => {
              const original = el.textContent.trim();
              el.textContent = censorEmail(original);
            });
          </script>

          <li><strong>Position: </strong><span id="job-role"><?php echo htmlspecialchars($data['supervisor_job_role'] ?? ''); ?></span></li>
          <li><strong>Department: </strong><span id="department"><?php echo htmlspecialchars($data['department_name'] ?? ''); ?></span></li>
          <li><strong>Company: </strong><span id="company"><?php echo htmlspecialchars($data['company_name'] ?? ''); ?></span></li>
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