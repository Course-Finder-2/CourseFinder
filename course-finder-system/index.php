<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Finder System</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<div class="container">

    <h1>🎓 Course Finder System</h1>

    <p style="text-align:center; max-width:600px; margin:auto;">
        This system helps students find suitable academic courses based on their interests.
        It uses database-driven recommendations and role-based access for Admin and Students.
    </p>

    <div style="text-align:center; margin-top:30px;">

        <a href="auth/login.php">
            <button>Login</button>
        </a>

        <a href="auth/register.php">
            <button>Register</button>
        </a>

    </div>

    <hr style="margin-top:40px;">

    <h3>System Features</h3>

    <ul style="max-width:500px; margin:auto;">
        <li>✔ User Authentication (Login/Register)</li>
        <li>✔ Admin Dashboard with Chart.js</li>
        <li>✔ Course & Category Management (CRUD)</li>
        <li>✔ Student Preference System</li>
        <li>✔ SQL JOIN-based Course Recommendation</li>
    </ul>

</div>

</body>
</html>