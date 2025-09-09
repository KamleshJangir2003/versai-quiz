<?php
include 'db.php';

$sql = "SELECT COUNT(id) AS total_students FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['total_students' => 0]);
}

$conn->close();
?>
