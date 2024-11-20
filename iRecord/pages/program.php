<?php
include '../incl/ShaConnect.php';

// Fetching programs from the database
$sql = "SELECT * FROM program";
$result = $conn->query($sql);
$programs = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
    }
}

// Handling form submission for adding/editing programs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $program_id = isset($_POST['program_id']) ? $_POST['program_id'] : null;
    $program_name = $_POST['program'];
    
    if ($program_id) {
        // Update existing program
        $sql = "UPDATE program SET program = '$program_name' WHERE id = $program_id";
        $conn->query($sql);
    } else {
        // Insert new program
        $sql = "INSERT INTO program (program) VALUES ('$program_name')";
        $conn->query($sql);
    }

    header('Location: program.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a program
if (isset($_GET['delete'])) {
    $program_id = $_GET['delete'];
    $sql = "DELETE FROM program WHERE id = $program_id";
    $conn->query($sql);
    header('Location: program.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Management</title>
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
    <h2>Add / Edit Program</h2>
    <form id="programForm" method="POST">
        <input type="hidden" name="program_id" id="programId">
        <table>
            <tr>
                <td><label for="program">Program Name:</label></td>
                <td><input type="text" name="program" id="program" required></td>
            </tr>
            
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Programs List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Program Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($programs as $program): ?>
        <tr>
            <td><?php echo $program['id']; ?></td>
            <td><?php echo $program['program']; ?></td>
            <td>
                <button onclick="editProgram(<?php echo $program['id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $program['id']; ?>" onclick="return confirm('Are you sure you want to delete this program?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editProgram(id) {
    // Load program data into the form for editing
    var programs = <?php echo json_encode($programs); ?>;
    var programData = programs.find(function(programObj) {
        return programObj.id == id;
    });

    if (programData) {
        document.getElementById('programId').value = programData.id;
        document.getElementById('program').value = programData.program;
    }
}
</script>

</body>
</html>
