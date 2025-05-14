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

        .profile-header {
            display: flex;
            flex-direction: row; 
            justify-content: left;
            align-items: center;
            width: 100%; /* Full width */
        }
        
        .intern-year {
            justify-self: flex-end;
            margin-left: auto; /* Pushes the intern year to the right */
            justify-content: right;
        }

        hr {
        border: none;
        height: 2px;
        background-color: #ff69b4;
        margin: 5px 0;
        }

        .information-panel{
            display: flex;
            flex-direction: row; /* Align child elements horizontally */
            justify-content: flex-start;
            width: 100%; /* Full width */
        }

        .side-panel {
            background-color:rgb(255, 255, 255);
            padding: 10px;  
            height: 100vh; /* Adjust to content height */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column; 
            justify-content: flex-start;
            width: 25%; /* Make sure it takes full width */
            border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top-left-radius: 10px;
                border-bottom-left-radius: 10px;
                margin-right: 10px;
        }

        h2{
            font-size: 28px;
            margin: 0;
        }
        .custom-tree {
        list-style: none;
        padding-left: 5px;
        font-family: sans-serif;
        font-size: 16px;
        }

        .custom-tree summary {
        cursor: pointer;
        font-weight: bold;
        list-style: none;
        font-size: 20px;
        }

        .custom-tree ul {
        padding-left: 2em;
        }

        a{
            text-decoration: none;
            color: #000;
            font-weight: bold;
            font-size: 20px;
        }

        .main-panel{
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
                    margin-left: 10px;
        }
        
        h3{
            font-size: 34px;
            margin: 2;
        }
        p{
            font-size: 20px;
            margin: 5;
        }
        </style>
</head>

<body>
    <div class="header">
        <div class="profile-header">
            <h1>Your Profile</h1>
            <div class="intern-year">
                <h1>Intern Year: INTERN 1</h1> 
                <!-- intern year should be connected to database and will change if student has finised intern 1 -->
            </div>  
        </div>
    <hr>

    <div class="information-panel">
          <div class="side-panel">
        <h2>Student Information</h2>
            <ul class="custom-tree">
                <li>
                    <details>
                        <summary>➤ Basic Information</summary>
                            <ul>
                                <li>Edit Information</li>
                                <li>Add Information</li>
                                <!-- gray out edit when no information and vice versa if information exist -->
                            </ul>
                        </details>
                    </li>
                    <a href="" style="text-decoration: underline;">
                ➤ Status Updating
                </a>
            </div>
        <div class="main-panel">
            <h3>Abonita, Ronin N.</h3>
            <!-- connect to database -->
            <p>SOE - Computer Engineering - BSCPE 231</p>
            <p>Student ID: 2023-12345</p>
            <p>Sex: Male</p>
            <p>Birthday: January 1, 2000 (19 years old)</p>
            <p>Contact Number: 09123456789</p>
            <p>Email:rnabonita@student.apc.edu.ph</p>
            <p>Address: 1234 Sample St., Sample City, Sample Province</p>

            <hr>
            <h3>Internship Status</h3>
            <p>Internship Status: Ongoing</p>
            <p>Internship Start Date: January 1, 2023</p>
            <p>Internship End Date: June 1, 2024</p>
            <p>Department: Sample Department</p>
            <p>Supervisor: Sample Supervisor</p>
            <p>Internship Company: Sample Company</p>

            <p>Please check if status is correct, if not, press Status Updating on side panel</p>
        </div>
  
