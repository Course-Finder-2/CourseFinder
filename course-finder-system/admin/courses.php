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
    $name = $_POST['course_name'];
    $cat = $_POST['category_id'];

    if ($conn->query("INSERT INTO courses (course_name, category_id)
                      VALUES ('$name', '$cat')")) {
        $message = "Course added successfully!";
    } else {
        $message = "Database Error: " . $conn->error;
    }
}

/* DELETE */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if ($conn->query("DELETE FROM courses WHERE course_id=$id")) {
        $message = "Course deleted successfully!";
    } else {
        $message = "Database Error: " . $conn->error;
    }
}

/* UPDATE */
if (isset($_POST['update'])) {
    $id = $_POST['course_id'];
    $name = $_POST['course_name'];
    $cat = $_POST['category_id'];

    if ($conn->query("UPDATE courses
                      SET course_name='$name',
                          category_id='$cat'
                      WHERE course_id=$id")) {
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

    $id = $_GET['edit'];
    $editQuery = $conn->query("SELECT * FROM courses WHERE course_id=$id");
    $editData = $editQuery->fetch_assoc();
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container">

    <h2>📚 Manage Courses</h2>

    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <!-- FORM -->
    <form method="POST">

        <?php if ($editMode) { ?>
            <input type="hidden" name="course_id"
                   value="<?php echo $editData['course_id']; ?>">
        <?php } ?>

        <!-- Course Name -->
        <input type="text"
               name="course_name"
               placeholder="Course Name"
               required
               value="<?php echo $editMode ? $editData['course_name'] : ''; ?>">

        <!-- Category Dropdown -->
        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php
            $categories = $conn->query("SELECT * FROM categories");

            while ($cat = $categories->fetch_assoc()) {
                $selected = "";

                if ($editMode && $editData['category_id'] == $cat['category_id']) {
                    $selected = "selected";
                }
            ?>
                <option value="<?php echo $cat['category_id']; ?>" <?php echo $selected; ?>>
                    <?php echo $cat['category_name']; ?>
                </option>
            <?php } ?>
        </select>

        <!-- Submit Button -->
        <?php if ($editMode) { ?>
            <button type="submit" name="update">Update Course</button>
        <?php } else { ?>
            <button type="submit" name="add">Add Course</button>
        <?php } ?>

    </form>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
            <th>Course</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php
        // INNER JOIN to show category names instead of IDs
        $result = $conn->query("
            SELECT courses.course_id,
                   courses.course_name,
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
            <td><?php echo $row['course_name']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['course_id']; ?>">✏ Edit</a>
                |
                <a href="?delete=<?php echo $row['course_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this course?')">
                   🗑 Delete
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>

    <div class="links">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>