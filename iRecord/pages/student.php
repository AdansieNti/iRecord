<?php
include '../incl/ShaConnect.php';

// Fetching students from the database
$search = isset($_POST['search']) ? $_POST['search'] : '';
$sql = "SELECT * FROM student WHERE name LIKE '%$search%' OR index_number LIKE '%$search%'";
$result = $conn->query($sql);
$students = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Handling form submission for adding/editing students
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['search'])) {
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    $index_number = $_POST['index_number'];
    $name = $_POST['name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $hall = $_POST['hall'];
    $program = $_POST['program'];
    $class = $_POST['class'];
    $school_year = $_POST['school_year'];
    $parent = $_POST['parent'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];
    $passport_picture = $_POST['passport_picture'];
    $password = $_POST['password'];

    if ($student_id) {
        // Update existing student
        $sql = "UPDATE student SET index_number = '$index_number', name = '$name', date_of_birth = '$date_of_birth', gender = '$gender', hall = '$hall', program = '$program', class = '$class', school_year = '$school_year', parent = '$parent', contact = '$contact', location = '$location', passport_picture = '$passport_picture', password = '$password' WHERE student_id = $student_id";
        $conn->query($sql);
    } else {
        // Insert new student
        $sql = "INSERT INTO student (index_number, name, date_of_birth, gender, hall, program, class, school_year, parent, contact, location, passport_picture, password) VALUES ('$index_number', '$name', '$date_of_birth', '$gender', '$hall', '$program', '$class', '$school_year', '$parent', '$contact', '$location', '$passport_picture', '$password')";
        $conn->query($sql);
    }

    header('Location: student.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a student
if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];
    $sql = "DELETE FROM student WHERE student_id = $student_id";
    $conn->query($sql);
    header('Location: student.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            border: 2px solid blue;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: blue;
        }
        .form-container table {
            width: 100%;
            border-spacing: 10px;
        }
        .form-container td {
            padding: 5px;
        }
        .form-container label {
            text-align: right;
            padding-right: 1px; /* Adjusted padding to bring input closer */
        }
        .form-container input {
            width: 100%;
            padding: 5px;
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
            background-color: blue;
        }
        .table-container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container h2 {
            text-align: center;
            color: blue;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table, .table-container th, .table-container td {
            border: 1px solid blue;
        }
        .table-container th, .table-container td {
            padding: 10px;
            text-align: center;
        }
        .table-container th {
            background-color: #f0f0f0;
        }
        .table-container a {
            color: red;
            text-decoration: none;
        }
        .table-container a:hover {
            text-decoration: underline;
        }
        .table-container button {
            padding: 5px 10px;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .table-container button:hover {
            background-color: blue;
        }
        .search-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add / Edit Student</h2>
    <form id="studentForm" method="POST">
        <input type="hidden" name="student_id" id="studentId">
        <table>
            <tr>
                <td><label for="index_number">Index Number:</label></td>
                <td><input type="text" name="index_number" id="index_number" required></td>
                <td><label for="name">Name:</label></td>
                <td><input type="text" name="name" id="name" required></td>
            </tr>
            <tr>
                <td><label for="date_of_birth">Date of Birth:</label></td>
                <td><input type="date" name="date_of_birth" id="date_of_birth" required></td>
                <td><label for="gender">Gender:</label></td>
                <td><input type="text" name="gender" id="gender" required></td>
            </tr>
            <tr>
                <td><label for="hall">Hall:</label></td>
                <td><input type="text" name="hall" id="hall" required></td>
                <td><label for="program">Program:</label></td>
                <td><input type="text" name="program" id="program" required></td>
            </tr>
            <tr>
                <td><label for="class">Class:</label></td>
                <td><input type="text" name="class" id="class" required></td>
                <td><label for="school_year">School Year:</label></td>
                <td><input type="text" name="school_year" id="school_year" required></td>
            </tr>
            <tr>
                <td><label for="parent">Parent:</label></td>
                <td><input type="text" name="parent" id="parent" required></td>
                <td><label for="contact">Contact:</label></td>
                <td><input type="text" name="contact" id="contact" required></td>
            </tr>
            <tr>
                <td><label for="location">Location:</label></td>
                <td><input type="text" name="location" id="location" required></td>
                <td><label for="passport_picture">Passport Picture:</label></td>
                <td><input type="text" name="passport_picture" id="passport_picture"></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" name="password" id="password" required></td>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Students List</h2>
    
    <div class="search-container">
        <form method="POST">
            <input type="text" name="search" placeholder="Search by name or index number" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Index Number</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?php echo $student['student_id']; ?></td>
            <td><?php echo $student['index_number']; ?></td>
            <td><?php echo $student['name']; ?></td>
            <td>
                <button onclick="editStudent(<?php echo $student['student_id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $student['student_id']; ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editStudent(id) {
    // Load the student details into the form for editing
    var student = <?php echo json_encode($students); ?>;
    for (var i = 0; i < student.length; i++) {
        if (student[i].student_id == id) {
            document.getElementById('studentId').value = student[i].student_id;
            document.getElementById('index_number').value = student[i].index_number;
            document.getElementById('name').value = student[i].name;
            document.getElementById('date_of_birth').value = student[i].date_of_birth;
            document.getElementById('gender').value = student[i].gender;
            document.getElementById('hall').value = student[i].hall;
            document.getElementById('program').value = student[i].program;
            document.getElementById('class').value = student[i].class;
            document.getElementById('school_year').value = student[i].school_year;
            document.getElementById('parent').value = student[i].parent;
            document.getElementById('contact').value = student[i].contact;
            document.getElementById('location').value = student[i].location;
            document.getElementById('passport_picture').value = student[i].passport_picture;
            document.getElementById('password').value = student[i].password;
            break;
        }
    }
}
</script>

</body>
</html>
