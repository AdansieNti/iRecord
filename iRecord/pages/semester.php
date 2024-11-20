<?php
include '../incl/ShaConnect.php';

// Fetching semesters from the database
$sql = "SELECT * FROM semester";
$result = $conn->query($sql);
$semesters = [];

// Fetching existing semesters
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row;
    }
}

// Handling form submission for adding/editing semesters
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $semester_id = isset($_POST['semester_id']) ? $_POST['semester_id'] : null;
    $semester_name = $_POST['semester_name'];

    if ($semester_id) {
        // Update existing semester
        $sql = "UPDATE semester SET semester_name = '$semester_name' WHERE semester_id = $semester_id";
        $conn->query($sql);
    } else {
        // Insert new semester
        $sql = "INSERT INTO semester (semester_name) VALUES ('$semester_name')";
        $conn->query($sql);
    }

    header('Location: semester.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a semester
if (isset($_GET['delete'])) {
    $semester_id = $_GET['delete'];
    $sql = "DELETE FROM semester WHERE semester_id = $semester_id";
    $conn->query($sql);
    header('Location: semester.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Management</title>
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
        .form-container table {
            width: 100%;
            border-spacing: 10px;
        }
        .form-container td {
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
    <h2>Add / Edit Semester</h2>
    <form id="semesterForm" method="POST">
        <input type="hidden" name="semester_id" id="semesterId">
        <table>
            <tr>
                <td><label for="semester_name">Semester Name:</label></td>
                <td><input type="text" name="semester_name" id="semester_name" required></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Semesters List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Semester Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($semesters as $semester): ?>
        <tr>
            <td><?php echo $semester['semester_id']; ?></td>
            <td><?php echo $semester['semester_name']; ?></td>
            <td>
                <button onclick="editSemester(<?php echo $semester['semester_id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $semester['semester_id']; ?>" onclick="return confirm('Are you sure you want to delete this semester?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editSemester(id) {
    // Load semester data into the form for editing
    var semesters = <?php echo json_encode($semesters); ?>;
    var semesterData = semesters.find(function(semObj) {
        return semObj.semester_id == id;
    });

    if (semesterData) {
        document.getElementById('semesterId').value = semesterData.semester_id;
        document.getElementById('semester_name').value = semesterData.semester_name;
    }
}
</script>

</body>
</html>
