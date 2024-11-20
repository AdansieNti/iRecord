<?php
include '../incl/ShaConnect.php';

// Fetching school years from the database
$sql = "SELECT * FROM school_year";
$result = $conn->query($sql);
$school_years = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $school_years[] = $row;
    }
}

// Handling form submission for adding/editing school years
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_year_id = isset($_POST['school_year_id']) ? $_POST['school_year_id'] : null;
    $school_year = $_POST['school_year'];

    if ($school_year_id) {
        // Update existing school year
        $sql = "UPDATE school_year SET school_year = '$school_year' WHERE school_year_id = $school_year_id";
        $conn->query($sql);
    } else {
        // Insert new school year
        $sql = "INSERT INTO school_year (school_year) VALUES ('$school_year')";
        $conn->query($sql);
    }

    header('Location: school_year.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a school year
if (isset($_GET['delete'])) {
    $school_year_id = $_GET['delete'];
    $sql = "DELETE FROM school_year WHERE school_year_id = $school_year_id";
    $conn->query($sql);
    header('Location: school_year.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Year Management</title>
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
            width: 60%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid blue;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Manage School Year</h2>
        <form method="POST" action="">
            <table>
                <tr>
                    <td><label for="school_year">School Year:</label></td>
                    <td><input type="text" name="school_year" id="school_year" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="table-container">
        <h3>School Years</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($school_years as $year): ?>
                <tr>
                    <td><?php echo $year['school_year_id']; ?></td>
                    <td><?php echo $year['school_year']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $year['school_year_id']; ?>">Edit</a> |
                        <a href="?delete=<?php echo $year['school_year_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
