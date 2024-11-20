<?php
// Include database connection
include '../incl/ShaConnect.php'; // Ensure this file establishes a connection to your database

// Fetch distinct values for dropdowns
$class_options = $conn->query("SELECT DISTINCT class FROM wkop");
$year_options = $conn->query("SELECT DISTINCT school_year FROM wkop");
$semester_options = $conn->query("SELECT DISTINCT semester FROM wkop");
$subject_options = $conn->query("SELECT DISTINCT subject FROM wkop");

// Fetch student names
$students_data = $conn->query("SELECT student_id, name FROM student"); // Use the correct table name
$students_names = [];
while ($row = $students_data->fetch_assoc()) {
    $students_names[$row['student_id']] = $row['name'];
}

$students_scores = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_class = $_POST['class'];
    $selected_year = $_POST['school_year'];
    $selected_semester = $_POST['semester'];
    $selected_subject = $_POST['subject'];

    // Prepare the SQL query to fetch scores based on the selected criteria
    $query = "SELECT student_id, week, score FROM wkop WHERE class = ? AND school_year = ? AND semester = ? AND subject = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $selected_class, $selected_year, $selected_semester, $selected_subject);
    $stmt->execute();
    $result = $stmt->get_result();

    // Organize scores by student_id
    while ($row = $result->fetch_assoc()) {
        $students_scores[$row['student_id']][$row['week']] = $row['score'];
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Scores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        select, button {
            padding: 10px;
            margin: 5px;
            border: 2px solid green;
            border-radius: 5px;
        }
        button {
            background-color: green;
            color: white;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid green;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Student Scores</h1>
        <form method="POST">
            <select name="class" required>
                <option value="">Select Class</option>
                <?php while ($row = $class_options->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($row['class']); ?>"><?php echo htmlspecialchars($row['class']); ?></option>
                <?php } ?>
            </select>

            <select name="school_year" required>
                <option value="">Select School Year</option>
                <?php while ($row = $year_options->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($row['school_year']); ?>"><?php echo htmlspecialchars($row['school_year']); ?></option>
                <?php } ?>
            </select>

            <select name="semester" required>
                <option value="">Select Semester</option>
                <?php while ($row = $semester_options->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($row['semester']); ?>"><?php echo htmlspecialchars($row['semester']); ?></option>
                <?php } ?>
            </select>

            <select name="subject" required>
                <option value="">Select Subject</option>
                <?php while ($row = $subject_options->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($row['subject']); ?>"><?php echo htmlspecialchars($row['subject']); ?></option>
                <?php } ?>
            </select>

            <button type="submit">Search</button>
        </form>

        <?php if (!empty($students_scores)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Week 1</ th>
                        <th >Week 2</th>
                        <th>Week 3</th>
                        <th>Week 4</th>
                        <th>Week 5</th>
                        <th>Week 6</th>
                        <th>Week 7</th>
                        <th>Week 8</th>
                        <th>Week 9</th>
                        <th>Week 10</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students_scores as $student_id => $scores) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($students_names[$student_id]); ?></td>
                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                <td><?php echo isset($scores['Week ' . $i]) ? $scores['Week ' . $i] : 'N/A'; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>
</html>