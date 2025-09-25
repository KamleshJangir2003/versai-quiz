<?php
session_start();
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = MD5('$password')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        $_SESSION['success_msg'] = "Login Successful! Welcome, $username."; // success message
        header("Location: ../admin-dashboard.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/login.css"> <!-- tumhara design CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <!-- Only Sign In Panel for Admin -->
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Admin Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class='bx bxl-google'></i></a>
                    <a href="#" class="icon"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="icon"><i class='bx bxl-github'></i></a>
                    <a href="#" class="icon"><i class='bx bxl-linkedin'></i></a>
                </div>
                <span>Use your admin credentials</span>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign In</button>
                <?php if (isset($error)) echo "<p style='color:red;margin-top:10px;'>$error</p>"; ?>
            </form>
        </div>

        <!-- Right Side Toggle Panel -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Admin!</h1>
                    <p>Please log in with your admin account to access the dashboard</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
