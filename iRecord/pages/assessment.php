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

// Handling form submission for assessment records
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class = $_POST['class'];  // Get class from POST data
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];

    foreach ($_POST['students'] as $student_id => $areas) {
        $area_1 = $areas['area_1'];
        $area_2 = $areas['area_2'];

        // Calculate total score
        $total = $area_1 + $area_2;

        // Determine grade
        if ($total > 79 && $total <= 100) {
            $grade = '1';
            $remark = 'Excellent';
        } elseif ($total >= 70 && $total < 80) {
            $grade = '2';
            $remark = 'Very Good';
        } elseif ($total >= 65 && $total < 70) {
            $grade = '3';
            $remark = 'Good';
        } elseif ($total >= 60 && $total < 65) {
            $grade = '4';
            $remark = 'Pass';
        } elseif ($total >= 55 && $total < 60) {
            $grade = '5';
            $remark = 'Pass';
        } elseif ($total >= 50 && $total < 55) {
            $grade = '6';
            $remark = 'Pass';
        } elseif ($total >= 45 && $total < 50) {
            $grade = '7';
            $remark = 'Pass';
        } elseif ($total >= 40 && $total < 45) {
            $grade = '8';
            $remark = 'Pass';
        } else {
            $grade = '9';
            $remark = 'Fail';
        }

        $sql = "INSERT INTO assessment (student_id, class, school_year, semester, subject, area_1, area_2, total, grade, remark) 
                VALUES ('$student_id', '$class', '$school_year', '$semester', '$subject', '$area_1', '$area_2', '$total', '$grade', '$remark')";
        $conn->query($sql);
    }

    header('Location: assessment.php'); // Redirect to avoid form resubmission
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
    <title>Assessment Management</title>
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
            color: blue;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid blue;
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
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button:hover {
            background-color: darkblue;
        }
    </style>
    <script>
        function calculateTotal(studentId) {
            const area1 = parseFloat(document.querySelector(`input[name="students[${studentId}][area_1]"]`).value) || 0;
            const area2 = parseFloat(document.querySelector(`input[name="students[${studentId}][area_2]"]`).value) || 0;

            const total = area1 + area2;
            document.getElementById(`total-${studentId}`).innerText = total;

            // Determine grade
            let grade = '';
            if (total > 79 && total <= 100) {
                grade = '1';
            } else if (total >= 70 && total < 80) {
                grade = '2';
            } else if (total >= 65 && total < 70) {
                grade = '3';
            } else if (total >= 60 && total < 65) {
                grade = '4';
            } else if (total >= 55 && total < 60) {
                grade = '5';
            } else if (total >= 50 && total < 55) {
                grade = '6';
            } else if (total >= 45 && total < 50) {
                grade = '7';
            } else if (total >= 40 && total < 45) {
                grade = '8';
            } else {
                grade = '9';
            }
            document.getElementById(`grade-${studentId}`).innerText = grade;
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Search Class and Add Assessment</h2>
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
    <h2>Assessment Form</h2>
    <form method="POST">
        <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">  <!-- Include the class in POST -->
        <label for="school_year">Select School Year:</label>
        <select name="school_year" id="school_year" required>
            <option value="">Select School Year</option>
            <?php foreach ($schoolYears as $year): ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="semester">Select Semester:</label>
        <select name="semester" id="semester" required>
            <option value="">Select Semester</option>
            <?php foreach ($semesters as $semesterName): ?>
                <option value="<?php echo $semesterName; ?>"><?php echo $semesterName; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="subject">Select Subject:</label>
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
                <th>Area 1</th>
                <th>Area 2</th>
                <th>Total</th>
                <th>Grade</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['student_id']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><input type="number" name="students[<?php echo $student['student_id']; ?>][area_1]" min="0" max="100" oninput="calculateTotal(<?php echo $student['student_id']; ?>)"></td>
                        <td><input type="number" name="students[<?php echo $student['student_id']; ?>][area_2]" min="0" max="100" oninput="calculateTotal(<?php echo $student['student_id']; ?>)"></td>
                        <td id="total-<?php echo $student['student_id']; ?>">0</td>
                        <td id="grade-<?php echo $student['student_id']; ?>"></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No students found for the selected class.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <button type="submit">Submit Assessment</button>
    </form>
</div>

</body>
</html>
