<?php
// Database connection (assumed to be in dbconnection.php)
include '../incl/ShaConnect.php';

// Initialize variables
$users = [];
$searchTerm = '';

// Handle search functionality
if (isset($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM user WHERE username LIKE '%$searchTerm%'";
} else {
    $query = "SELECT * FROM user";
}

$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert or update logic based on whether a user ID is provided
    if (!empty($_POST['userId'])) {
        $id = mysqli_real_escape_string($conn, $_POST['userId']);
        $update_query = "UPDATE user SET username='$username', password='$hashedPassword', role='$role' WHERE user_id='$id'";
        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "INSERT INTO user (username, password, role) VALUES ('$username', '$hashedPassword', '$role')";
        mysqli_query($conn, $insert_query);
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM user WHERE user_id='$id'";
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
    <title>User Management</title>
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
        .form-fields {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-fields label {
            flex: 1;
            text-align: right;
            margin-right: 10px;
        }
        .form-fields input, .form-fields select {
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
            margin: 0 auto;
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
        .table-container th, .table-container td {
            border: 1px solid blue;
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
        .search-container {
            text-align: left;
            margin-bottom: 20px;
        }
        .search-container input {
            padding: 8px;
            width: 200px;
            border: 1px solid blue;
            border-radius: 5px;
        }
        .search-container button {
            padding: 8px 15px;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .search-container button:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add/Edit User</h2>
    <form method="POST">
        <input type="hidden" name="userId" id="userId" value="">
        <div class="form-fields">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-fields">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-fields">
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="">Select Role</option>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
                </select>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>

<div class="table-container">
    <h2>Existing Users</h2>

    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by username..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <button onclick="editUser(<?php echo $user['user_id']; ?>, '<?php echo addslashes($user['username']); ?>', '<?php echo addslashes($user['role']); ?>')">Edit</button>
                        <a href="?delete=<?php echo $user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No users found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script>
    function editUser(id, username, role) {
        document.getElementById('userId').value = id;
        document.getElementById('username').value = username;
        document.getElementById('role').value = role;
    }
</script>

</body>
</html>
