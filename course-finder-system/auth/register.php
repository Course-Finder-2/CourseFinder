<?php
include("../db/connection.php");

$message = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $role = "student";

    // CHECK IF EMAIL EXISTS
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "Email already exists!";

    } else {

        // 🔐 HASH PASSWORD (IMPORTANT UPGRADE)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // INSERT USER
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $message = "Account created successfully! You can now login.";
        } else {
            $message = "Error creating account.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Course Finder System</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="container">

    <h2 style="text-align:center;">📝 Register</h2>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Register</button>

    </form>

    <?php if ($message != "") { ?>
        <p class="message" style="text-align:center; color:green;">
            <?php echo $message; ?>
        </p>
    <?php } ?>

    <div style="text-align:center; margin-top:15px;">
        <a href="login.php">Back to Login</a> |
        <a href="../index.php">Back to Home</a>
    </div>

</div>

</body>
</html>