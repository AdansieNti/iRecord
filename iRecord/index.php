<?php
include 'incl/ShaConnect.php';

session_start();

// Check if form is submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check the user credentials
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password and role
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on the role
        switch ($user['role']) {
            case 'Admin':
                header("Location: incl/admin.php");
                break;
            case 'Staff':
                header("Location: incl/staff.php");
                break;
            case 'HOD':
                header("Location: incl/hod.php");
                break;
            case 'Student':
                header("Location: incl/student.php");
                break;
            default:
                $error = "Invalid role!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>i-Record</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            background-color: black;
            font-family: Arial, sans-serif;
        }

        h1.header-title {
            font-size: 86px; /* Increased font size */
            text-align: center;
            margin-top: 20px;
        }

        h1.header-title .red {
            color: red;
        }

        h1.header-title .blue {
            color: blue;
        }

        .login-container {
            width: 20%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid blue;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .login-container h2 {
            text-align: center;
            color: blue;
        }

        .login-container label {
            display: block;
            margin-bottom: 10px;
            color: #fff;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: blue;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container input[type="submit"]:hover {
            background-color: darkblue;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            text-align: center;
            color: white;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1 class="header-title">
        <span class="red">i</span><span class="blue">-Record</span>
    </h1>
    
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" name="login" value="Login">
        </form>
    </div>

    <footer>
        <?php include 'incl/footer.php'; ?>
    </footer>
</body>
</html>
