<?php
// forgot_password.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Boxicons -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .forgot-container {
      background: #fff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0px 8px 25px rgba(0,0,0,0.2);
      text-align: center;
      width: 100%;
      max-width: 400px;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-30px);}
      to {opacity: 1; transform: translateY(0);}
    }

    .forgot-container h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .forgot-container p {
      font-size: 14px;
      color: #555;
      margin-bottom: 25px;
    }

    .input-box {
      position: relative;
      margin-bottom: 20px;
    }

    .input-box input {
      width: 100%;
      padding: 12px 40px 12px 15px;
      border: 1px solid #ddd;
      border-radius: 10px;
      outline: none;
      font-size: 15px;
      transition: 0.3s;
    }

    .input-box input:focus {
      border-color: #667eea;
      box-shadow: 0px 0px 8px rgba(102, 126, 234, 0.6);
    }

    .input-box i {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
      font-size: 18px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: linear-gradient(135deg, #5a6fd8, #6a3d90);
    }

    .back-link {
      display: block;
      margin-top: 15px;
      font-size: 14px;
      color: #667eea;
      text-decoration: none;
      transition: 0.3s;
    }

    .back-link:hover {
      text-decoration: underline;
      color: #764ba2;
    }
  </style>
</head>
<body>
  <div class="forgot-container">
    <h2>Forgot Password</h2>
    <p>Enter your registered email to receive a password reset link.</p>
    <form method="POST" action="send_reset_link.php">
      <div class="input-box">
        <input type="email" name="email" placeholder="Enter your email" required>
        <i class='bx bx-envelope'></i>
      </div>
      <button type="submit">Send Reset Link</button>
    </form>
    <a href="login.html" class="back-link">← Back to Login</a>
  </div>
</body>
</html>
