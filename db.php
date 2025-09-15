<?php
$servername = "localhost";
$username = "root";   // apne DB ka username
$password = "";       // apne DB ka password
$dbname = "examveraca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
