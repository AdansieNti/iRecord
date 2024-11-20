<?php
// db.php should contain code to connect to your database
include '../incl/ShaConnect.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'download_template') {
        // Create CSV template
        $csvTemplate = "student_id,index_number,name,date_of_birth,admission_date,gender,hall,program,class,school_year,parent,contact,location,passport_picture,password\n";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_template.csv"');
        echo $csvTemplate;
        exit;
    }

    if ($action == 'download_data') {
        // Fetch data from the `student` table
        $query = "SELECT * FROM student";
        $result = mysqli_query($conn, $query);

        // Create a CSV from the data
        $csvData = "student_id,index_number,name,date_of_birth,admission_date,gender,hall,program,class,school_year,parent,contact,location,passport_picture,password\n";

        while ($row = mysqli_fetch_assoc($result)) {
            $csvData .= implode(",", array_map('strval', $row)) . "\n";
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_data.csv"');
        echo $csvData;
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['csv_file'])) {
        $file = $_FILES['csv_file']['tmp_name'];

        if (($handle = fopen($file, "r")) !== false) {
            // Skip the header row
            fgetcsv($handle, 1000, ",");

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Prepare data for insert
                $student_id = $data[0];
                $index_number = $data[1];
                $name = $data[2];
                $date_of_birth = $data[3];
                $admission_date = $data[4];
                $gender = $data[5];
                $hall = $data[6];
                $program = $data[7];
                $class = $data[8];
                $school_year = $data[9];
                $parent = $data[10];
                $contact = $data[11];
                $location = $data[12];
                $passport_picture = $data[13];
                $password = $data[14];

                // Insert data into the student table
                $insertQuery = "INSERT INTO student (student_id, index_number, name, date_of_birth, admission_date, gender, hall, program, class, school_year, parent, contact, location, passport_picture, password) VALUES ('$student_id', '$index_number', '$name', '$date_of_birth', '$admission_date', '$gender', '$hall', '$program', '$class', '$school_year', '$parent', '$contact', '$location', '$passport_picture', '$password')";
                mysqli_query($conn, $insertQuery);
            }

            fclose($handle);
            echo "CSV data successfully imported!";
        } else {
            echo "Failed to open uploaded CSV file.";
        }
    } else {
        echo "No CSV file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSV Upload/Download</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        h1 {
            text-align: center;
        }
        .button-link {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            color: white;
            text-decoration: none;
            background-color: blue;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .button-link:hover {
            background-color: white;
        }
        form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Student Data Downloads and Uploads</h1>

    <div>
        <h2>Download CSV Template</h2>
        <a href="?action=download_template" class="button-link">Download Template</a>
        
        <h2>Upload CSV Data</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file" required>
            <input type="submit" value="Upload CSV" class="button-link">
        </form>

        <h2>Download Table Data</h2>
        <a href="?action=download_data" class="button-link">Download Current Data</a>
    </div>
</body>
</html>
