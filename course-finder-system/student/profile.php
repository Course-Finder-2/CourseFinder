<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// UPDATE PROFILE
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $conn->query("UPDATE users
                  SET name = '$name',
                      email = '$email'
                  WHERE user_id = $user_id");

    // Update session name immediately
    $_SESSION['name'] = $name;

    $message = "Profile updated successfully!";
}

// GET USER DATA
$result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>

    <!-- Use your external CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <h2 style="text-align:center;">👤 My Profile</h2>

    <?php if ($message != "") { ?>
        <p class="message" style="text-align:center; color: green; font-weight: bold;">
            <?php echo $message; ?>
        </p>
    <?php } ?>

    <!-- PROFILE FORM -->
    <form method="POST">

        <label>Full Name</label>
        <input
            type="text"
            name="name"
            required
            value="<?php echo $user['name']; ?>">

        <br><br>

        <label>Email Address</label>
        <input
            type="email"
            name="email"
            required
            value="<?php echo $user['email']; ?>">

        <br><br>

        <button type="submit" name="update">
            Update Profile
        </button>

    </form>

    <br>

    <div style="text-align:center;">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>

</body>
</html>