<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

/* =========================
   UPDATE PROFILE (SAFE)
========================= */
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("
        UPDATE users
        SET name = ?, email = ?
        WHERE user_id = ?
    ");

    $stmt->bind_param("ssi", $name, $email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile.";
    }
}

/* =========================
   GET USER DATA (SAFE)
========================= */
$stmt = $conn->prepare("
    SELECT user_id, name, email, role
    FROM users
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <h1>👤 My Profile</h1>

    <!-- MESSAGE -->
    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <!-- PROFILE INFO -->
    <div class="card" style="width: 60%; margin: 20px auto;">
        <h3>📌 Account Information</h3>
        <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
    </div>

    <!-- EDIT FORM -->
    <h3>✏️ Edit Profile</h3>

    <form method="POST">

        <label>Full Name</label>
        <input type="text"
               name="name"
               required
               value="<?php echo $user['name']; ?>">

        <label>Email Address</label>
        <input type="email"
               name="email"
               required
               value="<?php echo $user['email']; ?>">

        <button type="submit" name="update">
            Update Profile
        </button>

    </form>

    <!-- INFO BOX -->
    <div class="card" style="width: 70%; margin: 30px auto;">
        <h3>ℹ️ About This Page</h3>
        <p>
            This page allows students to safely update their profile information.
            All updates are processed using secure prepared statements.
        </p>
    </div>

    <!-- NAVIGATION -->
    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
        <a href="preferences.php">🎯 Set Preferences</a>

        <a href="../auth/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           🚪 Logout
        </a>
    </div>

</div>

</body>
</html>