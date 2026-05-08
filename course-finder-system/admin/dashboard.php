<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// COUNTS
$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'");
$student_count = $students->fetch_assoc()['total'];

$courses = $conn->query("SELECT COUNT(*) as total FROM courses");
$course_count = $courses->fetch_assoc()['total'];

$categories = $conn->query("SELECT COUNT(*) as total FROM categories");
$category_count = $categories->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Course Finder</title>

    <!-- CHART.JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    

</head>
<body>

<div class="container">

    <h1 style="text-align:center;">👨‍💼 Admin Dashboard</h1>

    <!-- CARDS -->
    <div class="cards">

        <div class="card">
            <h3>Students</h3>
            <p><?php echo $student_count; ?></p>
        </div>

        <div class="card">
            <h3>Courses</h3>
            <p><?php echo $course_count; ?></p>
        </div>

        <div class="card">
            <h3>Categories</h3>
            <p><?php echo $category_count; ?></p>
        </div>

    </div>

    <!-- CHART SECTION -->
    <div class="chart-box">
        <canvas id="myChart"></canvas>
    </div>

    <hr>

    <h3>Management</h3>

    <a href="courses.php">➡ Manage Courses</a>
    <a href="categories.php">➡ Manage Categories</a>
    <a href="users.php">➡ View Users</a>
    <a href="../auth/logout.php">➡ Logout</a>

</div>

<!-- CHART SCRIPT -->
<script>
var ctx = document.getElementById('myChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Students', 'Courses', 'Categories'],
        datasets: [{
            label: 'System Overview',
            data: [
                <?php echo $student_count; ?>,
                <?php echo $course_count; ?>,
                <?php echo $category_count; ?>
            ],
            backgroundColor: [
                'blue',
                'green',
                'orange'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});
</script>

</body>
</html>