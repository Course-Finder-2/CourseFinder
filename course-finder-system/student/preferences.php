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

/* ========================
   GET CURRENT PREFERENCE 
========================= */
$current_category_id = "";
$current_category_name = "Not Set";

$stmt = $conn->prepare("
    SELECT categories.category_id, categories.category_name
    FROM student_preferences
    INNER JOIN categories
        ON student_preferences.category_id = categories.category_id
    WHERE student_preferences.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$current = $stmt->get_result();

if ($current && $current->num_rows > 0) {
    $current_data = $current->fetch_assoc();
    $current_category_id = $current_data['category_id'];
    $current_category_name = $current_data['category_name'];
}

/* =========================
   SAVE OR UPDATE PREFERENCE 
========================= */
if (isset($_POST['save'])) {

    $category_id = $_POST['category_id'];

    /* DELETE OLD PREFERENCE */
    $delStmt = $conn->prepare("
        DELETE FROM student_preferences
        WHERE user_id = ?
    ");
    $delStmt->bind_param("i", $user_id);
    $delStmt->execute();

    /* INSERT NEW PREFERENCE */
    $insStmt = $conn->prepare("
        INSERT INTO student_preferences (user_id, category_id)
        VALUES (?, ?)
    ");
    $insStmt->bind_param("ii", $user_id, $category_id);

    if ($insStmt->execute()) {
        $message = "Your preference has been saved successfully!";

        /* Refresh display */
        $refreshStmt = $conn->prepare("
            SELECT category_name
            FROM categories
            WHERE category_id = ?
        ");
        $refreshStmt->bind_param("i", $category_id);
        $refreshStmt->execute();

        $refresh = $refreshStmt->get_result();

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
    <link rel="stylesheet" href="../assets/css/student-admin.css">
</head>
<body>

<div class="container">

    <!-- TITLE -->
    <h1>🎯 Select Your Academic Interest</h1>

    <p style="text-align:center; color: gray;">
        Hello, <?php echo $user_name; ?>! Choose your preferred category.
    </p>

    <!-- CURRENT -->
    <div class="card" style="width: 60%; margin: 20px auto;">
        <h3>📌 Current Preference</h3>
        <p><strong><?php echo $current_category_name; ?></strong></p>
    </div>

    <!-- MESSAGE -->
    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <!-- INFO -->
    <div class="card" style="width: 70%; margin: 20px auto;">
        <h3>ℹ️ How It Works</h3>
        <p>
            Your selected category will be used to generate personalized course recommendations.
        </p>
    </div>

    <!-- FORM -->
    <form method="POST">

        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php
            $catsStmt = $conn->prepare("
                SELECT category_id, category_name
                FROM categories
                ORDER BY category_name ASC
            ");
            $catsStmt->execute();
            $cats = $catsStmt->get_result();

            while ($row = $cats->fetch_assoc()) {
                $selected = ($row['category_id'] == $current_category_id) ? "selected" : "";
            ?>
                <option value="<?php echo $row['category_id']; ?>" <?php echo $selected; ?>>
                    <?php echo $row['category_name']; ?>
                </option>
            <?php } ?>

        </select>

        <button type="submit" name="save">
            Save Preference
        </button>

    </form>

    <!-- GUIDE -->
    <div class="card" style="width: 70%; margin: 30px auto;">
        <h3>💡 Recommendation Guide</h3>
        <p>
            After saving, go to your dashboard to see updated course recommendations.
        </p>
    </div>

    <!-- NAVIGATION -->
    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
        <a href="profile.php">👤 Profile</a>

        <a href="../auth/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           🚪 Logout
        </a>
    </div>

</div>

</body>
</html>