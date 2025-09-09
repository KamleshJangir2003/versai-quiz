<?php
// send_reset_link.php
include 'db.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Email check in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token
        $token = bin2hex(random_bytes(50));
        $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Save token in database
        $conn->query("UPDATE users SET reset_token='$token', token_expire='$expire' WHERE email='$email'");

        // Reset link
        $reset_link = "http://yourdomain.com/reset_password.php?token=$token";

        // Email (simplified version)
        mail($email, "Password Reset", "Click this link to reset your password: $reset_link");

        echo "Reset link has been sent to your email.";
    } else {
        echo "Email not found!";
    }
}
?>
