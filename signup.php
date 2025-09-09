<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['name']);  // form se 'name' input
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "<script>alert('❌ Password and Confirm Password do not match!'); window.location='signup.html';</script>";
        exit();
    }

    // Email already exists check
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "<script>alert('❌ Email already registered, please login!'); window.location='login.html';</script>";
        exit();
    }

    // password hash
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    // ✅ use 'username' instead of 'name'
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_pass')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Registration Successful! Please Login'); window.location='login.html';</script>";
    } else {
        echo "<script>alert('❌ Error: " . $conn->error . "'); window.location='signup.html';</script>";
    }
}
?>
