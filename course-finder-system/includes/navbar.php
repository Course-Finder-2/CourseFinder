<?php
$role = $_SESSION['role'] ?? null;
?>

<div class="navbar">

    <!-- PUBLIC / GUEST USERS -->
    <?php if (!$role) { ?>

        <a href="../index.php">Home</a>
        <a href="../auth/login.php">Login</a>
        <a href="../auth/register.php">Register</a>

    <?php } ?>

    <!-- ADMIN NAVIGATION -->
    <?php if ($role == 'admin') { ?>

        <a href="../admin/dashboard.php">Dashboard</a>
        <a href="../admin/courses.php">Courses</a>
        <a href="../admin/categories.php">Categories</a>
        <a href="../admin/users.php">Users</a>

    <?php } ?>

    <!-- STUDENT NAVIGATION -->
    <?php if ($role == 'student') { ?>

        <a href="../student/dashboard.php">Dashboard</a>
        <a href="../student/profile.php">Profile</a>
        <a href="../student/preferences.php">Preferences</a>

    <?php } ?>

    <!-- LOGOUT (ONLY FOR LOGGED IN USERS) -->
    <?php if ($role) { ?>
        <a href="../auth/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           Logout
        </a>
    <?php } ?>

</div>