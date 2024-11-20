<?php
include '../incl/ShaConnect.php';

// Fetching subjects from the database
$sql = "SELECT * FROM subject";
$result = $conn->query($sql);
$subjects = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Handling form submission for adding/editing subjects
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_name = $_POST['subject_name'];
    $subject_id = $_POST['subject_id'];

    if ($subject_id) {
        // Update existing subject
        $update_sql = "UPDATE subject SET subject_name = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('si', $subject_name, $subject_id);
        $stmt->execute();
    } else {
        // Add new subject
        $insert_sql = "INSERT INTO subject (subject_name) VALUES (?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param('s', $subject_name);
        $stmt->execute();
    }

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management</title>
    <link rel="stylesheet" href="../incl/styles.css"> <!-- Assume styles.css exists -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container h2 {
            margin-top: 0;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container input[type="text"], .form-container input[type="submit"] {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }

        .form-container input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid blue;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .action-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Add/Edit Subject</h2>
            <form action="" method="POST">
                <input type="hidden" name="subject_id" id="subject_id">
                <input type="text" name="subject_name" id="subject_name" placeholder="Enter Subject Name" required>
                <input type="submit" value="Save Subject">
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subjects)) : ?>
                    <?php foreach ($subjects as $subject) : ?>
                        <tr>
                            <td><?php echo $subject['id']; ?></td>
                            <td><?php echo $subject['subject_name']; ?></td>
                            <td>
                                <a href="javascript:void(0)" class="action-btn" onclick="editSubject('<?php echo $subject['id']; ?>', '<?php echo htmlspecialchars($subject['subject_name']); ?>')">Edit</a>
                                <a href="delete_subject.php?id=<?php echo $subject['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No subjects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editSubject(id, name) {
            document.getElementById('subject_id').value = id;
            document.getElementById('subject_name').value = name;
        }
    </script>
</body>
</html>
