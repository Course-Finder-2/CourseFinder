<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

/* SYSTEM COUNTS (SAFE + CLEAN) */
$student_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'")
    ->fetch_assoc()['total'];

$course_count = $conn->query("SELECT COUNT(*) as total FROM courses")
    ->fetch_assoc()['total'];

$category_count = $conn->query("SELECT COUNT(*) as total FROM categories")
    ->fetch_assoc()['total'];
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container">

    <!-- HEADER -->
    <h1>👨‍💼 Admin Dashboard</h1>

    <p style="text-align:center; color:gray;">
        Welcome Admin. This dashboard provides system monitoring, management tools, and data insights for the Course Finder System.
    </p>

    <!-- SUMMARY CARDS -->
    <div class="cards">

        <div class="card">
            <h3>👨‍🎓 Students</h3>
            <p><?php echo $student_count; ?></p>
        </div>

        <div class="card">
            <h3>📚 Courses</h3>
            <p><?php echo $course_count; ?></p>
        </div>

        <div class="card">
            <h3>📂 Categories</h3>
            <p><?php echo $category_count; ?></p>
        </div>

    </div>

    <!-- CHART -->
    <div class="chart-box">
        <canvas id="myChart"></canvas>
    </div>

    <!-- SYSTEM INSIGHT -->
    <div class="card" style="width:70%; margin:30px auto;">
        <h3>📊 System Insight</h3>
        <p>
            The system uses relational database design with foreign key relationships between users,
            categories, and courses. Student preferences are stored and used to generate course recommendations
            through SQL JOIN operations.
        </p>
    </div>

    <!-- MANAGEMENT -->
    <h3>⚙️ Management Modules</h3>

    <div class="links">
        <a href="courses.php">📚 Manage Courses</a>
        <a href="categories.php">📂 Manage Categories</a>
        <a href="users.php">👥 View Users</a>
    </div>

</div>

<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('myChart').getContext('2d');

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
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php include("../includes/footer.php"); ?>