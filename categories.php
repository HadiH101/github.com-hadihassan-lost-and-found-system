<?php
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

    <a href="index.php">Home</a>
    <a href="add_category.php">Add Category</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Category ID</th>
            <th>Category Name</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row['category_id']."</td>";
                echo "<td>".$row['category_name']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No categories found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>