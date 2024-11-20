<?php
include '../incl/ShaConnect.php';

// Fetching remarks from the database
$sql = "SELECT * FROM remark";
$result = $conn->query($sql);
$remarks = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $remarks[] = $row;
    }
}

// Handling form submission for adding/editing remarks
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $remark_id = isset($_POST['remark_id']) ? $_POST['remark_id'] : null;
    $remark = $_POST['remark'];
    
    if ($remark_id) {
        // Update existing remark
        $sql = "UPDATE remark SET remark = '$remark' WHERE id = $remark_id";
        $conn->query($sql);
    } else {
        // Insert new remark
        $sql = "INSERT INTO remark (remark) VALUES ('$remark')";
        $conn->query($sql);
    }

    header('Location: remark.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a remark
if (isset($_GET['delete'])) {
    $remark_id = $_GET['delete'];
    $sql = "DELETE FROM remark WHERE id = $remark_id";
    $conn->query($sql);
    header('Location: remark.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remark Management</title>
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
    <h2>Add / Edit Remark</h2>
    <form id="remarkForm" method="POST">
        <input type="hidden" name="remark_id" id="remarkId">
        <table>
            <tr>
                <td><label for="remark">Remark:</label></td>
                <td><input type="text" name="remark" id="remark" required></td>
            </tr>
            
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Remarks List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Remark</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($remarks as $remark): ?>
        <tr>
            <td><?php echo $remark['id']; ?></td>
            <td><?php echo $remark['remark']; ?></td>
            <td>
                <button onclick="editRemark(<?php echo $remark['id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $remark['id']; ?>" onclick="return confirm('Are you sure you want to delete this remark?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editRemark(id) {
    // Load remark data into the form for editing
    var remarks = <?php echo json_encode($remarks); ?>;
    var remarkData = remarks.find(function(remarkObj) {
        return remarkObj.id == id;
    });

    if (remarkData) {
        document.getElementById('remarkId').value = remarkData.id;
        document.getElementById('remark').value = remarkData.remark;
    }
}
</script>

</body>
</html>
