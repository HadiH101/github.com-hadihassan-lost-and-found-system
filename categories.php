<?php
include "admin_check.php";
include "db.php";

$sql = "SELECT * FROM Category";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Categories</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="add_category.php">Add Category</a>
        <a href="items.php">Items</a>
        <a href="claims.php">All Claims</a>
        <a href="users.php">Users</a>
    </div>

    <br>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Category ID</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['category_id']."</td>";
                echo "<td><strong>".htmlspecialchars($row['category_name'])."</strong></td>";
                echo "<td><a href='delete_category.php?id=".$row['category_id']."' onclick=\"return confirm('Are you sure you want to delete this category?');\" style='color: var(--danger-red); font-weight: bold; text-decoration: none;'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3' align='center'>No categories found</td></tr>";
        }
        ?>
    </table>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>