<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Course Finder System</title>

    <!-- CSS -->
    <link rel="stylesheet"
    href="assets/css/style.css">

    <!-- ICONS -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="hero">

    <div class="glass-box">

        <!-- BADGE -->
        <span class="badge">

            <i class="fa-solid fa-graduation-cap"></i>
            Philippine Education

        </span>

        <!-- LOGO -->
        <div class="hero-logo">
            🎓
        </div>

        <!-- TITLE -->
        <h1>
            Course Finder System
        </h1>

        <!-- DESCRIPTION -->
        <p>
            Discover the best course for your interests,
            skills, passion, and future career goals
            in the Philippines.
        </p>

        <!-- FEATURES -->
        <div class="features">

            <div class="feature-box">

                <i class="fa-solid fa-book-open"></i>

                <span>
                    Explore Courses
                </span>

            </div>

            <div class="feature-box">

                <i class="fa-solid fa-lightbulb"></i>

                <span>
                    Discover Your Interests
                </span>

            </div>

            <div class="feature-box">

                <i class="fa-solid fa-chart-line"></i>

                <span>
                    Build Your Future Career
                </span>

            </div>

        </div>

        <!-- BUTTONS -->
        <div class="btn-group">

            <a href="auth/login.php"
            class="btn btn-primary">

                <i class="fa-solid fa-right-to-bracket"></i>
                Login

            </a>

            <a href="auth/register.php"
            class="btn btn-outline">

                <i class="fa-solid fa-user-plus"></i>
                Register

            </a>

        </div>

    </div>

</div>

</body>
</html>