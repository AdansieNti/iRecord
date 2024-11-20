<?php
session_start(); // Start the session to access username

// Check if the user is logged in
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>i-Record</title>
    <link rel="stylesheet" href="styles.css"> <!-- External stylesheet for styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header, footer {
            background-color: #696969;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            left: 0;
            z-index: 10;
        }

        .header {
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header img {
            margin-right: 10px;
            width: 80px;
            height: 80px;
        }

        .header h1 {
            font-size: 5em;
            margin: 0;
        }

        .header h1 span.i {
            color: red;
        }

        .header h1 span.-record {
            color: blue;
        }

        .header .username {
            position: absolute;
            right: 10px;
            top: 20px;
        }

        footer {
            bottom: 0;
            
        }

        .nav-links {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            gap: 5px;
            position: relative;
            margin-top: 120px; /* To compensate for fixed header height */
        }

        .nav-links a,
        .nav-links button {
            text-decoration: none;
            padding: 10px;
            color: white;
            border-radius: 5px;
            font-size: 12px;
            width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
        }

        .blue {
            background-color: green;
        }

        .blue {
            background-color: blue;
            color: black;
            border: 1px solid green;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #f4f4f4;
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
            font-size: 12px;
            text-align: center;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .main-content {
            flex: 1;
            display: flex;
            height: calc(100vh - 200px); /* Total height excluding header and footer */
            overflow: hidden;
        }

        .left {
            width: 20%;
            background-color: #696969;
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
            transition: width 0.3s;
            overflow-y: auto;
        }

        .left a {
            display: block;
            padding: 5px;
            margin: 5px 0;
            text-decoration: none;
            color: white;
            text-align: center;
            font-size: 14px;
        }

        .left img {
            width: 80%;
            height: 20%;
            margin-top: 20px;
        }

        .right {
            width: 80%;
            padding: 10px;
            box-sizing: border-box;
            border-left: 1px solid #ccc;
            transition: width 0.3s;
            overflow-y: auto;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .hide-button {
            margin-right: 5px;
            background-color: white;
            color: white;
        }

        .hidden-left {
            width: 0;
            overflow: hidden;
            padding: 0;
        }

        .hidden-right {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../img/shalem.png" alt="Logo">
        <h1><span class="i">i</span><span class="-record">-Record</span></h1>
        <?php if ($username): ?>
            <div class="username">Welcome, <?php echo htmlspecialchars($username); ?>!</div>
        <?php endif; ?>
    </div>

    <div class="nav-links">
        <button class="hide-button" onclick="toggleSidebar()">Hide Sidebar</button>
        <a href="../pages/ahome.php" target="displayFrame" class="blue">Home</a>
        <div class="dropdown">
            <a href="#" class="blue">Manage Student</a>
            <div class="dropdown-content">
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Student</a>
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">Student CVSs </a>
                <a href="../pages/constructions.php" target="displayFrame" class="blue">Not Set</a>
            </div>
        </div>
        <div class="dropdown">
            <a href="#" class="blue">Manage Teacher</a>
            <div class="dropdown-content">
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Teacher</a>
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">Assign</a>
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">View Assigned</a>
                <a href="../pages/constructions.php" target="displayFrame" class="blue">Not Set</a>
            </div>
        </div>
        <div class="dropdown">
            <a href="#" class="blue">Assessment</a>
            <div class="dropdown-content">
                <a href="../pages/assessment.php" target="displayFrame" class="blue">Add Assessment</a>
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">Check Assessment</a>
                <a href="../pages/quiz.php" target="displayFrame" class="blue">Quizzes & Mocks</a>
                <a href="../pages/prohibited.php" target="displayFrame" class="blue">Q & M Report</a>
                <a href="../pages/wkop.php" target="displayFrame" class="bluen"> Add Weekly Score</a>
                <a href="../pages/prohibited.php" target="displayFrame" class="bluen">Geneate 50% Score</a>
                <a href="../pages/wkopdld.php" target="displayFrame" class="blue"> View Weekly Scoret</a>
            </div>
        </div>
        <a href="../pages/prohibited.php" target="displayFrame" class="blue">Report</a>
        <a href="../pages/constructions.php" target="displayFrame" class="blue">Transcript</a>
        <a href="../pages/analysis.php" target="displayFrame" class="blue">Analysis</a>
        <a href="../pages/prohibited.php" target="displayFrame" class="blue">Promotion</a>
        <a href="../index.php" class="blue">Logout</a>
    </div>

    <div class="main-content">
        <div class="left" id="leftSidebar">
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Class</a>
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Subject</a>
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Program</a>
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Hall</a>
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add School Year</a>
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Add Semester</a>
            <a href="../pages/attendance.php" target="displayFrame" class="blue">Add Attendance</a>
            <a href="../pages/behavior.php" target="displayFrame" class="blue">Add Conduct / Interest / Remarks</a>
            <a href="../pages/interest.php" target="displayFrame" class="blue">Add Interest</a>
            <a href="../pages/conduct.php" target="displayFrame" class="blue">Add Conduct</a>
            <a href="../pages/remark.php" target="displayFrame" class="blue">Add Remarks</a>
            <a href="../pages/prohibited.php" target="displayFrame" class="blue">Manage Users</a>
            
            <img src="../img/shalemlg1.png" alt="My Logo">
            </div>

        <div class="right" id="rightContent">
            <iframe name="displayFrame"></iframe>
        </div>
    </div>

    <footer>
       <?php include('footer.php'); ?>
    </footer>

    <script>
        function toggleSidebar() {
            const leftSidebar = document.getElementById('leftSidebar');
            const rightContent = document.getElementById('rightContent');
            const hideButton = document.querySelector('.hide-button');

            if (leftSidebar.classList.contains('hidden-left')) {
                leftSidebar.classList.remove('hidden-left');
                rightContent.classList.remove('hidden-right');
                hideButton.textContent = 'Hide Sidebar';
            } else {
                leftSidebar.classList.add('hidden-left');
                rightContent.classList.add('hidden-right');
                hideButton.textContent = 'Show Sidebar';
            }
        }
    </script>
</body>
</html>
