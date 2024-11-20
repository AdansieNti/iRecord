<?php
include '../incl/ShaConnect.php';

// Fetching halls from the database
$sql = "SELECT * FROM hall";
$result = $conn->query($sql);
$halls = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $halls[] = $row;
    }
}

// Handling form submission for adding/editing halls
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hall_id = isset($_POST['hall_id']) ? $_POST['hall_id'] : null;
    $hall_name = $_POST['hall_name'];
    
    if ($hall_id) {
        // Update existing hall
        $sql = "UPDATE hall SET hall = '$hall_name' WHERE id = $hall_id";
        $conn->query($sql);
    } else {
        // Insert new hall
        $sql = "INSERT INTO hall (hall) VALUES ('$hall_name')";
        $conn->query($sql);
    }

    header('Location: hall.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a hall
if (isset($_GET['delete'])) {
    $hall_id = $_GET['delete'];
    $sql = "DELETE FROM hall WHERE id = $hall_id";
    $conn->query($sql);
    header('Location: hall.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Management</title>
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
    <h2>Add / Edit Hall</h2>
    <form id="hallForm" method="POST">
        <input type="hidden" name="hall_id" id="hallId">
        <table>
            <tr>
                <td><label for="hall_name">Hall Name:</label></td>
                <td><input type="text" name="hall_name" id="hall_name" required></td>
            </tr>
            
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Halls List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Hall Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($halls as $hall): ?>
        <tr>
            <td><?php echo $hall['id']; ?></td>
            <td><?php echo $hall['hall']; ?></td>
            <td>
                <button onclick="editHall(<?php echo $hall['id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $hall['id']; ?>" onclick="return confirm('Are you sure you want to delete this hall?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editHall(id) {
    // Load hall data into the form for editing
    var halls = <?php echo json_encode($halls); ?>;
    var hallData = halls.find(function(hallObj) {
        return hallObj.id == id;
    });

    if (hallData) {
        document.getElementById('hallId').value = hallData.id;
        document.getElementById('hall_name').value = hallData.hall;
    }
}
</script>

</body>
</html>
