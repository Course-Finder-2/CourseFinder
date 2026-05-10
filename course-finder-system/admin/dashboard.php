<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

/* SYSTEM COUNTS */
$students = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'");
$student_count = $students->fetch_assoc()['total'];

$courses = $conn->query("SELECT COUNT(*) as total FROM courses");
$course_count = $courses->fetch_assoc()['total'];

$categories = $conn->query("SELECT COUNT(*) as total FROM categories");
$category_count = $categories->fetch_assoc()['total'];
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container">

    <!-- HEADER -->
    <h1>👨‍💼 Admin Dashboard</h1>

    <p style="text-align:center; color:gray;">
        Welcome to the admin control panel. Manage system data and monitor overall statistics.
    </p>

    <!-- SUMMARY CARDS -->
    <div class="cards">

        <div class="card">
            <h3>👨‍🎓 Total Students</h3>
            <p><?php echo $student_count; ?></p>
        </div>

        <div class="card">
            <h3>📚 Total Courses</h3>
            <p><?php echo $course_count; ?></p>
        </div>

        <div class="card">
            <h3>📂 Categories</h3>
            <p><?php echo $category_count; ?></p>
        </div>

    </div>

    <!-- CHART SECTION -->
    <div class="chart-box">
        <canvas id="myChart"></canvas>
    </div>

    <!-- SYSTEM DESCRIPTION -->
    <div class="card" style="width:70%; margin:30px auto;">
        <h3>📊 System Overview</h3>
        <p>
            This dashboard provides an overview of the Course Finder System.
            It displays the number of registered students, available courses,
            and categories. The system uses relational database structure
            with JOIN operations for data management and recommendations.
        </p>
    </div>

    <!-- MANAGEMENT LINKS -->
    <h3>⚙️ Management Modules</h3>

    <div class="links">

        <a href="courses.php">📚 Manage Courses</a>
        <a href="categories.php">📂 Manage Categories</a>
        <a href="users.php">👥 View Users</a>

    </div>

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
            backgroundColor: ['#007bff', '#28a745', '#ffc107']
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

<?php include("../includes/footer.php"); ?>