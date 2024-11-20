<?php
include '../incl/ShaConnect.php';

// Fetching all classes from the database for the search dropdown
$classSql = "SELECT DISTINCT class FROM student";
$classResult = $conn->query($classSql);
$classes = [];

if ($classResult && $classResult->num_rows > 0) {
    while ($row = $classResult->fetch_assoc()) {
        $classes[] = $row['class'];
    }
}

// Fetching school years from the database
$schoolYearSql = "SELECT DISTINCT school_year FROM school_year";
$schoolYearResult = $conn->query($schoolYearSql);
$schoolYears = [];

if ($schoolYearResult && $schoolYearResult->num_rows > 0) {
    while ($row = $schoolYearResult->fetch_assoc()) {
        $schoolYears[] = $row['school_year'];
    }
}

// Fetching semesters from the database
$semesterSql = "SELECT DISTINCT semester_name FROM semester";
$semesterResult = $conn->query($semesterSql);
$semesters = [];

if ($semesterResult && $semesterResult->num_rows > 0) {
    while ($row = $semesterResult->fetch_assoc()) {
        $semesters[] = $row['semester_name'];
    }
}

// Fetching subjects from the database
$subjectSql = "SELECT DISTINCT subject_name FROM subject";
$subjectResult = $conn->query($subjectSql);
$subjects = [];

if ($subjectResult && $subjectResult->num_rows > 0) {
    while ($row = $subjectResult->fetch_assoc()) {
        $subjects[] = $row['subject_name'];
    }
}

// Handling form submission for weekly operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class = $_POST['class'];
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];
    $week = $_POST['week'];
    $wkop_type = $_POST['wkop_type'];

    foreach ($_POST['students'] as $student_id => $data) {
        $score = $data['score'];

        $sql = "INSERT INTO wkop (student_id, class, school_year, semester, week, wkop_type, subject, score) 
                VALUES ('$student_id', '$class', '$school_year', '$semester', '$week', '$wkop_type', '$subject', '$score')";
        $conn->query($sql);
    }

    header('Location: wkop.php'); // Redirect to avoid form resubmission
    exit;
}

// Fetching students based on class selection
$students = [];
$class = ''; // Initialize class variable
if (isset($_GET['class']) && !empty($_GET['class'])) {
    $class = $_GET['class'];
    $studentSql = "SELECT * FROM student WHERE class = '$class'";
    $studentResult = $conn->query($studentSql);

    if ($studentResult && $studentResult->num_rows > 0) {
        while ($row = $studentResult->fetch_assoc()) {
            $students[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Operation Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .form-container, .table-container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: green;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid green;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
            font-size: 12px;
        }
        input[type="number"] {
            width: 60px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Search Class and Submit Weekly Work Output Score</h2>
    <form method="GET">
        <label for="class">Select Class:</label>
        <select name="class" id="class" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $className): ?>
                <option value="<?php echo $className; ?>" <?php echo ($class == $className) ? 'selected' : ''; ?>>
                    <?php echo $className; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<div class="form-container">
    <h2>Weekly Work Output Score Form</h2>
    <form method="POST">
        <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>"> <!-- Include the class in POST -->
        <label for="school_year">School Year:</label>
        <select name="school_year" id="school_year" required>
            <option value="">Select School Year</option>
            <?php foreach ($schoolYears as $year): ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="semester">Term:</label>
        <select name="semester" id="semester" required>
            <option value="">Select Semester</option>
            <?php foreach ($semesters as $semesterName): ?>
                <option value="<?php echo $semesterName; ?>"><?php echo $semesterName; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="week">Week:</label>
        <select name="week" id="week" required>
            <option value="">Select Week</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="week<?php echo $i; ?>">Week <?php echo $i; ?></option>
            <?php endfor; ?>
        </select>

        <label for="wkop_type">Work Type:</label>
        <select name="wkop_type" id="wkop_type" required>
            <option value="">Select Score Type</option>
            <option value="Class Exercise">Class Exercise</option>
            <option value="Class Test">Class Test</option>
        </select>

        <label for="subject">Subject:</label>
        <select name="subject" id="subject" required>
            <option value="">Select Subject</option>
            <?php foreach ($subjects as $subjectName): ?>
                <option value="<?php echo $subjectName; ?>"><?php echo $subjectName; ?></option>
            <?php endforeach; ?>
        </select>

        <table>
            <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['student_id']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><input type="number" name="students[<?php echo $student['student_id']; ?>][score]" min="0" max="100"></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No students found for the selected class.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <button type="submit">Submit Score</button>
    </form>
</div>

</body>
</html>
