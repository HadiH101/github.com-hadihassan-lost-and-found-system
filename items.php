<?php

include "auth_check.php";
include "db.php";

$search = "";

if(isset($_GET['search'])){
    $search = $_GET['search'];

    $query = "
        SELECT Item.*, Category.category_name
        FROM Item

        LEFT JOIN Category
        ON Item.category_id = Category.category_id

        WHERE Item.name LIKE '%$search%'
    ";

} else {

    $query = "
        SELECT Item.*, Category.category_name
        FROM Item

        LEFT JOIN Category
        ON Item.category_id = Category.category_id
    ";
}

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Items</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="main-box">

    <h1>Items</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="add_item.php">Add Item</a>
    </div>

    <br>

    <form method="GET">

        <input
            type="text"
            name="search"
            placeholder="search item..."
            value="<?php echo $search; ?>"
        >

        <button type="submit">
            Search
        </button>

    </form>

    <br><br>

    <table width="100%" border="1" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Item</th>
            <th>Description</th>
            <th>Date</th>
            <th>Location</th>
            <th>Status</th>
            <th>Category</th>
        </tr>

        <?php

        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_assoc($result)){

                ?>

                <tr>

                    <td><?php echo $row['item_id']; ?></td>

                    <td><?php echo $row['name']; ?></td>

                    <td><?php echo $row['description']; ?></td>

                    <td><?php echo $row['date_reported']; ?></td>

                    <td><?php echo $row['location']; ?></td>

                    <td><?php echo $row['status']; ?></td>

                    <td><?php echo $row['category_name']; ?></td>

                </tr>

                <?php
            }

        } else {

            ?>

            <tr>
                <td colspan="7">
                    no items found
                </td>
            </tr>

            <?php
        }

        ?>

    </table>

</div>

</body>
</html>