<?php
include("../db/connection.php");
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM users WHERE user_id=$user_id");
$user = $result->fetch_assoc();
?>

<h2>My Profile</h2>

<p>Name: <?php echo $user['name']; ?></p>
<p>Email: <?php echo $user['email']; ?></p>

<br>

<a href="dashboard.php">Back to Dashboard</a>