<?php
include '../incl/ShaConnect.php';

// Fetching classes for the dropdown
$classSql = "SELECT DISTINCT class FROM student";
$classResult = $conn->query($classSql);
$classes = [];

if ($classResult && $classResult->num_rows > 0) {
    while ($row = $classResult->fetch_assoc()) {
        $classes[] = $row['class'];
    }
}

// Fetching school years for the dropdown
$schoolYearSql = "SELECT DISTINCT school_year FROM school_year";
$schoolYearResult = $conn->query($schoolYearSql);
$schoolYears = [];

if ($schoolYearResult && $schoolYearResult->num_rows > 0) {
    while ($row = $schoolYearResult->fetch_assoc()) {
        $schoolYears[] = $row['school_year'];
    }
}

// Fetching semesters for the dropdown
$semesterSql = "SELECT DISTINCT semester_name FROM semester";
$semesterResult = $conn->query($semesterSql);
$semesters = [];

if ($semesterResult && $semesterResult->num_rows > 0) {
    while ($row = $semesterResult->fetch_assoc()) {
        $semesters[] = $row['semester_name'];
    }
}

// Fetching all subjects
$subjectSql = "SELECT DISTINCT subject_name FROM subject";
$subjectResult = $conn->query($subjectSql);
$subjects = [];

if ($subjectResult && $subjectResult->num_rows > 0) {
    while ($row = $subjectResult->fetch_assoc()) {
        $subjects[] = $row['subject_name'];
    }
}

// Handling form submission and report generation
$studentsReport = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['class_from'], $_GET['class_to'], $_GET['school_year'], $_GET['semester'])) {
    $class_from = $_GET['class_from'];
    $class_to = $_GET['class_to'];
    $school_year = $_GET['school_year'];
    $semester = $_GET['semester'];

    // Fetching students and their grades for a range of classes
    $studentSql = "SELECT DISTINCT s.student_id, s.name, s.class
                   FROM student s
                   JOIN quiz q ON s.student_id = q.student_id
                   WHERE s.class BETWEEN '$class_from' AND '$class_to' 
                   AND q.school_year = '$school_year' 
                   AND q.semester = '$semester'";
    $studentResult = $conn->query($studentSql);

    if ($studentResult && $studentResult->num_rows > 0) {
        while ($studentRow = $studentResult->fetch_assoc()) {
            $student_id = $studentRow['student_id'];
            $studentReport = [
                'student_name' => $studentRow['name'],
                'class' => $studentRow['class'],
                'grades' => [],
                'aggregate' => 0
            ];

            $coreSubjects = ['English Language', 'Mathematics', 'Integrated Science', 'Social Studies'];
            $optionalSubjects = [];
            $totalGrades = 0;

            // Fetching grades for each subject
            foreach ($subjects as $subject) {
                $gradeSql = "SELECT grade FROM quiz WHERE student_id = '$student_id' AND subject = '$subject' AND school_year = '$school_year' AND semester = '$semester'";
                $gradeResult = $conn->query($gradeSql);
                $grade = 0;

                if ($gradeResult && $gradeResult->num_rows > 0) {
                    $gradeRow = $gradeResult->fetch_assoc();
                    $grade = (int)$gradeRow['grade']; // Assuming the grades are integers
                }

                $studentReport['grades'][$subject] = $grade;

                // Summing grades for core subjects
                if (in_array($subject, $coreSubjects) && $grade > 0) {
                    $totalGrades += $grade;
                } elseif ($grade > 0) {
                    // Collect optional subjects excluding "Social Studies"
                    $optionalSubjects[] = $grade;
                }
            }

            // Sorting optional subjects to pick the best two (lowest grades)
            sort($optionalSubjects);
            $bestTwoOptional = array_slice($optionalSubjects, 0, 2);

            // Summing the best two optional grades
            foreach ($bestTwoOptional as $bestGrade) {
                $totalGrades += $bestGrade;
            }

            // Aggregate calculation for core subjects + best two optional
            $studentReport['aggregate'] = $totalGrades;

            $studentsReport[] = $studentReport;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Report</title>
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
        .print-button {
            float: left;
            padding: 10px 20px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: darkgreen;
        }
    </style>
    <script>
        function printReport() {
            // Hide the form container
            document.querySelector('.form-container').style.display = 'none';
            // Show the report
            document.querySelector('.table-container').style.display = 'block';
            // Print the report
            window.print();
            // Restore the form after printing
            document.querySelector('.form-container').style.display = 'block';
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Generate Class Report</h2>
    <form method="GET">
        <label for="class_from">Select Class From:</label>
        <select name="class_from" id="class_from" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $className): ?>
                <option value="<?php echo $className; ?>" <?php echo (isset($class_from) && $class_from == $className) ? 'selected' : ''; ?>>
                    <?php echo $className; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="class_to">Select Class To:</label>
        <select name="class_to" id="class_to" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $className): ?>
                <option value="<?php echo $className; ?>" <?php echo (isset($class_to) && $class_to == $className) ? 'selected' : ''; ?>>
                    <?php echo $className; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="school_year">Select School Year:</label>
        <select name="school_year" id="school_year" required>
            <option value="">Select School Year</option>
            <?php foreach ($schoolYears as $year): ?>
                <option value="<?php echo $year; ?>" <?php echo (isset($school_year) && $school_year == $year) ? 'selected' : ''; ?>>
                    <?php echo $year; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="semester">Select Semester:</label>
        <select name="semester" id="semester" required>
            <option value="">Select Semester</option>
            <?php foreach ($semesters as $semesterName): ?>
                <option value="<?php echo $semesterName; ?>" <?php echo (isset($semester) && $semester == $semesterName) ? 'selected' : ''; ?>>
                    <?php echo $semesterName; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Generate Report</button>
    </form>
</div>

<?php if (!empty($studentsReport)): ?>
    <div class="table-container">
        <button class="print-button" onclick="printReport()">Print Report</button>
        <h2>Class Report</h2>
        <table>
            <thead>
            <tr>
                <th>Student Name</th>
                <th>Class</th>
                <?php foreach ($subjects as $subject): ?>
                    <th><?php echo $subject; ?></th>
                <?php endforeach; ?>
                <th>Aggregate</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($studentsReport as $student): ?>
                <tr>
                    <td><?php echo $student['student_name']; ?></td>
                    <td><?php echo $student['class']; ?></td>
                    <?php foreach ($subjects as $subject): ?>
                        <td><?php echo $student['grades'][$subject]; ?></td>
                    <?php endforeach; ?>
                    <td><?php echo $student['aggregate']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</body>
</html>
