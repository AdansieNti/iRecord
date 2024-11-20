<?php
include '../incl/ShaConnect.php';

// Fetching interests from the database
$sql = "SELECT * FROM interest";
$result = $conn->query($sql);
$interests = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $interests[] = $row;
    }
}

// Handling form submission for adding/editing interests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $interest_id = isset($_POST['interest_id']) ? $_POST['interest_id'] : null;
    $interest = $_POST['interest'];

    if ($interest_id) {
        // Update existing interest
        $sql = "UPDATE interest SET interest = '$interest' WHERE id = $interest_id";
        $conn->query($sql);
    } else {
        // Insert new interest
        $sql = "INSERT INTO interest (interest) VALUES ('$interest')";
        $conn->query($sql);
    }

    header('Location: interest.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of an interest
if (isset($_GET['delete'])) {
    $interest_id = $_GET['delete'];
    $sql = "DELETE FROM interest WHERE id = $interest_id";
    $conn->query($sql);
    header('Location: interest.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interest Management</title>
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
            background-color: green;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add / Edit Interest</h2>
    <form id="interestForm" method="POST">
        <input type="hidden" name="interest_id" id="interestId">
        <table>
            <tr>
                <td><label for="interest">Interest:</label></td>
                <td><input type="text" name="interest" id="interest" required></td>
            </tr>
            
            <tr>
                <td colspan="2" style="text-align: center;"><button type="submit">Submit</button></td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Interests List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Interest</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($interests as $interest): ?>
        <tr>
            <td><?php echo $interest['id']; ?></td>
            <td><?php echo $interest['interest']; ?></td>
            <td>
                <button onclick="editInterest(<?php echo $interest['id']; ?>)">Edit</button>
                <a href="?delete=<?php echo $interest['id']; ?>" onclick="return confirm('Are you sure you want to delete this interest?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
function editInterest(id) {
    // Load interest data into the form for editing
    var interests = <?php echo json_encode($interests); ?>;
    var interestData = interests.find(function(interestObj) {
        return interestObj.id == id;
    });

    if (interestData) {
        document.getElementById('interestId').value = interestData.id;
        document.getElementById('interest').value = interestData.interest;
    }
}
</script>

</body>
</html>
