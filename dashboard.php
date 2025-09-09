<?php
session_start();

// Agar user login nahi hai to login.php pe redirect
if (!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Session se user ka data lena
$username = $_SESSION['username'];
$student_id = $_SESSION['id'];

include 'db.php';

// Exams Completed
$sql_completed = "SELECT COUNT(*) AS total_completed FROM results WHERE student_id = $student_id";
$result_completed = $conn->query($sql_completed);
$total_completed = 0;
if ($result_completed->num_rows > 0) {
    $row_completed = $result_completed->fetch_assoc();
    $total_completed = $row_completed['total_completed'];
}

// Pass Rate
$sql_passed = "SELECT COUNT(*) AS total_passed FROM results WHERE student_id = $student_id AND score >= 50";
$result_passed = $conn->query($sql_passed);
$total_passed = 0;
if ($result_passed->num_rows > 0) {
    $row_passed = $result_passed->fetch_assoc();
    $total_passed = $row_passed['total_passed'];
}

$pass_rate = 0;
if ($total_completed > 0) {
    $pass_rate = ($total_passed / $total_completed) * 100;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="icon" type="image/x-icon" href="favicon (2).svg">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .results-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    .results-table th, .results-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    .results-table th {
      background-color: #f2f2f2;
    }


    .btn {
  display: inline-block;   /* ensure button visible */
  padding: 10px 20px;
  background: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

  </style>
</head>
<body>
  <header class="main-header">
    <div class="container">
      <h1>Welcome to Dashboard</h1>
      <div class="top-buttons">
        <a href="logout.php" class="btn">Logout</a>
      </div>
    </div>
  </header>

  <main class="container">
    <h2>Hello, <span id="student-name"><?php echo $username; ?></span></h2>
    <p>Your Student ID: <span id="student-id"><?php echo $student_id; ?></span></p>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-title">Exams Completed</div>
        <div class="stat-value"><?php echo $total_completed; ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-title">Pass Rate</div>
        <div class="stat-value"><?php echo round($pass_rate, 2); ?>%</div>
      </div>
    </div>

    <h3>Your Results:</h3>
    <table class="results-table">
      <thead>
        <tr>
          <th>Exam Name</th>
          <th>Score</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include 'db.php';
        $sql_results = "SELECT exam_name, score, date FROM results WHERE student_id = $student_id ORDER BY date DESC";
        $result_results = $conn->query($sql_results);
        if ($result_results->num_rows > 0) {
            while($row = $result_results->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['exam_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['score']) . "%</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No results found.</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
    </table>
    <br>
    <button onclick="window.print()" class="btn">Download Result</button>

  </main>
</body>
