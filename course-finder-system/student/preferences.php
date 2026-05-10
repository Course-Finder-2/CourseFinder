<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$message = "";

// GET CURRENT PREFERENCE
$current_category_id = "";
$current_category_name = "Not Set";

$current = $conn->query("
    SELECT categories.category_id, categories.category_name
    FROM student_preferences
    JOIN categories
        ON student_preferences.category_id = categories.category_id
    WHERE student_preferences.user_id = $user_id
");

if ($current && $current->num_rows > 0) {
    $current_data = $current->fetch_assoc();
    $current_category_id = $current_data['category_id'];
    $current_category_name = $current_data['category_name'];
}

// SAVE OR UPDATE PREFERENCE
if (isset($_POST['save'])) {
    $category_id = $_POST['category_id'];

    // Remove existing preference
    $conn->query("
        DELETE FROM student_preferences
        WHERE user_id = $user_id
    ");

    // Insert new preference
    if ($conn->query("
        INSERT INTO student_preferences (user_id, category_id)
        VALUES ($user_id, $category_id)
    ")) {
        $message = "Your preference has been saved successfully!";

        // Refresh current preference data
        $refresh = $conn->query("
            SELECT category_name
            FROM categories
            WHERE category_id = $category_id
        ");

        if ($refresh && $refresh->num_rows > 0) {
            $refresh_data = $refresh->fetch_assoc();
            $current_category_id = $category_id;
            $current_category_name = $refresh_data['category_name'];
        }
    } else {
        $message = "Database Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Preferences - Course Finder</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- PAGE TITLE -->
    <h1>🎯 Select Your Academic Interest</h1>

    <!-- WELCOME MESSAGE -->
    <p style="text-align:center; color: gray;">
        Hello, <?php echo $user_name; ?>!
        Choose the academic category that best matches your interests.
    </p>

    <!-- CURRENT PREFERENCE CARD -->
    <div class="card" style="width: 60%; margin: 20px auto;">
        <h3>📌 Current Preference</h3>
        <p>
            <strong><?php echo $current_category_name; ?></strong>
        </p>
    </div>

    <!-- SUCCESS MESSAGE -->
    <?php if ($message != "") { ?>
        <p class="message">
            <?php echo $message; ?>
        </p>
    <?php } ?>

    <!-- INSTRUCTIONS -->
    <div class="card" style="width: 70%; margin: 20px auto;">
        <h3>ℹ️ How It Works</h3>
        <p>
            The system uses your selected academic interest to recommend
            courses that match your preferences.
        </p>
    </div>

    <!-- PREFERENCE FORM -->
    <form method="POST">

        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php
            $cats = $conn->query("
                SELECT *
                FROM categories
                ORDER BY category_name ASC
            ");

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

    <!-- GUIDANCE NOTE -->
    <div class="card" style="width: 70%; margin: 30px auto;">
        <h3>💡 Recommendation Guide</h3>
        <p>
            After saving your preference, go to the Student Dashboard to
            view course recommendations generated specifically for you.
        </p>
    </div>

    <!-- NAVIGATION -->
    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
        <a href="profile.php">👤 View Profile</a>
        <a href="../auth/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           🚪 Logout
        </a>
    </div>

</div>

</body>
</html>