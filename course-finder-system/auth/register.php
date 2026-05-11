<?php
include("../db/connection.php");

$message = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $role = "student";

    // CHECK EMAIL
    $check = $conn->prepare(
        "SELECT user_id FROM users WHERE email = ?"
    );

    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "Email already exists!";

    } else {

        // HASH PASSWORD
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // INSERT USER
        $stmt = $conn->prepare("
            INSERT INTO users
            (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $name,
            $email,
            $hashedPassword,
            $role
        );

        if ($stmt->execute()) {

            $message =
            "Account created successfully!";

        } else {

            $message =
            "Error creating account.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Register - Course Finder System</title>

    <!-- CSS -->
    <link rel="stylesheet"
    href="../assets/css/style.css">

    <!-- ICONS -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <form method="POST">

        <!-- LOGO -->
        <div class="logo">
            🎓
        </div>

        <!-- TITLE -->
        <h1>Create Account</h1>

        <p class="subtitle">
            Join Course Finder today
        </p>

        <!-- NAME -->
        <div class="input-box">

            <i class="fa-solid fa-user"></i>

            <input
                type="text"
                name="name"
                placeholder="Full Name"
                required
            >

        </div>

        <!-- EMAIL -->
        <div class="input-box">

            <i class="fa-solid fa-envelope"></i>

            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                required
            >

        </div>

        <!-- PASSWORD -->
        <div class="input-box">

            <i class="fa-solid fa-lock"></i>

            <input
                type="password"
                name="password"
                placeholder="Create Password"
                required
            >

        </div>

        <!-- MESSAGE -->
        <?php if ($message != "") { ?>

            <div class="message-box">
                <?php echo $message; ?>
            </div>

        <?php } ?>

        <!-- BUTTON -->
        <button
            type="submit"
            name="register"
        >

            <i class="fa-solid fa-user-plus"></i>
            Register

        </button>

        <!-- LINKS -->
        <div class="links">

            <a href="login.php">

                <i class="fa-solid fa-right-to-bracket"></i>
                Login

            </a>

            <a href="../index.php">

                <i class="fa-solid fa-house"></i>
                Home

            </a>

        </div>

    </form>

</div>

</body>
</html>