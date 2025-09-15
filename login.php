<?php
session_start();
include 'db.php';

$message = ""; // message store karne ke liye

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // password verify
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']    = $row['id'];
            $_SESSION['user_name']  = $row['username'];
            $_SESSION['user_email'] = $row['email'];

            // ✅ success message
            $message = "<p style='color:green;'>✅ Login Successful! Redirecting...</p>";
            
            // 2 second baad redirect
            header("refresh:2; url=subjects.php");
        } else {
            $message = "<p style='color:red;'>❌ Invalid Password</p>";
        }
    } else {
        $message = "<p style='color:red;'>❌ No account found with this email</p>";
    }
}
?>
