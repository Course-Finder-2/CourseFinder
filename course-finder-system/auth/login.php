<?php
include("../db/connection.php");
session_start();

$error = "";

/* LOGIN PROCESS */
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // SAFE QUERY
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        $isPasswordCorrect = false;

        // HASHED PASSWORD CHECK
        if (password_get_info($user['password'])['algo']) {

            $isPasswordCorrect = password_verify(
                $password,
                $user['password']
            );

        } else {

            // fallback old password
            $isPasswordCorrect =
                ($password === $user['password']);
        }

        if ($isPasswordCorrect) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // REDIRECT
            if ($user['role'] == 'admin') {

                header("Location: ../admin/dashboard.php");

            } else {

                header("Location: ../student/dashboard.php");
            }

            exit();

        } else {

            $error = "Invalid email or password!";
        }

    } else {

        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Login - Course Finder System</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- ICONS -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="container">

    <form method="POST">

        <div class="logo">
            🎓
        </div>

        <h1>Welcome Back</h1>

        <p class="subtitle">
            Login to your Course Finder account
        </p>

        <!-- EMAIL -->
        <div class="input-box">

            <i class="fa-solid fa-envelope"></i>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

        </div>

        <!-- PASSWORD -->
        <div class="input-box">

            <i class="fa-solid fa-lock"></i>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

        </div>

        <!-- ERROR -->
        <?php if ($error != "") { ?>

            <div class="error-message">
                <?php echo $error; ?>
            </div>

        <?php } ?>

        <!-- BUTTON -->
        <button type="submit" name="login">

            <i class="fa-solid fa-right-to-bracket"></i>
            Login

        </button>

        <!-- LINKS -->
        <div class="links">

            <a href="register.php">
                Create Account
            </a>

            <span> </span>

            <a href="../index.php">
                Back to Home
            </a>

        </div>

    </form>

</div>

</body>
</html>