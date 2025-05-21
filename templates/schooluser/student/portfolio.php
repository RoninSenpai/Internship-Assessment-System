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
        border: 2px solid rgb(0, 0, 0); /* <-- that's your borderline */
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
        </style>
</head>

<body>
    <div class="header">
        <h1>Student Portfolio</h1>
        <hr>
            <div class="papers-header">
                <div class="attendance">
                    <h2>Attendance Reports</h2>
                        <button class="attendance-buttons" onclick="window.location.href='/schooluser/student/attendance'">Add Attendance</button>

                        <a href="/your/link/here" style="text-decoration: underline;">Sample Report here (Clickable to download file)</a>
                        <a href="/your/link/here" style="text-decoration: underline;">Attendance Report Intern 1</a>
                </div>
                <div class="accomplishments">
                    <h2>Accomplishment Reports</h2>
                        <button class="attendance-buttons" onclick="window.location.href='/schooluser/student/accomplishments'">Add Accomplishment Reports</button>
                </div>
                <div class="finals">
                    <h2>Finals Report</h2>
                        <button class="attendance-buttons" onclick="window.location.href='/schooluser/student/finals'">Add Finals Reports</button>
                </div>
            </div>
    </div>