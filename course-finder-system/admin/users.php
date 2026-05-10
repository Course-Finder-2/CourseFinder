<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

// DELETE USER (except currently logged-in admin)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Prevent admin from deleting his/her own account
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE user_id = $id");
        $message = "User deleted successfully!";
    } else {
        $message = "You cannot delete your own account while logged in.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>

    <!-- Use your external CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <h2 style="text-align:center;">👥 Registered Users</h2>

    <?php if ($message != "") { ?>
        <p class="message" style="text-align:center; color: green; font-weight: bold;">
            <?php echo $message; ?>
        </p>
    <?php } ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM users ORDER BY user_id ASC");

        while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td>
                <?php if ($row['user_id'] != $_SESSION['user_id']) { ?>
                    <a
                        href="?delete=<?php echo $row['user_id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this user?')">
                        🗑 Delete
                    </a>
                <?php } else { ?>
                    <strong>Current Admin</strong>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <div style="text-align:center;">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>

</body>
</html>