<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

/* ===============
   DELETE USER 
================== */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    // prevent self delete
    if ($id != $_SESSION['user_id']) {

        // STEP 1: delete related child records first
        $stmt1 = $conn->prepare("
            DELETE FROM student_preferences WHERE user_id = ?
        ");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();

        // STEP 2: delete user
        $stmt2 = $conn->prepare("
            DELETE FROM users WHERE user_id = ?
        ");
        $stmt2->bind_param("i", $id);

        if ($stmt2->execute()) {
            $message = "User deleted successfully!";
        } else {
            $message = "Database error: " . $conn->error;
        }

    } else {
        $message = "You cannot delete your own account while logged in.";
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container">
    
    <h1>👥 Registered Users</h1>

    <p style="text-align:center; color:gray;">
        Manage all registered users in the system.
    </p>

    <!-- MESSAGE -->
    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <!-- TABLE -->
    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("
            SELECT * FROM users ORDER BY user_id ASC
        ");

        while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td>

                <?php if ($row['user_id'] != $_SESSION['user_id']) { ?>
                    <a href="?delete=<?php echo $row['user_id']; ?>"
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

    <!-- NAVIGATION -->
    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
        <a href="courses.php">📚 Courses</a>
        <a href="categories.php">📂 Categories</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>