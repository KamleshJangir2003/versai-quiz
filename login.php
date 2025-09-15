<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // password verify
        if (password_verify($password, $row['password'])) {
            // session set
            $_SESSION['user_id']    = $row['id'];
            $_SESSION['user_name']  = $row['username'];
            $_SESSION['user_email'] = $row['email'];

            echo "<script>alert('✅ Login Successful'); window.location='subjects.php';</script>";
        } else {
            echo "<script>alert('❌ Invalid Password'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('❌ No account found with this email'); window.location='login.html';</script>";
    }
}
?>
