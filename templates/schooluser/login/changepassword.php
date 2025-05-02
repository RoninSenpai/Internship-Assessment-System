<?php
  session_start();
  include '../../../database/database.php';

  // Step 1: If token exists in URL, store it in session and clean the URL
  if (isset($_GET['token'])) {
      $_SESSION['magic_token'] = $_GET['token'];

      // Redirect to same page without token in URL
      header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
      exit;
  }

  // Step 2: Use the token from session instead of URL
  $token = $_SESSION['magic_token'] ?? '';

  // Step 3: Check if the token exists in session, otherwise show 404
  if (!$token) {
      http_response_code(404);
      echo "Invalid or expired token!";
      exit;
  }

  // echo $token;

  // Step 4: Validate the token
  $query = "SELECT * FROM passwordresets WHERE passreset_token = ? AND passreset_is_used = 0 AND passreset_date_expiry > NOW()";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param('s', $token);
  $stmt->execute();

  $reset = $stmt->get_result()->fetch_assoc();

  // Step 5: If the token is valid, proceed with the password reset logic
  if ($reset) {
    error_log("1POST request received. Token: " . $token);
    error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);

    // Token is valid
    // echo "HI" . $_SERVER['REQUEST_METHOD'] . "\n";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      error_log("2POST request received. Token: " . $token);
      $new_password = $_POST['new_password'];
      $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

      // Step 6: Update the password in the school_users table
      $updateQuery = "UPDATE school_users SET schooluser_password = ? WHERE schooluser_id = ?";
      $updateStmt = $mysqli->prepare($updateQuery);
      $updateStmt->bind_param('si', $hashed_password, $reset['schooluser_id']);
      $updateStmt->execute();

      // Step 7: Mark the token as used
      $updateTokenQuery = "UPDATE passwordresets SET passreset_is_used = 1 WHERE passreset_token = ?";
      $updateTokenStmt = $mysqli->prepare($updateTokenQuery);
      $updateTokenStmt->bind_param('s', $token);
      $updateTokenStmt->execute();

      // Step 8: Redirect to login page after successful reset
      header("Location: /rias");
      exit;
    }
  } else {
    // Token is invalid or expired
    http_response_code(404);
    echo "Invalid or expired token!";
    exit;
  }
?>

<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="../../../static/images/components\rias_icon.png">
    <title>Forgot Password? - RIAS</title>
    </title>
    <link rel="stylesheet"  href="../../../static/css/components.css"></link>
    <style>
      .content-card {
        max-width: 700px;
        padding-top: 30px;
        padding-bottom: 50px;
      }

      h2, .form-group {
        margin-bottom: 20px;
      }

      {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        user-select: none;
      }

      /* Ensure the body and html take up the full height */
      html, body {
        background-color: #d3d3d3;
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
      }

      .hidden-logo {
        display: none; /* Hide the logo */
        max-height: 20px;
      }

      .rias-logo {
          /* height: 50px; */
          width: auto;
          transition: transform 0.3s ease-in-out;
          transform-origin: left;
          cursor: pointer;
      }

      .rias-logo:hover {
          transform: scale(1.1);
          transform-origin: left;
      }

      body {
        font-family: 'Lora' , serif;
        color: #222;
        padding-top: 80px; /* Keep header space */
        padding-bottom: 0; /* Remove bottom padding */
      }

      .header {
        align-items: unset;
        background-color: #f8f9fa; /* Light gray background */
        /* border-bottom: 2px solid #ddd; */
        z-index: 1000; /* Ensure header is above other elements */
        height: 80px; /* Fix the height of the header */
        display: flex;
        /* align-items: center; */ /* Center items vertically */
        /* justify-content: center; */ /* Center items horizontally */
        /* padding: 0 20px; */ /* Add some padding for spacing */
        box-sizing: border-box; /* Include padding in the height calculation */
        position: fixed; /* Keep the header visible at the top */
        top: 0;
        left: 0;
        right: 0;
      }

      .header .header-text {
        display: flex;
        height: 100px;
        align-items: center; /* Align vertically */
      }

      .header-content {
        display: flex;
        flex-direction: row; /* Align items horizontally */
        /* align-items: center; */ /* Center items vertically */
        /* justify-content: space-between; */ /* Space out the logos */
        width: 100%; /* Ensure it spans the full width of the header */
        /* max-width: 1400px; */ /* Optional: Limit the width for better layout */
        /* gap: 100px; */ /* Add spacing between the logos */
        margin: 0 auto; /* Center the content within the header */
      }

      .header .header-content {
        display: flex;
        align-items: center; /* Align vertically */
      }

      .header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
        padding: 0 50px;
        background-color: FBAF41;
      }

      .header-logo,
      .rias-logo {
        max-height: 60px; /* Ensure logos fit within the header */
        width: auto; /* Maintain aspect ratio */
        /* margin-right: auto; */
        /* position: relative; */
        /* display: flex; */
      }

      .header-logo,
      .header-apclogo {
        max-height: 120px; /* Adjust the size of the APC logo */
        width: auto; /* Maintain aspect ratio */
      }

      .site-title {
        font-family: 'Lora', serif;
        font-size: 32px; /* Adjust font size to fit the thinner header */
        color: #222;
        font-weight: 600;
        text-align: center; /* Center the title */
      }

      .info-card {
        max-width: 400px;
        margin: 100px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-family: 'Segoe UI', sans-serif;
      }

      .info-card h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
      }

      /* Form styles */
      form {
        display: flex;
        flex-direction: column;
        gap: 15px;
      }

      .form-group {
        text-align: left;
      }

      label {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
      }

      input {
        width: 100%;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
      }

      input.enabled:focus {
        border-color: #007bff;
        outline: none;
      }

      /* Button styles */
      button[type="submit"] {
        padding: 12px;
        background-color: #213b9a;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      button.enabled[type="submit"]:hover {
        background-color: #0056b3;
      }

      /* Disabled state for submit button */
      button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
      }

      /* Eye button for visibility toggle */
      .input-with-eye {
        position: relative;
      }

      .input-with-eye input {
        box-sizing: border-box;
        width: 100%;
        padding-right: 2.5rem;
      }

      .eye-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        font-size: 18px;
        cursor: pointer;
      }

      /* Hint styles */
      small {
        color: #777;
      }

      #match-hint, #pw-hint {
        font-size: 12px;
      }

      #match-hint.valid, #pw-hint.valid {
        color: green;
      }

      #match-hint.invalid, #pw-hint.invalid {
        color: red;
      }
    </style>
  </head>

  <body>
    <header class="header">
      <div class="container header-content">
        <a href="../../../templates/schooluser\login\index.php" target="_blank" rel="noopener noreferrer">
          <img src="../../../static/images/components/header_apclogo2.png" alt="RIAS Logo" class="rias-logo" />
        </a>
        <img src="../../../static/images/components/header_apclogo.png" alt="APC Logo" class="header-logo" />
      </div>
    </header>
    
    <div class="box-shadow">
      <div class="content-card">
        <h2>CHANGE PASSWORD</h2>
        <form id="reset-form" method="POST">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />

          <div class="form-group">
            <label for="new-password">New Password</label>
            <div class="input-with-eye">
              <input type="password" id="new-password" name="new_password" required />
              <button type="button" class="eye-btn" onclick="toggleVisibility('new-password', this)">👁️</button>
            </div>
            <small id="pw-hint">Must be at least 12 characters with letters, numbers, and symbols</small>
          </div>

          <div class="form-group">
            <label for="confirm-password">Confirm New Password</label>
            <div class="input-with-eye">
              <input type="password" id="confirm-password" name="confirm_password" required maxlength="255"/>
              <button type="button" class="eye-btn" onclick="toggleVisibility('confirm-password', this)">👁️</button>
            </div>
            <small id="match-hint"></small>
          </div>

          <button type="submit" id="submit-btn" disabled ">RESET PASSWORD</button>
        </form>
      </div>
    </div>

    <script>
      const newPassword = document.getElementById('new-password');
      const confirmPassword = document.getElementById('confirm-password');
      const submitBtn = document.getElementById('submit-btn');
      const pwHint = document.getElementById('pw-hint');
      const matchHint = document.getElementById('match-hint');

      function validatePassword() {
        const pw = newPassword.value;
        const confirm = confirmPassword.value;

        const missing = [];
        if (pw.length < 12) missing.push("at least 12 characters");
        if (!/[a-z]/i.test(pw)) missing.push("a letter");
        if (!/[0-9]/.test(pw)) missing.push("a number");
        if (!/[^A-Za-z0-9]/.test(pw)) missing.push("a symbol");

        // Set the hint text and color based on password validity
        if (missing.length === 0) {
          pwHint.textContent = "Password meets all requirements ✓";
          pwHint.classList.add("valid");
          pwHint.classList.remove("invalid");
        } else {
          pwHint.textContent = "Missing: " + missing.join(", ");
          pwHint.classList.add("invalid");
          pwHint.classList.remove("valid");
        }

        const match = pw === confirm && confirm.length > 0;
        matchHint.textContent = match ? "Passwords match ✓" : "Passwords do not match!";
        matchHint.classList.add(match ? "valid" : "invalid");
        matchHint.classList.remove(match ? "invalid" : "valid");

        submitBtn.disabled = !(missing.length === 0 && match);
        submitBtn.classList.toggle("disabled", submitBtn.disabled);
        submitBtn.classList.toggle("enabled", !submitBtn.disabled);
      }

      newPassword.addEventListener('input', validatePassword);
      confirmPassword.addEventListener('input', validatePassword);

      function toggleVisibility(id, button) {
        const field = document.getElementById(id);
        const isPassword = field.type === 'password';
        field.type = isPassword ? 'text' : 'password';
        button.textContent = isPassword ? '🙈' : '👁️';
      }

      document.getElementById('reset-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const confirmed = confirm("Confirm password change?");
        if (!confirmed) {
          return; // If the user cancels, do nothing
        }

        // Collect form data
        const formData = new FormData(this);

        console.log("Form data:", formData);

        // Send the form data via fetch as a POST request
        fetch(window.location.href, {
          method: 'POST',
          body: formData,
        })
        .then(response => {
          console.log("Response status:", response); // Log the response status
          return response.text().then(text => {
        console.log("Response body:", text); // Log the response body
        if (response.ok) {
          // Show success message and redirect
          alert("Password changed successfully!\nRedirecting to the login page...");
          window.location.replace('/rias');  // Adjust the redirect path as needed
        } else {
          // Handle error if the response is not successful
          alert("Error occurred while changing password. Please try again.");
        }
          });
        })
        .catch(error => {
          // Catch any network or other errors
          console.error("Error during fetch:", error);
          alert("An error occurred. Please try again.");
        });
      });

    </script>

  </body>
</html>