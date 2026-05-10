<?php
include("../db/connection.php");
session_start();

$error = "";

/* LOGIN PROCESS */
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // SAFE QUERY (only by email)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        /*
        SECURITY UPGRADE:
        - password_verify() is used for hashed passwords
        - fallback still works if your DB is plain text (temporary)
        */

        $isPasswordCorrect = false;

        if (password_get_info($user['password'])['algo']) {
            // hashed password
            $isPasswordCorrect = password_verify($password, $user['password']);
        } else {
            // fallback for old plain-text database
            $isPasswordCorrect = ($password === $user['password']);
        }

        if ($isPasswordCorrect) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // ROLE-BASED REDIRECTION
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

    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="container">

    <h2 style="text-align:center;">🔐 Login</h2>

    <form method="POST">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>

    </form>

    <?php if ($error != "") { ?>
        <p class="message" style="color:red; text-align:center;">
            <?php echo $error; ?>
        </p>
    <?php } ?>

    <div style="text-align:center; margin-top:15px;">
        <a href="register.php">Create Account</a> |
        <a href="../index.php">Back to Home</a>
    </div>

</div>

</body>
</html>