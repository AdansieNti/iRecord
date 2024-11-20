<?php include '../incl/ShaConnect.php'; ?>

<!-- Styles -->
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

        h1, h5, h4, h2 {
            text-align: center;
            margin: 0; /* Remove default margin */
        }

        .header-section {
            display: flex;
            align-items: center; /* Align items vertically */
            justify-content: center; /* Center the contents */
            margin-bottom: 20px; /* Space below the header */
        }

        .header-section img {
            width: 1in; /* Set image width to 1 inch */
            height: 1in; /* Set image height to 1 inch */
            margin-right: 10px; /* Space between image and text */
        }

        .header-text {
            text-align: center; /* Center the text */
        }

        h1 {
            font-size: 20px;
            margin-bottom: 2px;
        }

        h5 {
            font-size: 20px;
            margin-bottom: 2px;
        }

        h4 {
            font-size: 12px;
            margin-bottom: 2px;
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            line-height: 0.6;
        }

        th {
            background-color: #f0f0f0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-row td {
            text-align: left;
            padding: 10px;
        }

        .total-row td {
            font-weight: bold;
        }

        .action-buttons {
            display: none;
        }

        .footer-section {
            text-align: center;
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .footer-section img {
            width: 5cm;
            height: 1cm;
        }

        .footer-section p {
            margin-top: 1px;
            font-size: 12px;
        }
    }

    .report-container {
        width: 90%;
        margin: 10px auto;
        padding: 20px;
        border: 2px solid black;
        border-radius: 10px;
        background-color: #f9f9f9;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    .search-form {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .search-form select, .search-form button {
        margin: 5px;
        padding: 10px;
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

<div class="report-container">
    <h2>Student Assessment Report</h2>

    <!-- Search Form -->
    <form method="POST" action="" class="search-form">
        <select id="school_year" name="school_year">
            <option value="">Select School Year</option>
            <?php
            $yearQuery = "SELECT DISTINCT school_year FROM assessment";
            $yearResult = $conn->query($yearQuery);
            if ($yearResult->num_rows > 0) {
                while ($row = $yearResult->fetch_assoc()) {
                    echo "<option value='" . $row['school_year'] . "'>" . $row['school_year'] . "</option>";
                }
            }
            ?>
        </select>

        <select id="semester" name="semester">
            <option value="">Select Semester</option>
            <?php
            $semesterQuery = "SELECT DISTINCT semester FROM assessment";
            $semesterResult = $conn->query($semesterQuery);
            if ($semesterResult->num_rows > 0) {
                while ($row = $semesterResult->fetch_assoc()) {
                    echo "<option value='" . $row['semester'] . "'>" . $row['semester'] . "</option>";
                }
            }
            ?>
        </select>

        <select id="class" name="class">
            <option value="">Select Class</option>
            <?php
            $classQuery = "SELECT DISTINCT class FROM assessment";
            $classResult = $conn->query($classQuery);
            if ($classResult->num_rows > 0) {
                while ($row = $classResult->fetch_assoc()) {
                    echo "<option value='" . $row['class'] . "'>" . $row['class'] . "</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Fetch Report</button>
    </form>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="printReport()">Print Report</button>
        <button onclick="downloadReport()">Download Report</button>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['school_year']) && !empty($_POST['semester']) && !empty($_POST['class'])) {
    $school_year = $conn->real_escape_string($_POST['school_year']);
    $semester = $conn->real_escape_string($_POST['semester']);
    $class = $conn->real_escape_string($_POST['class']);

    // Query to get all student IDs for the specified year, semester, and class
    $studentQuery = "SELECT DISTINCT student_id, class FROM assessment WHERE school_year = '$school_year' AND semester = '$semester'";
    $studentResult = $conn->query($studentQuery);

    $studentScores = [];

    if ($studentResult->num_rows > 0) {
        while ($studentRow = $studentResult->fetch_assoc()) {
            $student_id = $studentRow['student_id'];
            $student_class = $studentRow['class'];

            // Fetch student details
            $studentDetailsQuery = "SELECT * FROM student WHERE student_id = '$student_id'";
            $studentDetailsResult = $conn->query($studentDetailsQuery);
            $studentDetails = $studentDetailsResult->fetch_assoc();

            // Fetch assessment data
            $assessmentQuery = "SELECT * FROM assessment WHERE student_id = '$student_id' AND school_year = '$school_year' AND semester = '$semester'";
            $assessmentResult = $conn->query($assessmentQuery);

            $total_score = 0;
            $area1_total = 0;
            $area2_total = 0;
            $overall_total = 0;

            // Calculate total score for the student
            if ($assessmentResult->num_rows > 0) {
                while ($assessmentRow = $assessmentResult->fetch_assoc()) {
                    $total_score += $assessmentRow['total'];
                    $area1_total += $assessmentRow['area_1'];
                    $area2_total += $assessmentRow['area_2'];
                    $overall_total += $assessmentRow['total'];
                }
            }

            // Store student total scores for ranking
            $studentScores[$student_id] = [
                'name' => $studentDetails['name'],
                'total_score' => $total_score,
                'class' => $student_class,
            ];
        }

        // Rank students based on total scores for each class
        $classRankings = [];
        foreach ($studentScores as $student_id => $details) {
            $classRankings[$details['class']][] = [
                'student_id' => $student_id,
                'name' => $details['name'],
                'total_score' => $details['total_score'],
            ];
        }

        foreach ($classRankings as $form_class => &$students) {
            usort($students, function ($a, $b) {
                return $b['total_score'] <=> $a['total_score'];
            });

            foreach ($students as $position => &$student) {
                $student['position'] = ordinal($position + 1);
                $student['overall_position'] = $position + 1;
            }
        }

        // Now display the reports
        foreach ($studentScores as $student_id => $details) {
            $student_class = $details['class'];
            $rankedStudents = $classRankings[$student_class];
            $studentDetails = $studentScores[$student_id];
            $studentName = $studentDetails['name'];
            $overall_position = null;

            foreach ($rankedStudents as $student) {
                if ($student['student_id'] === $student_id) {
                    $overall_position = $student['position'];
                    break;
                }
            }

            // Fetch attendance data
            $attendanceQuery = "SELECT * FROM attendance WHERE student_id = '$student_id' AND school_year = '$school_year' AND semester = '$semester'";
            $attendanceResult = $conn->query($attendanceQuery);
            $attendance = $attendanceResult->fetch_assoc();

            // Fetch behavior data
            $behaviorQuery = "SELECT * FROM behavior WHERE student_id = '$student_id' AND school_year = '$school_year' AND semester = '$semester'";
            $behaviorResult = $conn->query($behaviorQuery);
            $behavior = $behaviorResult->fetch_assoc();

            ?>
            <div class="report-container" id="report">
                <div class="header-section">
                    <img src="../img/arclg.png" alt="School Logo">
                    <div class="header-text">
                        <h5>GHANA EDUCATION SERVICE</ h5>
                        <h1>ABIRA R/C JHS</h1>
                        <h4>Box 8, Antoa-Krobo, Ashanti. Contact: 00004000000</h4>
                    </div>
                </div>

                <!-- Student Info Section -->
                <table class="info-table">
                    <tr class="info-row">
                        <td>Name: <?php echo $studentName; ?></td>
                        <td>Class: <?php echo $student_class; ?></td>
                    </tr>
                    <tr class="info-row">
                        <td>School Year: <?php echo $school_year; ?></td>
                        <td>Semester: <?php echo $semester; ?></td>
                    </tr>
                    <tr class="info-row">
                        <td>Total Students: <?php echo count($studentScores); ?></td>
                        <td>Position: <?php echo $overall_position; ?></td>
                    </tr>
                    <tr class="info-row">
                        <td>Overall Position: <?php echo $overall_position; ?></td>
                        <td> </td>
                    </tr>
                </table>

                <!-- Subject and Score Table -->
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Class Sc<br>(50%)</th>
                            <th>Exam Score<br>(50%)</th>
                            <th>Total<br>(100%)</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $coreSubjects = ['English Language', 'Mathematics', 'Integrated Science', 'Social Studies'];
                        $electiveSubjects = [];

                        $assessmentQuery = "SELECT * FROM assessment WHERE student_id = '$student_id' AND school_year = '$school_year' AND semester = '$semester'";
                        $assessmentResult = $conn->query($assessmentQuery);

                        if ($assessmentResult->num_rows > 0) {
                            while ($assessmentRow = $assessmentResult->fetch_assoc()) {
                                if (in_array($assessmentRow['subject'], $coreSubjects)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $assessmentRow['subject']; ?></td>
                                        <td><?php echo number_format($assessmentRow['area_1'], 2); ?></td>
                                        <td><?php echo number_format($assessmentRow['area_2'], 2); ?></td>
                                        <td><?php echo number_format($assessmentRow['total'], 2); ?></td>
                                        <td><?php echo $assessmentRow['grade']; ?></td>
                                        <td><?php echo $assessmentRow['remark']; ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    $electiveSubjects[] = $assessmentRow;
                                }
                            }
                        }

                        foreach ($electiveSubjects as $electiveSubject) {
                            ?>
                            <tr>
                                <td><?php echo $electiveSubject['subject']; ?></td>
                                <td><?php echo number_format($electiveSubject['area_1'], 2); ?></td>
                                <td><?php echo number_format($electiveSubject['area_2'], 2); ?></td>
                                <td><?php echo number_format($electiveSubject['total'], 2); ?></td>
                                <td><?php echo $electiveSubject['grade']; ?></td>
                                <td><?php echo $electiveSubject['remark']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                         <!-- Display Grand Total -->
                        <tr class="total-row">
                            <td>Grand Total</td>
                            <td><?php echo number_format($area1_total, 2); ?></td>
                            <td><?php echo number_format($area2_total, 2); ?></td>
                            <td><?php echo number_format($overall_total, 2); ?></td>
                            <td colspan="2">Aggregate:</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Attendance Section -->
                <table>
                    <thead>
                        <tr>
                            <th>Total Attendance</th>
                            <th>Total Present</th>
                            <th>Total Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $attendance['total_attendance']; ?></td>
                            <td><?php echo $attendance['total_present']; ?></td>
                            <td><?php echo $attendance['total_absent']; ?></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Behavior Section -->
                <table>
                    <thead>
                        <tr>
                            <th>Conduct</th>
                            <th>Interest</th>
                            <th>Cooperation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            < td><?php echo $behavior['conduct']; ?></td>
                            <td><?php echo $behavior['interest']; ?></td>
                            <td><?php echo $behavior['remark']; ?></td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <!-- Footer Section -->
                <div class="footer-section">
                    <div>
                        <p>Class Teacher Sign</p>
                        <img src="../img/signature.png" alt="">
                    </div>
                    <div>
                         <p>Headteacher Sign</p>
                        <img src="../img/signature.png" alt="">
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No students found for the selected criteria.</p>";
    }
}

function ordinal($number) {
    $suffix = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    return $number . ($suffix[$number % 10] ?? 'th');
}
?>

<script>
function printReport() {
    window.print();
}
</script>