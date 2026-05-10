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
    $name = $_POST['category_name'];

    $conn->query("INSERT INTO categories (category_name)
                  VALUES ('$name')");

    $message = "Category added successfully!";
}

/* DELETE */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $conn->query("DELETE FROM categories WHERE category_id = $id");

    $message = "Category deleted successfully!";
}

/* UPDATE */
if (isset($_POST['update'])) {
    $id = $_POST['category_id'];
    $name = $_POST['category_name'];

    $conn->query("UPDATE categories
                  SET category_name = '$name'
                  WHERE category_id = $id");

    $message = "Category updated successfully!";
}

/* EDIT MODE */
$editMode = false;
$editData = null;

if (isset($_GET['edit'])) {
    $editMode = true;

    $id = $_GET['edit'];
    $editQuery = $conn->query("SELECT * FROM categories WHERE category_id = $id");
    $editData = $editQuery->fetch_assoc();
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container">

    <h1>📂 Manage Categories</h1>

    <p style="text-align:center; color:gray;">
        Add, update, and manage course categories for the system.
    </p>

    <!-- MESSAGE -->
    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>

    <!-- FORM -->
    <form method="POST">

        <?php if ($editMode) { ?>
            <input type="hidden" name="category_id"
                   value="<?php echo $editData['category_id']; ?>">
        <?php } ?>

        <input type="text"
               name="category_name"
               placeholder="Category Name"
               required
               value="<?php echo $editMode ? $editData['category_name'] : ''; ?>">

        <?php if ($editMode) { ?>
            <button type="submit" name="update">Update Category</button>
        <?php } else { ?>
            <button type="submit" name="add">Add Category</button>
        <?php } ?>

    </form>

    <!-- TABLE -->
    <table>

        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM categories ORDER BY category_id ASC");

        while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $row['category_id']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['category_id']; ?>">✏ Edit</a>

                <a href="?delete=<?php echo $row['category_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this category?')">
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
        <a href="courses.php">📚 Courses</a>
        <a href="users.php">👥 Users</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>