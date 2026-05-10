<div class="navbar">

    <a href="/course-finder-system/index.php">Home</a>

    <?php if (isset($_SESSION['role'])) { ?>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="/course-finder-system/admin/dashboard.php">Admin Dashboard</a>
        <?php } ?>

        <?php if ($_SESSION['role'] == 'student') { ?>
            <a href="/course-finder-system/student/dashboard.php">Student Dashboard</a>
        <?php } ?>

        <a href="/course-finder-system/auth/logout.php">Logout</a>

    <?php } else { ?>
        <a href="/course-finder-system/auth/login.php">Login</a>
        <a href="/course-finder-system/auth/register.php">Register</a>
    <?php } ?>

</div>