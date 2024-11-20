<?php
// Database connection
include '../incl/ShaConnect.php'; // Ensure this file contains your database connection details

// Function to promote students
function promoteStudents($currentYear) {
    global $conn; // Use the connection from dbconnection.php

    // Validate the current year format
    if (!preg_match('/^\d{4}\/\d{2}$/', $currentYear)) {
        echo "Invalid school year format. Please use the format YYYY/YY (e.g., 2023/24).";
        return;
    }

    // Split current year to calculate the next school year
    list($yearStart, $yearEnd) = explode('/', $currentYear);
    $nextYearStart = (int)$yearStart + 1;
    $nextYearEnd = str_pad((int)$yearEnd + 1, 2, '0', STR_PAD_LEFT);
    $nextYear = $nextYearStart . '/' . $nextYearEnd;

    // Promote students in all first-year classes (e.g., 1A, 1B, etc.)
    $sql = "SELECT * FROM student WHERE school_year = '$currentYear' AND class LIKE '1%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Promote from 1 to 2 in the class field (e.g., 1A -> 2A)
            $newClass = preg_replace('/^1/', '2', $row['class']);
            $studentId = $row['student_id'];

            // Update class and school year for first-year students
            $updateSql = "UPDATE student SET class = '$newClass', school_year = '$nextYear' WHERE student_id = $studentId";
            if (!$conn->query($updateSql)) {
                echo "Error updating record for student ID $studentId: " . $conn->error;
            }
        }
    }

    // Promote students in all second-year classes (e.g., 2A, 2B, etc.)
    $sql = "SELECT * FROM student WHERE school_year = '$currentYear' AND class LIKE '2%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Promote from 2 to 3 in the class field (e.g., 2A -> 3A)
            $newClass = preg_replace('/^2/', '3', $row['class']);
            $studentId = $row['student_id'];

            // Update class and school year for second-year students
            $updateSql = "UPDATE student SET class = '$newClass', school_year = '$nextYear' WHERE student_id = $studentId";
            if (!$conn->query($updateSql)) {
                echo "Error updating record for student ID $studentId: " . $conn->error;
            }
        }
    }

    // Graduate students in all third-year classes (e.g., 3A, 3B, etc.)
    $sql = "SELECT * FROM student WHERE class LIKE '3%' AND school_year = '$currentYear'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Move to graduate table
            $insertSql = "INSERT INTO graduate (index_number, name, date_of_birth, admission_date, gender, hall, program, class, school_year, parent, contact, location, passport_picture, password) VALUES (
                '{$row['index_number']}', '{$row['name']}', '{$row['date_of_birth']}', '{$row['admission_date']}', '{$row['gender']}', '{$row['hall']}', '{$row['program']}', '{$row['class']}', '{$row['school_year']}', '{$row['parent']}', '{$row['contact']}', '{$row['location']}', '{$row['passport_picture']}', '{$row['password']}'
            )";
            if (!$conn->query($insertSql)) {
                echo "Error inserting into graduate table for student ID {$row['student_id']}: " . $conn->error;
            }

            // Move attendance records to graduate attendance
            $attendanceSql = "INSERT INTO graduate_attendance (student_id, attendance_date, status) SELECT student_id, attendance_date, status FROM attendance WHERE student_id = {$row['student_id']}";
            if (!$conn->query($attendanceSql)) {
                echo "Error moving attendance for student ID {$row['student_id']}: " . $conn->error;
            }

            // Move assessment records to graduate assessment
            $assessmentSql = "INSERT INTO graduate_assessment (student_id, subject_id, score) SELECT student_id, subject_id, score FROM assessment WHERE student_id = {$row['student_id']}";
            if (!$conn->query($assessmentSql)) {
                echo "Error moving assessment for student ID {$row['student_id']}: " . $conn->error;
            }

            // Delete the student from the student table
            $deleteSql = "DELETE FROM student WHERE student_id = {$row['student_id']}";
            if (!$conn->query($deleteSql)) {
                echo "Error deleting student ID {$row['student_id']}: " . $conn->error;
            }
        }
    }

    echo "Students promoted successfully from $currentYear to $nextYear.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $schoolYear = $_POST['school_year'];
    promoteStudents($schoolYear);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promote Students</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS file for styling -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4; /* Light gray background */
        }
        .container {
            border: 2px solid green; /* Green border */
            padding: 20px;
            border-radius: 10px;
            background-color: white; /* White background for the form */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for better appearance */
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin-bottom: 10px;
        }
        input {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 80%; /* Adjust width as needed */
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: green; /* Green button */
            color: white; /* White text */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: darkgreen; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Promote Students</h1>
        <form method="post" action="">
            <label for="school_year">Enter School Year (e.g., 2023/24):</label>
            <input type="text" id="school_year" name="school_year" required>
            <button type="submit">Promote</button>
        </form>
    </div>
</body>
</html>
