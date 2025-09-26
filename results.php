<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Pagination variables
$results_per_page = 10;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$start_from = ($page - 1) * $results_per_page;

// Fetch all exam results with pagination
$sql_all_results = "SELECT u.username, r.exam_name, r.score, r.total_questions, r.created_at 
                    FROM results r 
                    JOIN users u ON r.student_id = u.id 
                    ORDER BY r.created_at DESC
                    LIMIT $start_from, $results_per_page";
$all_results = $conn->query($sql_all_results);

// Get total number of results for pagination
$sql_total_results = "SELECT COUNT(*) AS total FROM results";
$total_results_query = $conn->query($sql_total_results);
$total_results_row = $total_results_query->fetch_assoc();
$total_results = $total_results_row['total'];
$total_pages = ceil($total_results / $results_per_page);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProExam - All Exam Results</title>
    <link rel="icon" type="image/x-icon" href="favicon (2).svg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <div id="admin-interface">
        <header class="admin-header">
            <div class="corner-logo">
                <img src="images/versailogo.png" alt="Admin Logo">
            </div>
            <button class="logout-btn" id="admin-logout">
                <i class="fas fa-sign-out-alt"></i> <a href="logout.php">Logout</a>
            </button>
        </header>

        <div class="admin-container">
            <div class="admin-sidebar">
                <ul class="sidebar-menu">
                    <li class="menu-item">
                        <a href="admin-dashboard.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-users"></i>
                        <span>Students</span>
                    </li>
                    <li class="menu-item active">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Exams</span>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </li>
                </ul>
            </div>

            <div class="dashboard-content">
                <h1 class="dashboard-title">
                    <i class="fas fa-clipboard-list"></i> All Exam Results
                </h1>

                <div class="activity-section">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam Name</th>
                                <th>Score</th>
                                <th>Total Questions</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($all_results->num_rows > 0) {
                                while($row = $all_results->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['exam_name'] . "</td>";
                                    echo "<td>" . $row['score'] . "</td>";
                                    echo "<td>" . $row['total_questions'] . "</td>";
                                    echo "<td>" . date("M d, Y", strtotime($row['created_at'])) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No results found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <?php
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo "<a href='results.php?page=" . $i . "'";
                            if ($i == $page) {
                                echo " class='active'";
                            }
                            echo ">" . $i . "</a>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
