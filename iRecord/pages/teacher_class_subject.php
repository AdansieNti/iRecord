<?php
// Database connection (assumed to be in dbconnection.php)
include '../incl/ShaConnect.php';

// Initialize variables
$teacher_class_subjects = [];
$teachers = [];
$classes = [];
$subjects = [];

// Fetch existing associations
$query = "SELECT * FROM teacher_class_subject";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $teacher_class_subjects[] = $row;
    }
}

// Fetch teachers
$query = "SELECT name FROM teacher";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $teachers[] = $row['name'];
    }
}

// Fetch classes
$query = "SELECT class_name FROM class";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $classes[] = $row['class_name'];
    }
}

// Fetch subjects
$query = "SELECT subject_name FROM subject";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $subjects[] = $row['subject_name'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['teacher_name']);
    $class = mysqli_real_escape_string($conn, $_POST['class_name']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject_name']);

    // Insert or update logic based on whether an ID is provided
    if (!empty($_POST['teacherClassSubjectId'])) {
        $id = mysqli_real_escape_string($conn, $_POST['teacherClassSubjectId']);
        $update_query = "UPDATE teacher_class_subject SET name='$name', class='$class', subject='$subject' WHERE id='$id'";
        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "INSERT INTO teacher_class_subject (name, class, subject) VALUES ('$name', '$class', '$subject')";
        mysqli_query($conn, $insert_query);
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM teacher_class_subject WHERE id='$id'";
    mysqli_query($conn, $delete_query);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher-Class-Subject Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 40%;
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
        .form-container .form-fields {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-container .form-fields label {
            flex: 1;
            text-align: right;
            margin-right: 10px;
        }
        .form-container .form-fields select,
        .form-container .form-fields input {
            flex: 2;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            display: block;
            margin: 0 auto; /* Center the button */
        }
        .form-container button:hover {
            background-color: darkblue;
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
            background-color: darkblue;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add/Edit Teacher-Class-Subject Association</h2>
    <form method="POST">
        <input type="hidden" name="teacherClassSubjectId" id="teacherClassSubjectId" value="">
        <div class="form-fields">
            <label for="teacher_name">Teacher Name:</label>
            <select name="teacher_name" id="teacher_name" required>
                <option value="">Select Teacher</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?php echo htmlspecialchars($teacher); ?>"><?php echo htmlspecialchars($teacher); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-fields">
            <label for="class_name">Class:</label>
            <select name="class_name" id="class_name" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($class); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-fields">
            <label for="subject_name">Subject:</label>
            <select name="subject_name" id="subject_name" required>
                <option value="">Select Subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo htmlspecialchars($subject); ?>"><?php echo htmlspecialchars($subject); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>

<div class="table-container">
    <h2>Existing Teacher-Class-Subject Associations</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Teacher Name</th>
            <th>Class Name</th>
            <th>Subject Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($teacher_class_subjects as $tcs): ?>
            <tr>
                <td><?php echo htmlspecialchars($tcs['id']); ?></td>
                <td><?php echo htmlspecialchars($tcs['name']); ?></td>
                <td><?php echo htmlspecialchars($tcs['class']); ?></td>
                <td><?php echo htmlspecialchars($tcs['subject']); ?></td>
                <td>
                    <button onclick="editTcs(<?php echo $tcs['id']; ?>, '<?php echo addslashes($tcs['name']); ?>', '<?php echo addslashes($tcs['class']); ?>', '<?php echo addslashes($tcs['subject']); ?>')">Edit</button>
                    <a href="?delete=<?php echo $tcs['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
    function editTcs(id, teacherName, className, subjectName) {
        document.getElementById('teacherClassSubjectId').value = id;
        document.getElementById('teacher_name').value = teacherName;
        document.getElementById('class_name').value = className;
        document.getElementById('subject_name').value = subjectName;
    }
</script>

</body>
</html>
