<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    // Handle case where user is not logged in
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$student_id = $_SESSION['user_id'];
$exam_name = $_POST['exam_name'];
$score = $_POST['score'];
$total_questions = $_POST['total_questions'];

$sql = "INSERT INTO results (student_id, exam_name, score, total_questions) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isii", $student_id, $exam_name, $score, $total_questions);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Failed to save result']);
}

$stmt->close();
$conn->close();
?>
