<?php
include("../db/connection.php");

$message = "";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Default role is student
    $role = "student";

    $sql = "INSERT INTO users (name, email, password, role)
            VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql)) {
        $message = "Account created successfully! You can now login.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Course Finder System</title>


</head>
<body>

<div class="container">

    <h2>📝 Register</h2>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required><br><br>

        <input type="email" name="email" placeholder="Email" required><br><br>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <button type="submit" name="register">Register</button>

    </form>

    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <a href="login.php">Back to Login</a>
    <a href="../index.php">Back to Home</a>

</div>

</body>
</html>