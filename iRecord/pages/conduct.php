<?php
include '../incl/ShaConnect.php';

// Fetching conduct records from the database
$sql = "SELECT * FROM conduct";
$result = $conn->query($sql);
$conducts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $conducts[] = $row;
    }
}

// Handling form submission for adding/editing conduct
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conduct_id = isset($_POST['conduct_id']) ? $_POST['conduct_id'] : null;
    $conduct_name = $_POST['conduct_name'];
    
    if ($conduct_id) {
        // Update existing conduct
        $sql = "UPDATE conduct SET conduct = '$conduct_name' WHERE id = $conduct_id";
        $conn->query($sql);
    } else {
        // Insert new conduct
        $sql = "INSERT INTO conduct (conduct) VALUES ('$conduct_name')";
        $conn->query($sql);
    }

    header('Location: conduct.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a conduct record
if (isset($_GET['delete'])) {
    $conduct_id = $_GET['delete'];
    $sql = "DELETE FROM conduct WHERE id = $conduct_id";
    $conn->query($sql);
    header('Location: conduct.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conduct Management</title>
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
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add / Edit Conduct</h2>
    <form id="conductForm" method="POST">
        <input type="hidden" name="conduct_id" id="conductId">
        <table>
            <tr>
                <td><label for="conduct_name">Conduct:</label></td>
                <td><input type="text" name="conduct_name" id="conduct_name" required></td>
            </tr>
            
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Conduct List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Conduct</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($conducts as $conduct): ?>
        <tr>
            <td><?php echo $conduct['id']; ?></td>
            <td><?php echo $conduct['conduct']; ?></td>
            <td>
                <button onclick="editConduct(<?php echo $conduct['id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $conduct['id']; ?>" onclick="return confirm('Are you sure you want to delete this conduct?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editConduct(id) {
    // Load conduct data into the form for editing
    var conducts = <?php echo json_encode($conducts); ?>;
    var conductData = conducts.find(function(conductObj) {
        return conductObj.id == id;
    });

    if (conductData) {
        document.getElementById('conductId').value = conductData.id;
        document.getElementById('conduct_name').value = conductData.conduct;
    }
}
</script>

</body>
</html>
