<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// GET CURRENT PREFERENCE
$current_category_id = "";

$current = $conn->query("
    SELECT category_id
    FROM student_preferences
    WHERE user_id = $user_id
");

if ($current->num_rows > 0) {
    $current_data = $current->fetch_assoc();
    $current_category_id = $current_data['category_id'];
}

// SAVE OR UPDATE PREFERENCE
if (isset($_POST['save'])) {
    $category_id = $_POST['category_id'];

    // Remove existing preference (simple approach)
    $conn->query("DELETE FROM student_preferences
                  WHERE user_id = $user_id");

    // Insert new preference
    $conn->query("INSERT INTO student_preferences (user_id, category_id)
                  VALUES ($user_id, $category_id)");

    $current_category_id = $category_id;
    $message = "Your preference has been saved successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Preferences</title>

    <!-- Use your external CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <h2 style="text-align:center;">🎯 Select Your Academic Interest</h2>

    <p style="text-align:center;">
        Choose the category that best matches your interests.
        The system will recommend academic courses based on your selection.
    </p>

    <?php if ($message != "") { ?>
        <p class="message" style="text-align:center; color: green; font-weight: bold;">
            <?php echo $message; ?>
        </p>
    <?php } ?>

    <!-- PREFERENCE FORM -->
    <form method="POST">

        <select name="category_id" required style="width:100%; padding:10px; margin-bottom:15px;">
            <option value="">Select Category</option>

            <?php
            $cats = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");

            while ($row = $cats->fetch_assoc()) {
                $selected = ($row['category_id'] == $current_category_id)
                    ? "selected"
                    : "";
            ?>
                <option
                    value="<?php echo $row['category_id']; ?>"
                    <?php echo $selected; ?>>
                    <?php echo $row['category_name']; ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="save">
            Save Preference
        </button>

    </form>

    <br>

    <div style="text-align:center;">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>

</body>
</html>