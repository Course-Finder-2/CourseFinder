<?php
include("../db/connection.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

/* CREATE */
if (isset($_POST['add'])) {

    $name = trim($_POST['course_name']);
    $cat = (int)$_POST['category_id'];
    $description = trim($_POST['description']);
    $career_opportunities = trim($_POST['career_opportunities']);
    $duration = trim($_POST['duration']);
    $recommendation_reason = trim($_POST['recommendation_reason']);

    $stmt = $conn->prepare("
        INSERT INTO courses (
            course_name,
            category_id,
            description,
            career_opportunities,
            duration,
            recommendation_reason
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sissss",
        $name,
        $cat,
        $description,
        $career_opportunities,
        $duration,
        $recommendation_reason
    );

    if ($stmt->execute()) {
        $message = "Course added successfully!";
    } else {
        $message = "Database Error: " . $conn->error;
    }
}

/* DELETE */
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Course deleted successfully!";
    } else {
        $message = "Database Error: " . $conn->error;
    }
}

/* UPDATE */
if (isset($_POST['update'])) {

    $id = (int)$_POST['course_id'];
    $name = trim($_POST['course_name']);
    $cat = (int)$_POST['category_id'];
    $description = trim($_POST['description']);
    $career_opportunities = trim($_POST['career_opportunities']);
    $duration = trim($_POST['duration']);
    $recommendation_reason = trim($_POST['recommendation_reason']);

    $stmt = $conn->prepare("
        UPDATE courses
        SET
            course_name = ?,
            category_id = ?,
            description = ?,
            career_opportunities = ?,
            duration = ?,
            recommendation_reason = ?
        WHERE course_id = ?
    ");

    $stmt->bind_param(
        "sissssi",
        $name,
        $cat,
        $description,
        $career_opportunities,
        $duration,
        $recommendation_reason,
        $id
    );

    if ($stmt->execute()) {
        $message = "Course updated successfully!";
    } else {
        $message = "Database Error: " . $conn->error;
    }
}

/* EDIT MODE */
$editMode = false;
$editData = [];

if (isset($_GET['edit'])) {

    $editMode = true;
    $id = (int)$_GET['edit'];

    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $editData = $result->fetch_assoc();
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container">
    <h1>📚 Manage Courses</h1>

    <p style="text-align:center; color:gray;">
        Add detailed course information to provide better recommendations to students.
    </p>

    <!-- MESSAGE -->
    <?php if ($message != "") { ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <!-- FORM -->
    <form method="POST">

        <?php if ($editMode) { ?>
            <input
                type="hidden"
                name="course_id"
                value="<?php echo $editData['course_id']; ?>">
        <?php } ?>

        <!-- COURSE NAME -->
        <input
            type="text"
            name="course_name"
            placeholder="Course Name"
            required
            value="<?php echo $editMode ? htmlspecialchars($editData['course_name']) : ''; ?>">

        <!-- CATEGORY -->
        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php
            $categories = $conn->query("
                SELECT *
                FROM categories
                ORDER BY category_name ASC
            ");

            while ($cat = $categories->fetch_assoc()) {

                $selected = (
                    $editMode &&
                    $editData['category_id'] == $cat['category_id']
                ) ? "selected" : "";
            ?>
                <option
                    value="<?php echo $cat['category_id']; ?>"
                    <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($cat['category_name']); ?>
                </option>
            <?php } ?>
        </select>

        <!-- DESCRIPTION -->
        <textarea
            name="description"
            placeholder="Course Description"
            rows="4"
            required><?php
                echo $editMode ? htmlspecialchars($editData['description']) : '';
            ?></textarea>

        <!-- CAREER OPPORTUNITIES -->
        <textarea
            name="career_opportunities"
            placeholder="Career Opportunities (e.g. Software Developer, Web Developer)"
            rows="4"
            required><?php
                echo $editMode ? htmlspecialchars($editData['career_opportunities']) : '';
            ?></textarea>

        <!-- DURATION -->
        <input
            type="text"
            name="duration"
            placeholder="Duration (e.g. 4 Years)"
            required
            value="<?php echo $editMode ? htmlspecialchars($editData['duration']) : ''; ?>">

        <!-- RECOMMENDATION REASON -->
        <textarea
            name="recommendation_reason"
            placeholder="Why is this course recommended?"
            rows="4"
            required><?php
                echo $editMode ? htmlspecialchars($editData['recommendation_reason']) : '';
            ?></textarea>

        <!-- BUTTON -->
        <?php if ($editMode) { ?>
            <button type="submit" name="update">
                Update Course
            </button>
        <?php } else { ?>
            <button type="submit" name="add">
                Add Course
            </button>
        <?php } ?>

    </form>

    <!-- TABLE -->
    <table>

        <tr>
            <th>ID</th>
            <th>Course</th>
            <th>Category</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("
            SELECT
                courses.course_id,
                courses.course_name,
                courses.duration,
                categories.category_name
            FROM courses
            INNER JOIN categories
                ON courses.category_id = categories.category_id
            ORDER BY courses.course_id ASC
        ");

        while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
            <td><?php echo htmlspecialchars($row['duration']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['course_id']; ?>">
                    ✏ Edit
                </a>
                |
                <a
                    href="?delete=<?php echo $row['course_id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this course?')">
                    🗑 Delete
                </a>
            </td>
        </tr>
        <?php } ?>

    </table>

    <br>

    <!-- NAVIGATION -->
    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
        <a href="categories.php">📂 Categories</a>
        <a href="users.php">👥 Users</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>