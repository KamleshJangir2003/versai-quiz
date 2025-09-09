<?php
// reset_password.php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND token_expire > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $conn->query("UPDATE users SET password='$password', reset_token=NULL, token_expire=NULL WHERE reset_token='$token'");
            echo "Password updated successfully! <a href='login.html'>Login</a>";
        }
    } else {
        echo "Invalid or expired token!";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
</head>
<body>
  <h2>Reset Your Password</h2>
  <form method="POST">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
  </form>
</body>
</html>
