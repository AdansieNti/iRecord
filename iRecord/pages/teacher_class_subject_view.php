<?php
// Include database connection
include('../incl/ShaConnect.php');

// Initialize variables
$class = '';
$results = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class = $_POST['class'];

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT name, subject FROM teacher_class_subject WHERE class = ?");
    $stmt->bind_param("s", $class);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; /* Align items at the top */
            height: 100vh;
            margin: 0;
            padding-top: 20px; /* Space from the top */
        }
        .search-container {
            border: 2px solid green;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 600px; /* Fixed width for the search container */
            margin-bottom: 20px; /* Space below the search container */
        }
        .results {
            border: 2px solid green;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px; /* Set width for the results */
            display: none; /* Initially hidden */
            margin-top: 20px;
        }
        .results.visible {
            display: inline-block; /* Show results when they exist */
        }
        .search-container input[type="submit"] {
            margin-top: 10px; /* Space above the button */
        }
        .flex-row {
            display: flex;
            justify-content: center; /* Center items horizontally */
            align-items: center; /* Align items vertically */
        }
        .flex-row select {
            margin-right: 10px; /* Space between select and button */
        }
    </style>
</head>
<body>
    <div class="search-container">
        <h2>Search Teachers by Class</h2>
        <form method="POST" action="">
            <div class="flex-row">
                <label for="class">Select Class:</label>
                <select name="class" id="class" required>
                    <option value="">--Select Class--</option>
                    <?php
                    // Fetch distinct classes for the dropdown
                    $classQuery = "SELECT DISTINCT class FROM teacher_class_subject";
                    $classResult = $conn->query($classQuery);
                    while ($row = $classResult->fetch_assoc()) {
                        echo "<option value=\"{$row['class']}\">{$row['class']}</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Search">
            </div>
        </form>
    </div>

    <div class="results <?php echo !empty($results) ? 'visible' : ''; ?>">
        <h3>Results:</h3>
        <?php foreach ($results as $teacher): ?>
            <p><strong>Teacher:</strong> <?php echo htmlspecialchars($teacher['name']); ?><br>
               <strong>Subject:</strong> <?php echo htmlspecialchars($teacher['subject']); ?></p>
        <?php endforeach; ?>
    </div>
</body>
</html>
