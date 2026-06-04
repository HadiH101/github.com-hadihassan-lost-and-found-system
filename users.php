<?php
include "admin_check.php";
include "db.php";

$sql = "SELECT * FROM `User`";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users - Lost and Found System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Users</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="add_users.php">Add User</a>
        <a href="items.php">Items</a>
        <a href="claims.php">All Claims</a>
        <a href="categories.php">Categories</a>
    </div>

    <br>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['user_id']."</td>";
                echo "<td><strong>".htmlspecialchars($row['name'])."</strong></td>";
                echo "<td>".htmlspecialchars($row['email'])."</td>";
                echo "<td>".htmlspecialchars($row['phone'])."</td>";
                echo "<td style='text-transform: capitalize; font-weight: bold; color: var(--primary-orange);'>".htmlspecialchars($row['role'])."</td>";
                echo "<td><a href='delete_user.php?id=".$row['user_id']."' onclick=\"return confirm('Are you sure you want to delete this user?');\" style='color: var(--danger-red); font-weight: bold; text-decoration: none;'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' align='center'>No users found</td></tr>";
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