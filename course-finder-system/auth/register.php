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

    <!-- FIXED CSS PATH -->
    <link rel="stylesheet" href="/course-finder-system/assets/css/style.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        input {
            width: 100%;
        }

        .message {
            margin-top: 10px;
            color: green;
        }

        a {
            display: block;
            margin-top: 10px;
        }
    </style>

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