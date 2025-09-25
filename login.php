<?php
session_start();
include 'db.php';

$message = ""; // message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']    = $row['id'];
            $_SESSION['user_name']  = $row['username'];
            $_SESSION['user_email'] = $row['email'];

            $message = "<div class='success-msg'>✅ Login Successful</div>";
            header("refresh:2; url=subjects.php"); // redirect after 2 sec
        } else {
            $message = "<div class='error-msg'>❌ Invalid Password</div>";
        }
    } else {
        $message = "<div class='error-msg'>❌ No account found with this email</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
    .success-msg { padding:10px; background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px; margin-bottom:15px; }
    .error-msg   { padding:10px; background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px; margin-bottom:15px; }
</style>
</head>
<body>

<div class="login-container">
    <!-- Show message here -->
    <?php if(!empty($message)) { echo $message; } ?>

    <!-- <form method="post" action="">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form> -->
</div>

</body>
</html>
