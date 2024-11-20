<?php
// Include database connection
include('../incl/ShaConnect.php');

// Function to get unique school years
function getSchoolYears($conn) {
    $sql = "SELECT DISTINCT school_year FROM assessment ORDER BY school_year ASC";
    $result = $conn->query($sql);
    
    $school_years = [];
    while ($row = $result->fetch_assoc()) {
        $school_years[] = $row['school_year'];
    }
    return $school_years;
}

// Function to get unique semesters
function getSemesters($conn) {
    $sql = "SELECT DISTINCT semester FROM assessment ORDER BY semester ASC";
    $result = $conn->query($sql);
    
    $semesters = [];
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row['semester'];
    }
    return $semesters;
}

// Function to get all unique classes based on selected school year and semester
function getClasses($conn, $school_year, $semester) {
    $sql = "SELECT DISTINCT class FROM assessment WHERE school_year = ? AND semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $school_year, $semester);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row['class'];
    }
    return $classes;
}

// Function to get subjects for a particular class, school year, and semester
function getSubjects($conn, $class, $school_year, $semester) {
    $sql = "SELECT DISTINCT subject FROM assessment WHERE class = ? AND school_year = ? AND semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $class, $school_year, $semester);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row['subject'];
    }
    return $subjects;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid blue;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        label {
            margin-right: 10px;
        }
        select {
            padding: 5px;
            min-width: 150px;
        }
        button {
            background-color: blue;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: darkgreen;
        }
        .class-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 3 columns */
            gap: 20px; /* space between boxes */
            justify-items: center; /* center items in each grid cell */
            margin: 20px;
        }
        .class-box {
            border: 2px solid blue;
            padding: 20px;
            width: 200px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .class-box h3 {
            margin-bottom: 10px;
        }
        .subject-list {
            list-style-type: none;
            padding: 0;
        }
        .subject-list li {
            background-color: #f9f9f9;
            margin: 5px 0;
            padding: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="school_year">School Year:</label>
                <select id="school_year" name="school_year" required>
                    <option value="">Select School Year</option>
                    <?php
                    // Fetch and display school years in dropdown
                    $school_years = getSchoolYears($conn);
                    foreach ($school_years as $year) {
                        echo '<option value="' . htmlspecialchars($year) . '">' . htmlspecialchars($year) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="semester">Semester:</label>
                <select id="semester" name="semester" required>
                    <option value="">Select Semester</option>
                    <?php
                    // Fetch and display semesters in dropdown
                    $semesters = getSemesters($conn);
                    foreach ($semesters as $sem) {
                        echo '<option value="' . htmlspecialchars($sem) . '">' . htmlspecialchars($sem) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <button type="submit">Search</button>
        </div>
    </form>
</div>

<div class="class-container">
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $school_year = $_POST['school_year'];
        $semester = $_POST['semester'];

        // Fetch classes
        $classes = getClasses($conn, $school_year, $semester);

        // Loop through each class and display the subjects
        foreach ($classes as $class) {
            $subjects = getSubjects($conn, $class, $school_year, $semester);
            echo '<div class="class-box">';
            echo '<h3>' . htmlspecialchars($class) . '</h3>';
            echo '<ul class="subject-list">';
            foreach ($subjects as $subject) {
                echo '<li>' . htmlspecialchars($subject) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    ?>
</div>

</body>
</html>