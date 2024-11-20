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

$students = [];
$class = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['class']) && isset($_GET['school_year']) && isset($_GET['semester']) && isset($_GET['subject'])) {
    $class = $_GET['class'];
    $school_year = $_GET['school_year'];
    $semester = $_GET['semester'];
    $subject = $_GET['subject'];

    // Fetch students and calculate 50% of the total score from week 1 to 10 for each student
    $studentSql = "
        SELECT s.student_id, s.name, SUM(w.score) * 0.5 AS calculated_score
        FROM student s
        LEFT JOIN wkop w ON s.student_id = w.student_id
        WHERE s.class = '$class' 
          AND w.school_year = '$school_year' 
          AND w.semester = '$semester' 
          AND w.subject = '$subject' 
          AND w.week BETWEEN 'week1' AND 'week10'
        GROUP BY s.student_id, s.name
    ";
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
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        .form-container, .table-container {
            width: 90%; margin: 20px auto; padding: 20px; background-color: white;
            border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 { text-align: center; color: green; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid green; text-align: center; }
        th { background-color: #f0f0f0; font-size: 12px; }
        .form-container button {
            padding: 10px 20px; background-color: green; color: white; border: none;
            cursor: pointer; border-radius: 5px;
        }
        .form-container button:hover { background-color: darkgreen; }
        .print-button {
            text-align: center; margin: 20px 0;
        }
        @media print {
            .form-container, .print-button { display: none; }
        }
        h3 { text-align: center; font-size: 18px; }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Search Class and View Scores</h2>
    <form method="GET">
        <label for="class">Select Class:</label>
        <select name="class" id="class" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $className): ?>
                <option value="<?php echo $className; ?>" <?php echo (isset($_GET['class']) && $_GET['class'] == $className) ? 'selected' : ''; ?>>
                    <?php echo $className; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="school_year">School Year:</label>
        <select name="school_year" id="school_year" required>
            <option value="">Select School Year</option>
            <?php foreach ($schoolYears as $year): ?>
                <option value="<?php echo $year; ?>" <?php echo (isset($_GET['school_year']) && $_GET['school_year'] == $year) ? 'selected' : ''; ?>>
                    <?php echo $year; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="semester">Semester:</label>
        <select name="semester" id="semester" required>
            <option value="">Select Semester</option>
            <?php foreach ($semesters as $semesterName): ?>
                <option value="<?php echo $semesterName; ?>" <?php echo (isset($_GET['semester']) && $_GET['semester'] == $semesterName) ? 'selected' : ''; ?>>
                    <?php echo $semesterName; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="subject">Subject:</label>
        <select name="subject" id="subject" required>
            <option value="">Select Subject</option>
            <?php foreach ($subjects as $subjectName): ?>
                <option value="<?php echo $subjectName; ?>" <?php echo (isset($_GET['subject']) && $_GET['subject'] == $subjectName) ? 'selected' : ''; ?>>
                    <?php echo $subjectName; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Search</button>
    </form>
</div>

<div class="print-button">
    <button onclick="printReport()">Print Report</button>
</div>

<div class="table-container">
    <h2>Student Scores</h2>
    <?php if ($class): ?>
        <h3>Class: <?php echo htmlspecialchars($class); ?></h3>
    <?php endif; ?>
    <table>
        <thead>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>50% of Total Score (Weeks 1-10)</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['student_id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo number_format($student['calculated_score'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No scores found for the selected criteria.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
