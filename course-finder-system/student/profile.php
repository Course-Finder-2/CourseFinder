<?php
include("../db/connection.php");
session_start();

$user_id = $_SESSION['user_id'];
$message = "";

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
    if($stmt->execute()) {
        $_SESSION['name'] = $name;
        $message = "Profile Updated!";
    }
}

$user = $conn->query("SELECT * FROM users WHERE user_id = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/student&admin.css">
</head>
<body>
<div class="container">
    <h1>👤 Profile Settings</h1>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <button type="submit" name="update">Update Profile</button>
    </form>
    <div class="links"><a href="dashboard.php">⬅ Back to Dashboard</a></div>
</div>
</body>
</html>