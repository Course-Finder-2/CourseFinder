<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Finder System</title>

    <!-- FIXED CSS PATH (works even if project is inside folder) -->
    <link rel="stylesheet" href="/course-finder-system/assets/css/style.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }

        button {
            width: 120px;
            margin: 10px;
        }
    </style>

</head>
<body>

<div class="container">

    <h1>🎓 Course Finder System</h1>

    <p>
        This system helps students find suitable academic courses based on their interests.
    </p>

    <br>

    <a href="auth/login.php">
        <button>Login</button>
    </a>

    <a href="auth/register.php">
        <button>Register</button>
    </a>

</div>

</body>
</html>