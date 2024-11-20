<?php
include '../incl/ShaConnect.php';

// Fetching all classes from the database for the search dropdown
$classSql = "SELECT DISTINCT class_name FROM class";
$classResult = $conn->query($classSql);
$classes = [];

if ($classResult && $classResult->num_rows > 0) {
    while ($row = $classResult->fetch_assoc()) {
        $classes[] = $row['class_name'];
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

// Handling form submission for attendance records
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare and sanitize input
    $class = $_POST['class'];
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester'];
    $total_attendance = intval($_POST['total_attendance']); // Ensuring total attendance is an integer

    // Prepare an SQL statement to insert data safely
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, class, school_year, semester, total_attendance, total_present, total_absent) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Loop through each student's attendance data
    foreach ($_POST['students'] as $student_id => $attendance) {
        $total_present = intval($attendance['total_present']);
        $total_absent = $total_attendance - $total_present;

        // Bind the parameters: student_id, class, school_year, semester, total_attendance, total_present, total_absent
        $stmt->bind_param("isssiii", $student_id, $class, $school_year, $semester, $total_attendance, $total_present, $total_absent);

        // Execute the statement
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
    }

    // Close the statement
    $stmt->close();

    // Redirect to avoid form resubmission
    header('Location: attendance.php');
    exit;
}

// Fetching students based on class selection
$students = [];
if (isset($_GET['class']) && !empty($_GET['class'])) {
    $class = $_GET['class'];
    $studentSql = "SELECT * FROM student WHERE class = ?";
    $stmt = $conn->prepare($studentSql);

    // Bind the class to the prepared statement
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $class);

    // Execute the statement
    if ($stmt->execute()) {
        $studentResult = $stmt->get_result();

        // Fetch the students from the result
        while ($row = $studentResult->fetch_assoc()) {
            $students[] = $row;
        }
    } else {
        die("Error executing student query: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
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
        function calculateAbsent(totalAttendanceInput, presentInput, absentInput) {
            const totalAttendance = parseInt(totalAttendanceInput.value) || 0;
            const totalPresent = parseInt(presentInput.value) || 0;
            const totalAbsent = totalAttendance - totalPresent;

            absentInput.value = totalAbsent >= 0 ? totalAbsent : 0;
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Search Class and Add Attendance</h2>
    <form method="GET">
        <label for="class">Select Class:</label>
        <select name="class" id="class" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $className): ?>
                <option value="<?php echo htmlspecialchars($className); ?>" <?php echo (isset($class) && $class == $className) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($className); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<?php if (!empty($students)): ?>
    <div class="form-container">
        <h2>Attendance Records</h2>
        <form method="POST">
            <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
            
            <label for="school_year">School Year:</label>
            <select name="school_year" required>
                <option value="">Select School Year</option>
                <?php foreach ($schoolYears as $year): ?>
                    <option value="<?php echo htmlspecialchars($year); ?>"><?php echo htmlspecialchars($year); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="total_attendance">Total Attendance:</label>
            <input type="number" name="total_attendance" id="total_attendance" required onchange="updateAllAbsentFields()">

            <label for="semester">Semester:</label>
            <select name="semester" required>
                <option value="">Select Semester</option>
                <?php foreach ($semesters as $sem): ?>
                    <option value="<?php echo htmlspecialchars($sem); ?>"><?php echo htmlspecialchars($sem); ?></option>
                <?php endforeach; ?>
            </select>

            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Class</th>
                    <th>Student Name</th>
                    <th>Total Present</th>
                    <th>Total Absent</th>
                </tr>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($student['class']); ?></td>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td><input type="number" name="students[<?php echo htmlspecialchars($student['student_id']); ?>][total_present]" required oninput="calculateAbsent(document.getElementById('total_attendance'), this, this.parentElement.nextElementSibling.children[0])"></td>
                    <td><input type="number" name="students[<?php echo htmlspecialchars($student['student_id']); ?>][total_absent]" readonly></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <button type="submit">Submit Attendance</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>