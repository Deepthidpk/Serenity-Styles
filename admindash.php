<!-- login.php -->
<?php
session_start();

// Database connection
$db_connection = mysqli_connect("localhost", "username", "password", "database_name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($db_connection, $_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($db_connection, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_username'] = $username;
            header("Location: admindash.php");
            exit();
        }
    }
    $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            background-color: #1a1a1a;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #2d2d2d;
            padding: 40px;
            border-radius: 10px;
            width: 300px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #3d3d3d;
            border-radius: 5px;
            background-color: #1a1a1a;
            color: #fff;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: #f44336;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

<!-- logout.php -->
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>