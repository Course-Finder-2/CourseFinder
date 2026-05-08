<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// JOIN QUERY (CORE OF SYSTEM)
$query = "
SELECT users.name, courses.course_name, categories.category_name
FROM users
JOIN student_preferences ON users.user_id = student_preferences.user_id
JOIN categories ON student_preferences.category_id = categories.category_id
JOIN courses ON categories.category_id = courses.category_id
WHERE users.user_id = $user_id
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - Course Finder</title>


</head>
<body>

<div class="container">

    <h1>🎓 Student Dashboard</h1>

    <h3>Recommended Courses Based on Your Interest</h3>

    <?php if ($result->num_rows > 0) { ?>

        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Course</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['course_name']; ?></td>
            </tr>
            <?php } ?>

        </table>

    <?php } else { ?>
        <p class="empty">No recommendations yet. Please set your preferences first.</p>
    <?php } ?>

    <div class="nav">
        <a href="profile.php">Profile</a>
        <a href="preferences.php">Set Preferences</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

</div>

</body>
</html>