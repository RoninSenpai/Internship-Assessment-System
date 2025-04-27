<?php
    include '../../database/database.php';

    $token = $_GET['token'] ?? null;
    $intern_id = $_GET['intern_id'] ?? null;
    if (!$token || !$intern_id) {
        echo "Token or id not provided, baka!";
        exit;
    }

    $sql = "SELECT 
                CONCAT(u.user_last_name, ', ', u.user_first_name) AS full_name,
                p.program_name,
                i.internship_year
            FROM internships i
            JOIN interns inr ON i.intern_id = inr.intern_id
            JOIN programs p ON inr.program_id = p.program_id
            JOIN school_users su ON inr.schooluser_id = su.schooluser_id
            JOIN users u ON su.user_id = u.user_id
            WHERE i.intern_id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $intern_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $dataStatus = isset($_GET['data-status']) ? $_GET['data-status'] : null;
?>

<script>
    window.tokenFromPHP = <?php echo json_encode($token); ?>;

    var dataStatusFromPHP = "<?php echo $dataStatus; ?>";
</script>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../../static\css\components.css">
    <!-- <link rel="stylesheet" href="evaluation2.css" /> -->
    <link rel="stylesheet" href="evaluation.css" />
</head>

<body class="content">
    <p class="date">Wednesday, March 12, 2025</p>
    <script src="../../templates/_components/date.js"></script>

    <div class="box-shadow">
        <div class="content-card">
            <h1>EVALUATION FORM</h1>

            <article class="card">
                <img src="https://static.vecteezy.com/system/resources/previews/002/534/006/original/social-media-chatting-online-blank-profile-picture-head-and-body-icon-people-standing-icon-grey-background-free-vector.jpg"
                    />
                <div class="card-content">
                    <h3 class="name"><?php echo htmlspecialchars($data['full_name'] ?? ''); ?></h3>
                    <p class="course"><?php echo htmlspecialchars($data['program_name'] ?? ''); ?></p>
                    <p class="intern-id"><?php echo htmlspecialchars($data['internship_year'] ?? ''); ?></p>
                </div>
            </article>

            <p class="scale">RATING SCALE: 5 - Highest, 0 - Lowest, N/A - Not Applicable</p>

            <form id="evaluationForm" onsubmit="return handleEvaluationSubmit(event)" method="POST">
                <table border="1">
                    <thead>
                        <colgroup>
                            <col style="width: 50px">
                            <col>
                            <col style="width: 50px">
                            <col style="width: 50px">
                            <col style="width: 50px">
                            <col style="width: 50px">
                            <col style="width: 50px">
                            <col style="width: 50px">
                            <col style="width: 50px">
                        </colgroup>
                        <tr>
                            <th rowspan="2" class="table-header">#</th>
                            <th rowspan="2" class="table-header">Criteria</th>
                            <th colspan="7" class="table-header">Rating</th>
                        </tr>
                        <tr class="rating-header">
                            <th class="table-header">5</th>
                            <th class="table-header">4</th>
                            <th class="table-header">3</th>
                            <th class="table-header">2</th>
                            <th class="table-header">1</th>
                            <th class="table-header">0</th>
                            <th class="table-header">N/A</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // Assuming you have a connection to your database already
                        $internship_id = null;


                        // Prepare the query to fetch internship data based on intern_id
                        $sql = "SELECT internship_id FROM internships WHERE intern_id = ?";

                        // Prepare the statement
                        if ($stmt = $mysqli->prepare($sql)) {
                            // Bind parameters
                            $stmt->bind_param("i", $intern_id);

                            // Execute the statement
                            $stmt->execute();

                            // Bind the result to a variable
                            $stmt->bind_result($internship_id);

                            // Fetch the result
                            if ($stmt->fetch()) {
                                // Successfully fetched the internship_id
                                // echo "Internship ID: " . $internship_id; // Debugging line
                            } else {
                                echo "No internship found for the given intern_id.";
                            }

                            // Close the statement
                            $stmt->close();
                        } else {
                            echo "Error: Could not prepare the query.";
                        }

                        // Fetch the assessment contents
                        $sql = "SELECT * FROM `assessment_contents` WHERE `assessments_id` = 1"; // Get all the questions for assessment_id 1
                        $result = $mysqli->query($sql);

                        if ($result->num_rows > 0) {
                            $counter = 1; // To handle the dynamic radio button names
                            while($row = $result->fetch_assoc()) {
                                $assessment_content_id = $row['assessment_content_id']; // Store the assessment_content_id
                                
                                // Debug: Output the assessment content ID and question
                                // echo "Debug: assessment_content_id: $assessment_content_id, question: " . htmlspecialchars($row['ass_content_question']) . "<br>";

                                // Check if a grade exists for this assessment_content_id and internship_id
                                $sql_grade = "SELECT internship_id, assessment_content_id, assessment_grade 
                                                FROM assessment_grades 
                                                WHERE internship_id = ? AND assessment_content_id = ?";
                                if ($stmt = $mysqli->prepare($sql_grade)) {
                                    $stmt->bind_param("ii", $internship_id, $assessment_content_id);
                                    $stmt->execute();
                                    $stmt->store_result();
                                    
                                    // echo "Debug: sql_grade: $sql_grade<br>";
                                    if ($stmt->num_rows > 0) {
                                        // Data exists, fetch the grade
                                        $stmt->bind_result($existing_internship_id, $existing_assessment_content_id, $existing_grade);
                                        $stmt->fetch();
                                        $existing_grade_value = $existing_grade; // Store the existing grade
                                        
                                        // Debug: Output the existing grade
                                        // echo "Debug: Existing grade: $existing_grade_value<br>";
                                    } else {
                                        $existing_grade_value = -2; // No existing grade
                                        // echo "Debug: Existing grade: null<br>";
                                    }
                                    $stmt->close();
                                }

                                echo "<tr>";
                                echo "<td class='number'>" . $counter . "</td>"; // Dynamic numbering
                                echo "<td class='criteria-item'>" . htmlspecialchars($row['ass_content_question']) . "</td>"; // The question text
                                
                                // Add hidden input for assessment_content_id
                                echo "<input type='hidden' name='assessment_content_id_" . $counter . "' value='" . $assessment_content_id . "'>";
                                
                                // Radio buttons with dynamic names (grade_1, grade_2, etc.)
                                for ($grade = 5; $grade >= 0; $grade--) {
                                    $checked = ($existing_grade_value == $grade) ? "checked" : ""; // Check if the grade is already selected
                                    
                                    // Debug: Output the grade and checked value
                                    // echo "Debug: existing_grade_value: $existing_grade_value, Checked: $checked<br>";
                                    // echo "Debug: Grade: $grade, Checked: $checked<br>";
                                    
                                    echo "<td class='rating-item'><label><input type='radio' name='grade_" . $counter . "' value='" . $grade . "' " . $checked . ($grade == 0 ? "" : "") . "><span>" . $grade . "</span></label></td>";
                                }
                                echo "<td class='rating-item'><label><input type='radio' name='grade_" . $counter . "' value='N/A' " . (($existing_grade_value == -1) ? "checked" : "") . " required><span>N/A</span></label></td>";
                                echo "</tr>";
                                
                                $counter++; // Increment to next number for dynamic naming
                            }
                        } else {
                            echo "<tr><td colspan='8'>No assessment content found.</td></tr>";
                        }

                        // Fetch the feedback contents
                        $sql_feedback = "SELECT * FROM `assessment_feedback` WHERE `assessment_id` = 1";
                        $result_feedback = $mysqli->query($sql_feedback);


                        if ($result_feedback->num_rows > 0) {
                            $feedbackcounter = 1;
                            
                            while ($row = $result_feedback->fetch_assoc()) {
                                $afeedback_content_id = $row['afeedback_content_id'];
                                $question_text = htmlspecialchars($row['afeedback_questions']);
                                $requires_yesno = $row['afeedback_yesno'];

                                // Default values
                                $existing_feedback_answer1 = '';
                                $existing_feedback_yesno1 = null;

                                // Prepare statement to fetch existing feedback
                                $sql_feedback_data = "SELECT `feedback_answer`, `feedback_yesno` 
                                                    FROM `feedback` 
                                                    WHERE `internship_id` = ? AND `afeedback_content_id` = ?";

                                if ($stmt = $mysqli->prepare($sql_feedback_data)) {
                                    $stmt->bind_param("ii", $internship_id, $afeedback_content_id);
                                    $stmt->execute();
                                    $stmt->store_result();

                                    if ($stmt->num_rows > 0) {
                                        $stmt->bind_result($existing_feedback_answer1, $existing_feedback_yesno1);
                                        $stmt->fetch();
                                    }

                                    $stmt->close();
                                }

                                // Output the feedback row
                                echo "<tr>";
                                echo "<td class='number'>" . $counter . "</td>";
                                echo "<td colspan='8' class='criteria-item'>";

                                // Hidden input for content ID
                                echo "<input type='hidden' name='afeedback_content_id_" . $feedbackcounter . "' value='" . $afeedback_content_id . "'>";

                                // Output question
                                echo "<p>$question_text</p>";

                                if ($requires_yesno) {
                                    // Yes/No radio input
                                    $yes_checked = ($existing_feedback_yesno1 === 1) ? "checked" : "";
                                    $no_checked  = ($existing_feedback_yesno1 === 0) ? "checked" : "";

                                    echo "<div class='yesno'>";
                                    echo "<label><input type='radio' name='feedback_yesno_" . $feedbackcounter . "' value='Yes' $yes_checked required><span>Yes</span></label>";
                                    echo "<label><input type='radio' name='feedback_yesno_" . $feedbackcounter . "' value='No' $no_checked><span>No</span></label>";
                                    echo "</div>";
                                } else {
                                    // Textarea input
                                    $feedback_value = htmlspecialchars($existing_feedback_answer1);
                                    echo "<textarea class='textarea-expand' rows='4' cols='50' name='feedback_" . $feedbackcounter . "' placeholder='Your feedback here...' maxlength='512'>" . $feedback_value . "</textarea>";
                                }

                                echo "</td>";
                                echo "</tr>";

                                $counter++;
                                $feedbackcounter++;
                            }
                        } else {
                            echo "<tr><td colspan='8'>No feedback found.</td></tr>";
                        }
                    ?> 

                    </tbody>
                    <tfoot>
                        <tr class="point-count-row">
                            <td colspan="2" class="table-footer">Point Count:</td>
                            <td id="count-5" class="point-count"><span>0</span></td>
                            <td id="count-4" class="point-count"><span>0</span></td>
                            <td id="count-3" class="point-count"><span>0</span></td>
                            <td id="count-2" class="point-count"><span>0</span></td>
                            <td id="count-1" class="point-count"><span>0</span></td>
                            <td id="count-0" class="point-count"><span>0</span></td>
                            <td id="count-na" class="point-count"><span>0</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="table-footer">Subtotal:</td>
                            <td id="subtotal-5" class="subtotal-cell"><span>0</span></td>
                            <td id="subtotal-4" class="subtotal-cell"><span>0</span></td>
                            <td id="subtotal-3" class="subtotal-cell"><span>0</span></td>
                            <td id="subtotal-2" class="subtotal-cell"><span>0</span></td>
                            <td id="subtotal-1" class="subtotal-cell"><span>0</span></td>
                            <td id="subtotal-0" class="subtotal-cell"><span>0</span></td>
                            <td id="subtotal-na" class="subtotal-cell"><span>0</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="table-footer">Total Grade:</td>
                            <td colspan="2" class="average-score" id="average-score"><input type="hidden" name="supervisor_grade" id="supervisor_grade_hidden">
                            <span>0</span></td>
                            <td colspan="5" class="average-score"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="table-footer">APC Grade Equivalent:</td>
                            <td colspan="2" class="apc-grade" id="apc-grade">0</td>
                            <td colspan="5" class="apc-grade"></td>
                        </tr>
                    </tfoot>
                </table>
                
                <p class="note">Thank you for evaluating the student-intern objectively and completely.</p>
                
                <div class="button-panel">
                    <button type="button" class="cancel-buttons"
                        onclick="changeIframe('../../templates/supervisor/masterlist.php?token=<?php echo urlencode($token); ?>', 'Are you sure you want to discard your latest changes?')">Discard
                        Changes</button>
                    <button type="button" name="action" value="save" class="save-buttons" id="save-button">Save Changes</button>
                    <button type="submit" name="action" value="submit" class="submit-buttons">Submit Evaluation</button>
                </div>

                <script> 
                    document.querySelector('.submit-buttons').addEventListener('click', function() {
                        var grade = document.querySelector('#average-score span').innerText;
                        document.querySelector('#supervisor_grade_hidden').value = grade;
                    });
                </script>

                <div class="button-panel2">
                    <button type="button" class="back-buttons" 
                    onclick="changeIframe('masterlist.php?token=<?php echo urlencode($token); ?>', null, force=true)">Back to Intern list</button>
                </div>  
            </form>

            <div id="server-response"></div>
            <?php
                // echo "Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
                // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //     echo "POST detected! You did it, you funky banana 🎉<br>";
                // } else {
                //     echo "YOU DID A GET. YOU FAILURE. 💀";
                // }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && ($_POST['action'] == 'submit' || $_POST['action'] == 'save')) {
                    echo $_POST['action'];

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
                        // Connect to your database first, dummy (っ °Д °;)っ

                        // Get the supervisor grade from the page
                        $supervisor_grade = isset($_POST['supervisor_grade']) ? intval($_POST['supervisor_grade']) : 0;
                        $internship_id = intval($internship_id); // Make sure it's clean, you little code-goblin

                        $stmt = $mysqli->prepare("INSERT INTO `internship_grades` (`internship_id`, `supervisor_grade`, `supervisor_date_graded`, `io_grade`, `io_date_graded`, `total_grade`) VALUES (?, ?, NOW(), NULL, NULL, NULL)");
                        $stmt->bind_param("ii", $internship_id, $supervisor_grade);

                        if ($stmt->execute()) {
                            echo "NYAA~ Successfully inserted the grade, you functional disaster! (≧◡≦)";
                        } else {
                            echo "You failed so hard, the database is weeping: " . $stmt->error;
                        }
                    }
                    
                    // Query to get internship_id based on intern_id
                    $sql_internship = "SELECT internship_id FROM internships WHERE intern_id = ?";
                    if ($stmt = $mysqli->prepare($sql_internship)) {
                        $stmt->bind_param("i", $intern_id); // 'i' for integer
                        $stmt->execute();
                        $stmt->bind_result($internship_id); // Bind result to $internship_id

                        // Check if a result is found
                        if ($stmt->fetch()) {
                            // internship_id is now available for use
                            // echo "Internship ID: " . $internship_id;
                        } else {
                            echo "No internship found for this intern ID.";
                        }
                        $stmt->close();
                    }

                    // DELETE existing rows from the assessment_grades table for the same internship_id
                    $delete_assessment_grades = "DELETE FROM `assessment_grades` WHERE internship_id = ?";
                    if ($stmt = $mysqli->prepare($delete_assessment_grades)) {
                        $stmt->bind_param("i", $internship_id);
                        $stmt->execute();
                        $stmt->close();
                    }
                
                    // DELETE existing rows from the feedback table for the same internship_id
                    $delete_feedback = "DELETE FROM `feedback` WHERE internship_id = ?";
                    if ($stmt = $mysqli->prepare($delete_feedback)) {
                        $stmt->bind_param("i", $internship_id);
                        $stmt->execute();
                        $stmt->close();
                    }

                    // Process Grades (from the 'assessment_grades' table)
                    $max_grades = 100; // Set an upper limit to avoid infinite loops from hell
                    $counter = 1;

                    while ($counter <= $max_grades) {
                        if (!isset($_POST['grade_' . $counter])) {
                            $counter++;
                            continue; // SKIP missing grades instead of dying
                        }

                        $grade_value = $_POST['grade_' . $counter];

                        $assessment_content_id = isset($_POST['assessment_content_id_' . $counter]) ? $_POST['assessment_content_id_' . $counter] : null;

                        if ($assessment_content_id === null) {
                            echo "Error: Missing assessment content ID for grade_" . $counter . "<br>";
                            $counter++;
                            continue;
                        }

                        if ($grade_value == "N/A") $grade_value = -1;

                        $sql_grade = "INSERT INTO `assessment_grades` (internship_id, assessment_content_id, assessment_grade) 
                                    VALUES (?, ?, ?)";
                        if ($stmt = $mysqli->prepare($sql_grade)) {
                            $stmt->bind_param("iii", $internship_id, $assessment_content_id, $grade_value);
                            $stmt->execute();
                        }

                        $counter++;
                    }

                    // Process Feedback (from the 'feedback' table)
                    $max_feedback = 100; // arbitrary safety limit
                    $feedback_counter = 1;

                    while ($feedback_counter <= $max_feedback) {
                        if (!isset($_POST['feedback_' . $feedback_counter]) && !isset($_POST['feedback_yesno_' . $feedback_counter])) {
                            $feedback_counter++;
                            continue; // SKIP if neither feedback nor yesno exists
                        }

                        $feedback_answer = isset($_POST['feedback_' . $feedback_counter]) ? $_POST['feedback_' . $feedback_counter] : null;
                        if (strlen($feedback_answer) < 1) $feedback_yesno = -1;
                        $feedback_yesno = (isset($_POST['feedback_yesno_' . $feedback_counter]) && $_POST['feedback_yesno_' . $feedback_counter] == "Yes") ? 1 : 0;

                        if ($feedback_answer == !null) $feedback_yesno = -1;

                        $temp = $_POST['feedback_yesno_' . $feedback_counter];
                        echo "Feedback Answer: $temp <br>"; // Debugging line

                        $afeedback_content_id = isset($_POST['afeedback_content_id_' . $feedback_counter]) ? $_POST['afeedback_content_id_' . $feedback_counter] : null;

                        if ($afeedback_content_id === null) {
                            echo "Error: Missing feedback content ID for feedback_" . $feedback_counter . "<br>";
                            $feedback_counter++;
                            continue;
                        }

                        $sql_feedback = "INSERT INTO `feedback` (internship_id, afeedback_content_id, feedback_answer, feedback_yesno) 
                                        VALUES (?, ?, ?, ?)";
                        if ($stmt = $mysqli->prepare($sql_feedback)) {
                            $stmt->bind_param("iisi", $internship_id, $afeedback_content_id, $feedback_answer, $feedback_yesno);
                            
                            if ($stmt->execute()) {
                                // success maybe
                            } else {
                                echo "Error: " . $stmt->error;
                            }
                        }

                        $feedback_counter++;
                    }       


                    echo "Evaluation submitted successfully!";
                }
            ?>
        </div>
    </div>
</body>

<script src="../../templates/_components/sidebar.js"></script>
<script src="../../templates/_components/content.js"></script>
<script src="../../templates\supervisor\evaluation.js"></script>