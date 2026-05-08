<?php
include("../db/connection.php");
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// CREATE
if (isset($_POST['add'])) {
    $name = $_POST['category_name'];

    $conn->query("INSERT INTO categories (category_name)
                  VALUES ('$name')");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE category_id=$id");
}
?>

<h2>Categories</h2>

<form method="POST">
    <input type="text" name="category_name" placeholder="Category Name" required>
    <button type="submit" name="add">Add</button>
</form>

<br>

<table border="1">
<tr>
    <th>ID</th>
    <th>Category</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM categories");

while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $row['category_id']; ?></td>
    <td><?php echo $row['category_name']; ?></td>
    <td>
        <a href="?delete=<?php echo $row['category_id']; ?>">Delete</a>
    </td>
</tr>
<?php } ?>
</table>