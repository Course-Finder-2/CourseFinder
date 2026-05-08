<div style="background:#007bff; padding:10px;">
    <a href="../index.php" style="color:white; margin-right:10px;">Home</a>

    <?php if (isset($_SESSION['role'])) { ?>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="../admin/dashboard.php" style="color:white; margin-right:10px;">Admin Dashboard</a>
        <?php } ?>

        <?php if ($_SESSION['role'] == 'student') { ?>
            <a href="../student/dashboard.php" style="color:white; margin-right:10px;">Student Dashboard</a>
        <?php } ?>

        <a href="../auth/logout.php" style="color:white;">Logout</a>

    <?php } else { ?>
        <a href="../auth/login.php" style="color:white;">Login</a>
        <a href="../auth/register.php" style="color:white; margin-left:10px;">Register</a>
    <?php } ?>
</div>