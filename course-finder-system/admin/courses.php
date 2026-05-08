<?php
include("../db/connection.php");
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// CREATE
if (isset($_POST['add'])) {
    $name = $_POST['course_name'];
    $cat = $_POST['category_id'];

    $conn->query("INSERT INTO courses (course_name, category_id)
                  VALUES ('$name', '$cat')");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM courses WHERE course_id=$id");
}
?>

<h2>Courses</h2>

<form method="POST">
    <input type="text" name="course_name" placeholder="Course Name" required>
    <input type="number" name="category_id" placeholder="Category ID" required>
    <button type="submit" name="add">Add</button>
</form>

<br>

<table border="1">
<tr>
    <th>ID</th>
    <th>Course</th>
    <th>Category</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM courses");

while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $row['course_id']; ?></td>
    <td><?php echo $row['course_name']; ?></td>
    <td><?php echo $row['category_id']; ?></td>
    <td>
        <a href="?delete=<?php echo $row['course_id']; ?>">Delete</a>
    </td>
</tr>
<?php } ?>
</table>