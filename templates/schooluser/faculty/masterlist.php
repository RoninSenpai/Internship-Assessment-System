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
            align-items: flex-start;
            width: 100%; /* Make sure it takes full width */
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
            margin-bottom: 5px;
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

        .mainpanel{
            display: flex;
            flex-direction: row; /* Align child elements horizontally */
            align-items: stretch;
            width: 100%; /* Make sure it takes full width */
        }
        .sidepanel{
            display: flex;
            flex-direction: column; /* Align child elements horizontally */
            justify-content: flex-start;
            align-items: stretch;
            width: 25%; /* Make sure it takes full width */
        }

        .list-panel{
            background-color: #ffffff;
            display: flex;
            flex-direction: column; /* Align child elements horizontally */
            justify-content: flex-start;
            align-items: stretch;
            width: 100%; /* Make sure it takes full width */
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
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
        }

        .custom-tree ul {
        padding-left: 2em;
        }

        .information-panel{
            background-color: #ffffff;
                padding: 10;
                width: 185vh;
                height: 100vh;
                border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top-left-radius: 10px;
                border-bottom-left-radius: 10px;
                align-items: stretch;
                margin-left: 20px;
        }
        .styled-table {
        border-collapse: collapse;
        margin: 3px 0;
        font-size: 16px;
        min-width: 100%;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .styled-table thead tr {
        background-color: #ffaad4;
        color: rgb(0, 0, 0);
        text-align: left;
        }

        .styled-table th, .styled-table td {
        padding: 10px 10px;
        border: 1px solid #eee;
        }

        .styled-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
        }

        .styled-table tbody tr:hover {
        background-color: #f1f1f1;
        }
        
        .information-data-panel-header{
            font-size: 30px;
            margin: 0 0 0 0px;
        }
        .information-data-professors{
            font-size: 16px;
            margin: 0 0 0 0px;
        }

        hr {
        border: none;
        height: 2px;
        background-color: #ff69b4;
        margin: 15px 0;
        }
    
    </style>

</head>

<body>
    <header class="header">
            <div class="back">
                <button onclick="window.location.href='../../../templates/schooluser/faculty/home.html'">Back</button>
            </div>
            <div class="mainpanel">
                <div class="sidepanel">
                    <h1>Student Masterlist</h1>
                    <div class="search-bar">
                        <form action="/supervisor/masterlist" method="GET">
                            <input type="text" name="search" placeholder="Search by student name..." required>
                            <button type="submit">Search</button>
                        </form>
                    </div>
                    <div class="list-panel">
                        <ul class="custom-tree">
                            <li>
                            <details>
                                <summary>▶ School of Engineering</summary>
                                <ul>
                                <li>Computer Engineering</li>
                                <li>Civil Engineering</li>
                                <li>Electronics Engineering</li>
                                </ul>
                            </details>
                            </li>
                            <li>
                            <details>
                                <summary>▶ School of Computing and Information Technology</summary>
                                <ul>
                                <li>Computer Science</li>
                                <li>Information Technology</li>
                                <li>Computer Technology</li>
                                </ul>
                            </details>
                            </li>
                        </ul>
                </div>
                </div>
                <div class="information-panel"> 
                    <table class="styled-table">
                        <colgroup>
                            <col style="width: 3%;">
                            <col style="width: 50%;">
                            <col style="width: 30%;">
                          </colgroup>
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Intern School Year</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td>Denbert Veloria</td>
                            <td>INTERN 1 2024-2025</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>Marvin Kurumphang</td>
                            <td>INTERN 2 2024-2025</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>Julius Aquino</td>
                            <td>INTERN 2 2024-2025</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td>Ronin Abonita</td>
                            <td>INTERN 2 2024-2025</td>
                          </tr>
                        </tbody>
                      </table>

                      <div class="information-data-panel-header">
                        <h2>SCHOOL OF ENGINEERING</h2>
                        <div class="information-data-professors">
                        <p><strong>Executive Director:</strong> Leonardo Samaniego, Jr.</p>
                        <p><strong>Program Director:</strong> Serge Peruda, Jr.</p>
                        <hr>
                        <p><strong>INTERN 1 Students:</strong> 130 ↗</p>
                    </div>
                </div>
    </body>