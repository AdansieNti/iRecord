<?php
include '../incl/ShaConnect.php';

// Fetching teachers from the database
$sql = "SELECT * FROM teacher";
$result = $conn->query($sql);
$teachers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Handling form submission for adding/editing teachers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : null;
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    
    if ($teacher_id) {
        // Update existing teacher
        $sql = "UPDATE teacher SET name = '$name', gender = '$gender', contact = '$contact', password = '$password' WHERE teacher_id = $teacher_id";
        $conn->query($sql);
    } else {
        // Insert new teacher
        $sql = "INSERT INTO teacher (name, gender, contact, password) VALUES ('$name', '$gender', '$contact', '$password')";
        $conn->query($sql);
    }

    header('Location: teacher.php'); // Redirect to avoid form resubmission
    exit;
}

// Handling deletion of a teacher
if (isset($_GET['delete'])) {
    $teacher_id = $_GET['delete'];
    $sql = "DELETE FROM teacher WHERE teacher_id = $teacher_id";
    $conn->query($sql);
    header('Location: teacher.php'); // Redirect to avoid URL manipulation
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 40%;
            margin: 30px auto;
            padding: 20px;
            border: 2px blue;
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
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container th, .table-container td {
            border: 1px blue;
            padding: 8px;
        }
        .table-container th {
            background-color: #f2f2f2;
        }
        .table-container tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Teacher Management</h2>
    <form method="POST" action="">
        <input type="hidden" name="teacher_id" value="">
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td>Gender:</td>
                <td>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Contact:</td>
                <td><input type="text" name="contact" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit">Submit</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<div class="table-container">
    <h2>Teacher List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td><?php echo $teacher['teacher_id']; ?></td>
                    <td><?php echo $teacher['name']; ?></td>
                    <td><?php echo $teacher['gender']; ?></td>
                    <td><?php echo $teacher['contact']; ?></td>
                    <td>
                        <a href="teacher.php?edit=<?php echo $teacher['teacher_id']; ?>">Edit</a>
                        <a href="teacher.php?delete=<?php echo $teacher['teacher_id']; ?>" onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
