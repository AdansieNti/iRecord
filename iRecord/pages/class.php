<?php
include '../incl/ShaConnect.php';

// Fetching classes from the database
$sql = "SELECT * FROM class";
$result = $conn->query($sql);
$classes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

// Handling form submission for adding/editing classes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;
    $class_name = $_POST['class_name'];
    
    if ($class_id) {
        // Update existing class
        $sql = "UPDATE class SET class_name = '$class_name' WHERE class_id = $class_id";
        $conn->query($sql);
    } else {
        // Insert new class
        $sql = "INSERT INTO class (class_name) VALUES ('$class_name')";
        $conn->query($sql);
    }

    header('Location: class.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a class
if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $sql = "DELETE FROM class WHERE class_id = $class_id";
    $conn->query($sql);
    header('Location: class.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 40%;
            margin: 30px auto;
            padding: 20px;
            border: 2px solid green;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: green;
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
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button:hover {
            background-color: green;
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
            border: 1px solid green;
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
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .table-container button:hover {
            background-color: green;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add / Edit Class</h2>
    <form id="classForm" method="POST">
        <input type="hidden" name="class_id" id="classId">
        <table>
            <tr>
                <td><label for="class_name">Class Name:</label></td>
                <td><input type="text" name="class_name" id="class_name" required></td>
            </tr>
            
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Classes List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Class Name</th>
             <th>Actions</th>
        </tr>
        <?php foreach ($classes as $class): ?>
        <tr>
            <td><?php echo $class['class_id']; ?></td>
            <td><?php echo $class['class_name']; ?></td>
            <td>
                <button onclick="editClass(<?php echo $class['class_id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $class['class_id']; ?>" onclick="return confirm('Are you sure you want to delete this class?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editClass(id) {
    // Load class data into the form for editing
    var classes = <?php echo json_encode($classes); ?>;
    var classData = classes.find(function(classObj) {
        return classObj.class_id == id;
    });

    if (classData) {
        document.getElementById('classId').value = classData.class_id;
        document.getElementById('class_name').value = classData.class_name;
       }
}
</script>

</body>
</html>
