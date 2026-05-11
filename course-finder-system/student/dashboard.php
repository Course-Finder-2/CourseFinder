<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$current_date = date("F d, Y");

/* =========================
   GET STUDENT CATEGORY (SAFE)
========================= */
$stmt = $conn->prepare("
    SELECT categories.category_name
    FROM student_preferences
    JOIN categories
        ON student_preferences.category_id = categories.category_id
    WHERE student_preferences.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$interestQuery = $stmt->get_result();

$interest = "Not Set";
if ($interestQuery && $interestQuery->num_rows > 0) {
    $interest = $interestQuery->fetch_assoc()['category_name'];
}

/* =========================
   TOTAL COURSES (SAFE)
========================= */
$totalCoursesStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM courses
");
$totalCoursesStmt->execute();
$total_courses = $totalCoursesStmt->get_result()->fetch_assoc()['total'];

/* =========================
   RECOMMENDED COURSES WITH DETAILS (SAFE)
========================= */
$recStmt = $conn->prepare("
    SELECT DISTINCT
        courses.course_id,
        courses.course_name,
        courses.description,
        courses.career_opportunities,
        courses.duration,
        courses.recommendation_reason,
        categories.category_name
    FROM student_preferences
    JOIN categories
        ON student_preferences.category_id = categories.category_id
    JOIN courses
        ON categories.category_id = courses.category_id
    WHERE student_preferences.user_id = ?
    ORDER BY courses.course_name ASC
");

$recStmt->bind_param("i", $user_id);
$recStmt->execute();
$result = $recStmt->get_result();

$recommended_count = $result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - Course Finder</title>
    <link rel="stylesheet" href="../assets/css/student&admin.css">
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <h1>🎓 Welcome, <?php echo htmlspecialchars($user_name); ?></h1>

    <p style="text-align:center; color:gray;">
        Today is <?php echo $current_date; ?>
    </p>

    <!-- INFO CARDS -->
    <div class="cards">

        <div class="card">
            <h3>🎯 Interest</h3>
            <p><?php echo htmlspecialchars($interest); ?></p>
        </div>

        <div class="card">
            <h3>📚 Recommended</h3>
            <p><?php echo $recommended_count; ?></p>
        </div>

        <div class="card">
            <h3>🗂 Total Courses</h3>
            <p><?php echo $total_courses; ?></p>
        </div>

    </div>

    <!-- MESSAGE -->
    <p class="message">
        Recommendations are based on your selected interest:
        <strong><?php echo htmlspecialchars($interest); ?></strong>
    </p>

    <!-- RECOMMENDED COURSES -->
    <h2 style="text-align:center;">📖 Recommended Courses</h2>

    <?php if ($result && $result->num_rows > 0) { ?>

        <?php while ($row = $result->fetch_assoc()) { ?>

            <div class="card" style="width:85%; margin:30px auto; text-align:left;">

                <h2 style="margin-top:0;">
                    🎓 <?php echo htmlspecialchars($row['course_name']); ?>
                </h2>

                <p>
                    <strong>📂 Category:</strong>
                    <?php echo htmlspecialchars($row['category_name']); ?>
                </p>

                <p>
                    <strong>⏳ Duration:</strong>
                    <?php echo htmlspecialchars($row['duration']); ?>
                </p>

                <p>
                    <strong>📘 Description:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                </p>

                <p>
                    <strong>💼 Career Opportunities:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['career_opportunities'])); ?>
                </p>

                <p>
                    <strong>⭐ Why This Course Is Recommended:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['recommendation_reason'])); ?>
                </p>

            </div>

        <?php } ?>

    <?php } else { ?>

        <p style="text-align:center; color:red;">
            No recommendations yet. Please set your preferences first.
        </p>

        <div class="links" style="text-align:center; margin-top:20px;">
            <a href="preferences.php">🎯 Set Preferences Now</a>
        </div>

    <?php } ?>

    <!-- INFO BOX -->
    <div class="card" style="width:70%; margin:30px auto;">
        <h3>💡 How Recommendations Work</h3>
        <p>
            The system uses your selected category from the Preferences module.
            It then matches available courses using secure prepared SQL statements
            and JOIN operations to generate personalized recommendations.
        </p>
    </div>

    <!-- NAVIGATION -->
    <div class="links">

        <a href="profile.php">👤 Profile</a>
        <a href="preferences.php">🎯 Preferences</a>

        <a href="../auth/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           🚪 Logout
        </a>

    </div>

</div>

</body>
</html>