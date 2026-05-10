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

/* GET STUDENT CATEGORY */
$interestQuery = $conn->query("
    SELECT categories.category_name
    FROM student_preferences
    JOIN categories
        ON student_preferences.category_id = categories.category_id
    WHERE student_preferences.user_id = $user_id
");

$interest = "Not Set";
if ($interestQuery && $interestQuery->num_rows > 0) {
    $interest = $interestQuery->fetch_assoc()['category_name'];
}

/* TOTAL COURSES */
$totalCourses = $conn->query("SELECT COUNT(*) AS total FROM courses");
$total_courses = $totalCourses->fetch_assoc()['total'];

/* RECOMMENDED COURSES (JOIN CORE) */
$query = "
SELECT DISTINCT
    courses.course_id,
    courses.course_name,
    categories.category_name
FROM student_preferences
JOIN categories
    ON student_preferences.category_id = categories.category_id
JOIN courses
    ON categories.category_id = courses.category_id
WHERE student_preferences.user_id = $user_id
";

$result = $conn->query($query);

/* COUNT RECOMMENDATIONS */
$recommended_count = ($result) ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - Course Finder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <h1>🎓 Welcome, <?php echo $user_name; ?></h1>
    <p style="text-align:center; color:gray;">
        Today is <?php echo $current_date; ?>
    </p>

    <!-- INFO CARDS -->
    <div class="cards">

        <div class="card">
            <h3>🎯 Interest</h3>
            <p><?php echo $interest; ?></p>
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
        <strong><?php echo $interest; ?></strong>
    </p>

    <!-- TABLE -->
    <h3>📖 Recommended Courses</h3>

    <?php if ($result && $result->num_rows > 0) { ?>

        <table>
            <tr>
                <th>Course</th>
                <th>Category</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['course_name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
            </tr>
            <?php } ?>

        </table>

    <?php } else { ?>

        <p style="text-align:center; color:red;">
            No recommendations yet. Please set your preferences first.
        </p>

    <?php } ?>

    <!-- INFO BOX -->
    <div class="card" style="width:70%; margin:30px auto;">
        <h3>💡 How Recommendations Work</h3>
        <p>
            The system uses your selected category from the preferences module.
            It then matches available courses using SQL JOIN operations to generate personalized recommendations.
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