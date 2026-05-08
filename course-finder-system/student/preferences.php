<?php
include("../db/connection.php");
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// SAVE PREFERENCE
if (isset($_POST['save'])) {
    $category_id = $_POST['category_id'];

    // remove old preference first (simple logic)
    $conn->query("DELETE FROM student_preferences WHERE user_id=$user_id");

    // insert new preference
    $conn->query("INSERT INTO student_preferences (user_id, category_id)
                  VALUES ($user_id, $category_id)");
}
?>

<h2>Select Your Interest</h2>

<form method="POST">
    <select name="category_id" required>
        <option value="">Select Category</option>

        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($row = $cats->fetch_assoc()) {
        ?>
            <option value="<?php echo $row['category_id']; ?>">
                <?php echo $row['category_name']; ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit" name="save">Save Preference</button>
</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>