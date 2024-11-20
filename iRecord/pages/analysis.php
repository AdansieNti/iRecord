<?php
include '../incl/ShaConnect.php';

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects for the grid
$subjects = [];
$subject_sql = "SELECT DISTINCT subject FROM assessment";
$result = $conn->query($subject_sql);
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row['subject'];
}

// Fetch classes for the grid
$classes = [];
$class_sql = "SELECT DISTINCT class FROM assessment";
$result = $conn->query($class_sql);
while ($row = $result->fetch_assoc()) {
    $classes[] = $row['class'];
}

// Fetch school years for the dropdown
$school_years = [];
$year_sql = "SELECT DISTINCT school_year FROM assessment ORDER BY school_year ASC";
$result = $conn->query($year_sql);
while ($row = $result->fetch_assoc()) {
    $school_years[] = $row['school_year'];
}

// Fetch semesters for the dropdown
$semesters = [];
$semester_sql = "SELECT DISTINCT semester FROM assessment ORDER BY semester ASC";
$result = $conn->query($semester_sql);
while ($row = $result->fetch_assoc()) {
    $semesters[] = $row['semester'];
}

// Handle form submission and report generation
$reportGenerated = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subjects']) && isset($_POST['classes'])) {
    $selected_subjects = $_POST['subjects'];
    $selected_classes = $_POST['classes'];
    $school_year_start = $_POST['school_year_start'];
    $school_year_end = $_POST['school_year_end'];
    $semester_start = $_POST['semester_start'];
    $semester_end = $_POST['semester_end'];
    $reportGenerated = true;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grade Report</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .report-container {
                width: 90%;
                margin: 0 auto;
                border: none;
                box-shadow: none;
                page-break-after: always;
            }
            h1, h2, h3, h5 {
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 5px;
                text-align: center;
            }
            th {
                background-color: #f0f0f0;
            }
            .no-print, form {
                display: none !important;
            }
        }

        .report-container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid black;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-row {
            display: flex;
            justify-content: space-around;
            width: 100%;
        }

        .form-row select, .form-row input {
            margin: 10px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .action-buttons button {
            padding: 10px 15px;
            margin: 5px;
        }
    </style>
</head>
<body>

<div class="no-print form-container">
    <h2>Select Criteria</h2>

    <form method="post" action="">
        <div class="form-row">
            <div>
                <label for="school_year_start">School Year (Start):</label>
                <select id="school_year_start" name="school_year_start" required>
                    <?php
                    foreach ($school_years as $year) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="school_year_end">School Year (End):</label>
                <select id="school_year_end" name="school_year_end" required>
                    <?php
                    foreach ($school_years as $year) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div>
                <label for="semester_start">Semester (Start):</label>
                <select id="semester_start" name="semester_start" required>
                    <?php
                    foreach ($semesters as $semester) {
                        echo "<option value='$semester'>$semester</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="semester_end">Semester (End):</label>
                <select id="semester_end" name="semester_end" required>
                    <?php
                    foreach ($semesters as $semester) {
                        echo "<option value='$semester'>$semester</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <h3>Select Subjects:</h3>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;">
            <?php
            foreach ($subjects as $subject) {
                echo "<div><input type='checkbox' name='subjects[]' value='$subject'> $subject</div>";
            }
            ?>
        </div>

        <h3>Select Classes:</h3>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;">
            <?php
            foreach ($classes as $class) {
                echo "<div><input type='checkbox' name='classes[]' value='$class'> $class</div>";
            }
            ?>
        </div>

        <br><input type="submit" value="Generate Report">
    </form>
</div>

<?php if ($reportGenerated): ?>
    <div class="report-container" id="report">
        <h1>Adanwomase Senior High</h1>
        <h2>Subject Grade Analysis and Report</h2>
        <h3>Classes: <?= htmlspecialchars(implode(", ", $selected_classes)) ?></h3>
        <h5>School Year: <?= htmlspecialchars($school_year_start) ?> - <?= htmlspecialchars($school_year_end) ?></h5>
        <h5>Semester Range: <?= htmlspecialchars($semester_start) ?> - <?= htmlspecialchars($semester_end) ?></h5>

        <?php
        foreach ($selected_subjects as $subject) {
            echo "<h3>Subject: $subject</h3>";
            echo "<table>";
            echo "<tr><th>Grade</th><th>Number of Students</th></tr>";

            foreach ($selected_classes as $class) {
                $sql = "SELECT grade, COUNT(*) as num_students 
                        FROM assessment 
                        WHERE subject = '$subject' 
                        AND school_year BETWEEN '$school_year_start' AND '$school_year_end' 
                        AND semester BETWEEN '$semester_start' AND '$semester_end' 
                        AND class = '$class' 
                        GROUP BY grade";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>{$row['grade']}</td><td>{$row['num_students']}</td></tr>";
                    }
                } else {
                    echo "<tr><td>No records found</td><td></td></tr>";
                }
            }

            echo "</table><br>";
        }
        ?>
        <p>This report provides a summary of grades for the selected subjects and classes within the specified school year and semester range.
         <br><br> Recommendation ........................................................................................................................................................................
          <br><br>.......................................................................................................................................................................
          <br><br>.......................................................................................................................................................................
          <br><br>.......................................................................................................................................................................
          <br><br>.......................................................................................................................................................................
         <br><br>.......................................................................................................................................................................<br><br>
         <br><br>Subject Master:......................................<br><br> Signnature:......................................</p>
        <div class="action-buttons no-print">
            <button onclick="printReport()">Print Report</button>
        </div>
    </div>
<?php endif; ?>

<script>
    function printReport() {
        window.print();
    }
</script>

</body>
</html>
