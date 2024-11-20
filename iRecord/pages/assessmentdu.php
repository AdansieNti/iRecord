<?php
include '../incl/ShaConnect.php';

// Fetch distinct classes from the assessment table
$classQuery = "SELECT DISTINCT class FROM assessment";
$classResult = $conn->query($classQuery);

// Handle CSV generation
if (isset($_POST['class']) && !isset($_POST['upload'])) {
    $class = $_POST['class'];

    // Fetch students in the selected class
    $sql = "SELECT student_id, name FROM student WHERE class = '$class'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Set the CSV headers
        $csvHeaders = [
            'student_id', 'student_name', 'school_year', 'semester', 'subject',
            'area_1', 'area_2', 'area_3', 'area_4', 'area_5', 'ctotal',
            'area_6', 'etotal', 'total', 'grade', 'remark'
        ];

        // Create the CSV file and output
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="assessment_template.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, $csvHeaders);

        // Add rows with student info
        while ($row = $result->fetch_assoc()) {
            $csvRow = [
                $row['student_id'],
                $row['name'],
                '', // school_year
                '', // semester
                '', // subject
                '', '', '', '', '', '', // areas 1-5 and ctotal
                '', '', '', '', // area_6, etotal, total, grade, remark
            ];
            fputcsv($output, $csvRow);
        }

        fclose($output);
        exit;
    } else {
        echo "No students found for the selected class.";
    }
}

// Handle CSV upload
if (isset($_POST['upload'])) {
    if ($_FILES['csv']['error'] == 0) {
        $file = $_FILES['csv']['tmp_name'];
        $handle = fopen($file, 'r');

        // Skip the header
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $student_id = $data[0];
            $school_year = $data[2];
            $semester = $data[3];
            $subject = $data[4];
            $area_1 = $data[5];
            $area_2 = $data[6];
            $area_3 = $data[7];
            $area_4 = $data[8];
            $area_5 = $data[9];
            $ctotal = $data[10];
            $area_6 = $data[11];
            $etotal = $data[12];
            $total = $data[13];
            $grade = $data[14];
            $remark = $data[15];

            // Insert into the assessment table
            $sql = "INSERT INTO assessment (student_id, school_year, semester, subject, area_1, area_2, area_3, area_4, area_5, ctotal, area_6, etotal, total, grade, remark) 
                    VALUES ('$student_id', '$school_year', '$semester', '$subject', '$area_1', '$area_2', '$area_3', '$area_4', '$area_5', '$ctotal', '$area_6', '$etotal', '$total', '$grade', '$remark')";
            
            if ($conn->query($sql) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        fclose($handle);
        echo "Data uploaded successfully!";
    } else {
        echo "Failed to upload file.";
    }
}

// Close connection
$conn->close();
?>

<!-- HTML form to select the class and upload CSV -->
<form method="POST" enctype="multipart/form-data">
    <label for="class">Select Class:</label>
    <select name="class" id="class">
        <?php
        // Populate the class dropdown dynamically
        if ($classResult->num_rows > 0) {
            while ($classRow = $classResult->fetch_assoc()) {
                echo "<option value='" . $classRow['class'] . "'>" . $classRow['class'] . "</option>";
            }
        } else {
            echo "<option value=''>No classes available</option>";
        }
        ?>
    </select>
    <button type="submit">Generate CSV Template</button>
</form>

<hr>

<form enctype="multipart/form-data" method="POST">
    <label for="csv">Upload CSV File:</label>
    <input type="file" name="csv" id="csv" accept=".csv">
    <button type="submit" name="upload">Upload</button>
</form>
