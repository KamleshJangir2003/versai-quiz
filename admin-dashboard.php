<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
// Recent Registrations
$sql_new_users = "SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$new_users = $conn->query($sql_new_users);

// Recent Exam Results
$sql_results = "SELECT u.username, r.exam_name, r.score, r.total_questions, r.created_at 
                FROM results r 
                JOIN users u ON r.student_id = u.id 
                ORDER BY r.created_at DESC LIMIT 5";
$recent_results = $conn->query($sql_results);

// Total Students
$sql = "SELECT COUNT(id) AS total_students FROM users";
$result = $conn->query($sql);
$total_students = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_students = $row['total_students'];
}

// Total Exams (jitne exams students ne diye)
$sql_exams = "SELECT COUNT(*) AS total_exams FROM results";
$result_exams = $conn->query($sql_exams);
$total_exams = 0;
if ($result_exams->num_rows > 0) {
    $row_exams = $result_exams->fetch_assoc();
    $total_exams = $row_exams['total_exams'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProExam - Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="favicon (2).svg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
    a{
        color: #f8f6f6ff;
        text-decoration: none;
    }
</style>
</head>

<body>
    <!-- Admin Dashboard -->
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
            <!-- Sidebar -->
            <div class="admin-sidebar">
                <ul class="sidebar-menu">
                    <li class="menu-item active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-users"></i>
                        <span>Students (<?php echo $total_students; ?>)</span>
                    </li>
                    <li class="menu-item">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Exams (<?php echo $total_exams; ?>)</span>
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

            <!-- Main Content -->
            <div class="dashboard-content">
                <h1 class="dashboard-title">
                    <i class="fas fa-tachometer-alt"></i> Dashboard Overview
                </h1>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-title">Total Students</div>
                        <div class="stat-value"><?php echo $total_students; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Exams Completed</div>
                        <div class="stat-value"><?php echo $total_exams; ?></div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i> 8% from last month
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Pass Rate</div>
                        <div class="stat-value">86.5%</div>
                        <div class="stat-change danger">
                            <i class="fas fa-arrow-down"></i> 2% from last month
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="activity-section">
    <div class="section-header">
        <h2 class="section-title">Recent Activity</h2>
        <span class="view-all"><a href="results.php">View All</a></span>
    </div>
    <ul class="activity-list">
        <?php while($row = $new_users->fetch_assoc()): ?>
            <li class="activity-item">
                <div class="activity-info">
                    <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                    <div class="activity-text">
                        New student registered: <strong><?= $row['username']; ?></strong>
                    </div>
                </div>
                <div class="activity-time"><?= date("M d, H:i", strtotime($row['created_at'])); ?></div>
            </li>
        <?php endwhile; ?>

        <?php while($row = $recent_results->fetch_assoc()): ?>
            <li class="activity-item">
                <div class="activity-info">
                    <div class="activity-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div class="activity-text">
                        <strong><?= $row['username']; ?></strong> scored 
                        <strong><?= $row['score']; ?>/<?= $row['total_questions']; ?></strong> 
                        on <strong><?= $row['exam_name']; ?></strong>
                    </div>
                </div>
                <div class="activity-time"><?= date("M d, H:i", strtotime($row['created_at'])); ?></div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

                <!-- Progress Section -->
                <div class="progress-section">
                    <div class="section-header">
                        <h2 class="section-title">Exam Completion Progress</h2>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris.</p>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 68%"></div>
                    </div>
                    <div class="progress-stats">
                        <div class="progress-stat">
                            <div class="progress-value">68%</div>
                            <div class="progress-label">Completion Rate</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-value">70</div>
                            <div class="progress-label">Today</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-value">60</div>
                            <div class="progress-label">Yesterday</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple active menu item toggle
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function () {
                menuItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
