<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&display=swap" rel="stylesheet">

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Internship Assessment System</title>
    <link rel="stylesheet" href="/static/css/style.css" />
    
    <style>
        .header {
            background-color: #FFF3E2;
            padding: 20px;
            margin: 0;
            height: 100vh; /* Adjust to content height */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column; /* Align child elements horizontally */
            justify-content: flex-start;
            width: 100%; /* Make sure it takes full width */
        }
        .search-and-proceed {
            display: flex;
            flex-direction: row; /* Align child elements horizontally */
            width: 100%; /* Full width */
        }
        .back button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .search-bar {
            display: flex;
            justify-content: flex-start; /* Align search bar to the right */
            margin: 5px;
        }
        
        .search-bar input {
        flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 8px 15px;
            background-color: #FF8C42;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }

    .proceed button{
            background-color: #E7F8E9;
            margin-top: 5px;
            margin-left: 500%; /* Align to the right */
            padding: 8px 15px;
            border: 1px solid rgb(0, 0, 0);
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            justify-content: end; /* Align search bar to the right */
}
    .information {
            background-color: #ffffff;
            padding: 10px;
            height: 100vh; /* Adjust to content height */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column; /* Align child elements horizontally */
            justify-content: flex-start;
            width: 100%; /* Make sure it takes full width */
            border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top-left-radius: 10px;
                border-bottom-left-radius: 10px;
        }
        hr {
        border: none;
        height: 2px;
        background-color: #ff69b4;
        margin: 5px 0;
        }

        .dropdown {
    position: relative;
    display: inline-block;
}

.styled-select {
    margin: 20px;
    width: 400px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f3f3f3;
    font-size: 1rem;
    color: #333;
    appearance: none; /* removes default arrow */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position-x: 95%;
    background-position-y: center;
}

.styled-select:focus {
    outline: none;
    border-color: #FF8C42;
    box-shadow: 0 0 3px #FF8C42;
}


        </style>
</head>

<div class="header">
    <div class="back">
                <button onclick="window.location.href='../../../templates/schooluser/faculty/home.php'">Back</button>
            </div>
    <div class="search-and-proceed">
                <div class="search-bar">
                        <form action="/supervisor/masterlist" method="GET">
                            <input type="text" name="search" placeholder="Search by student name..." required>
                            <button type="submit">Search</button>
                        </form>
                </div>
                <div class="proceed">
                    <button onclick="window.location.href='../../../templates/schooluser/faculty/rubrics.php'">Proceed</button>
        </div>
        </div>
    <div class="information">
        <h1>Rubrics Filters</h1>
        <hr>

                    <!-- Include jQuery and Select2 -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <label for="year">Year</label>
        <select id="year" class="styled-select">
        <option>2025</option>
        <option>2024</option>
        <option>2023</option>
        </select>

        <label for="term">Term</label>
        <select id="term" class="styled-select">
        <option>INTERN1 - TERM 1</option>
        <option>INTERN1 - TERM 2</option>
        <option>INTERN2 - TERM 1</option>
        <option>INTERN2 - TERM 2</option>
        </select>

        <label for="school">School:</label>
        <select id="school" class="styled-select">
        <option value="">Select a school</option>
        <option value="engineering">School of Engineering</option>
        <option value="arts">School of Multimedia And Arts</option>
        <option value="management">School of Management</option>
        </select>

        <label for="program">Program:</label>
        <select id="program" class="styled-select">
        <option value="">Select a program</option>
        </select>

        <script>
        $(document).ready(function() {
            $('#year').select2();
        });
        </script>

        <script>
        $(document).ready(function() {
            $('#term').select2();
        });
        </script>
                <script>
        $(document).ready(function() {
            $('#school').select2();
        });
        </script>

        <script>
        $(document).ready(function() {
            $('#program').select2();
        });
        </script>

<script>
        const programsBySchool = {
            engineering: [
            { value: "civil", text: "Civil Engineering" },
            { value: "comp", text: "Computer Engineering" },
            { value: "electro", text: "Electrical Engineering" },
            ],
            arts: [
                { value: "psych", text: "Psychology" },
                { value: "multi-arts", text: "Multimedia Arts" }
            ],
            management: [
            { value: "business admin", text: "Business Administration" },
            { value: "acc", text: "Accountancy" },
            { value: "finance manage", text: "Finance Management" },
            { value: "tourism manage", text: "Tourism Management" },
            { value: "manage acc", text: "Management Accounting" }
            ]
        };
    </script>

    <script>
    $(document).ready(function() {
        $('#school').on('change', function() {
            const selectedSchool = $(this).val();
            const $program = $('#program');

            // Clear current options
            $program.empty();

            // Add placeholder
            $program.append(new Option("Select a program", ""));

            // Add new options
            if (programsBySchool[selectedSchool]) {
                programsBySchool[selectedSchool].forEach(program => {
                    $program.append(new Option(program.text, program.value));
                });
            }

            // Refresh Select2
            $program.trigger('change');
        });
    });
    </script>
</div>

</div>