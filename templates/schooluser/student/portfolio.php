<!-- <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
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
            height: 200vh; /* Adjust to content height */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column; 
            justify-content: flex-start;
            width: 100%; /* Make sure it takes full width */
        }

        hr {
            border: none;
            height: 2px;
            background-color: #ff69b4;
            margin: 0px 0;
        }

        .papers-header {
            display: flex;
            flex-direction: row;
            justify-content: left;
            align-items: left;
            width: 100%; /* Full width */
        }
        
        .attendance{
            display: flex;
            flex-direction: column;
            justify-content: left;
            align-items: left;
            width: 100%; /* Full width */
            border-right: 2px solid #ccc; /* light gray border between panels */
            padding-right: 20px;
            margin-right: 10px;
            margin-left: 10px;
        }

        .attendance-buttons {
            color: black;
            background-color: #FBAF41;
            padding: 8px 15px;
            border: 2px solid rgb(0, 0, 0);
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 15px;
            margin-left: 10px;
        }

        .accomplishment-buttons {
            color: black;
            background-color: #FBAF41;
            padding: 8px 15px;
            border: 2px solid rgb(0, 0, 0);
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 15px;
            margin-left: 10px;
        }

        .accomplishments {
            display: flex;
            flex-direction: column; 
            justify-content: left;
            align-items: left;
            width: 100%; /* Full width */
            border-right: 2px solid #ccc; /* light gray border between panels */
            padding-right: 20px;
            margin-right: 10px;
            margin-left: 10px;
        }

        .finals {
            display: flex;
            flex-direction: column; 
            justify-content: left;
            align-items: left;
            width: 100%; /* Full width */
            padding-right: 20px;
            margin-right: 10px;
            margin-left: 10px;
        }
        
        a {
            font-size: 1.4rem;
            color: #333; /* Darker text for better readability */
            margin-top: 10px; /* Space between header and paragraph */
        }

        /* Shared style for all form elements */
        #attendance-form input[type="text"],
        #attendance-form textarea,
        #attendance-form input[type="file"],
        #accomplishment-form input[type="text"],
        #accomplishment-form textarea,
        #accomplishment-form input[type="file"],
        #finals-form input[type="text"],
        #finals-form textarea,
        #finals-form input[type="file"] {
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: 8px;
            border: 2px solid #000;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-family: 'Inria Serif', serif;
            transition: all 0.3s ease;
        }

        #attendance-form input[type="text"]:focus,
        #attendance-form textarea:focus,
        #attendance-form input[type="file"]:focus,
        #accomplishment-form input[type="text"]:focus,
        #accomplishment-form textarea:focus,
        #accomplishment-form input[type="file"]:focus,
        #finals-form input[type="text"]:focus,
        #finals-form textarea:focus,
        #finals-form input[type="file"]:focus {
            outline: none;
            border-color: #ff69b4;
            box-shadow: 0 0 5px #ff69b4;
        }

        .finals-buttons {
            color: black;
            background-color: #FBAF41;
            padding: 8px 15px;
            border: 2px solid rgb(0, 0, 0);
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 15px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Student Portfolio</h1>
        <hr>
        <div class="papers-header">
            <div class="attendance">
                <h2>Attendance Reports</h2>
                <button class="attendance-buttons" onclick="toggleForm()">Add Attendance Report</button>

                <div id="attendance-form" style="display: none; margin-top: 10px;">
                    <form action="/upload/attendance" method="POST" enctype="multipart/form-data">
                        <label for="fileName">File Name:</label><br>
                        <input type="text" id="fileName" name="fileName" required><br><br>

                        <input type="file" name="attendanceFile" accept=".pdf,.xls,.xlsx" required><br><br>

                        <button class="attendance-buttons" type="submit">Submit</button>
                        <button class="attendance-buttons" type="button" onclick="toggleForm()">Cancel</button>
                    </form>
                </div>

                <a href="/your/link/here" style="text-decoration: underline;">Sample Report here (Clickable to download file)</a>
                <a href="/your/link/here" style="text-decoration: underline;">Attendance Report Intern 1</a>
            </div>

            <div class="accomplishments">
                <h2>Accomplishment Reports</h2>
                <button class="accomplishment-buttons" onclick="toggleAccomplishmentForm()">Add Accomplishment Report</button>

                <div id="accomplishment-form" style="display: none; margin-top: 10px;">
                    <form action="/upload/accomplishment" method="POST" enctype="multipart/form-data">
                        <label for="accomplishmentFileName">File Name:</label><br>
                        <input type="text" id="accomplishmentFileName" name="fileName" required><br><br>

                        <input type="file" name="accomplishmentFile" accept=".pdf,.xls,.xlsx" required><br><br>

                        <button class="accomplishment-buttons" type="submit">Submit</button>
                        <button class="accomplishment-buttons" type="button" onclick="toggleAccomplishmentForm()">Cancel</button>
                    </form>
                </div>
            </div>
<<<<<<< HEAD

            <div class="finals">
                <h2>Finals Report</h2>
                <button class="finals-buttons" onclick="toggleFinalsForm()">Add Finals Report</button>

                <div id="finals-form" style="display: none; margin-top: 10px;">
                    <form action="/upload/finals" method="POST" enctype="multipart/form-data">
                        <label for="finalsFileName">File Name:</label><br>
                        <input type="text" id="finalsFileName" name="fileName" required><br><br>

                        <input type="file" name="finalsFile" accept=".pdf,.xls,.xlsx" required><br><br>

                        <button class="finals-buttons" type="submit">Submit</button>
                        <button class="finals-buttons" type="button" onclick="toggleFinalsForm()">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleForm() {
            const form = document.getElementById('attendance-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        function toggleAccomplishmentForm() {
            const form = document.getElementById('accomplishment-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        function toggleFinalsForm() {
            const form = document.getElementById('finals-form');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
=======
    </div> -->
>>>>>>> 966dfaa2e0626283c142547d971dd565c1d32e0d
