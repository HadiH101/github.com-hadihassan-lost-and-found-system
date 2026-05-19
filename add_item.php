<?php

include "auth_check.php";
include "db.php";

$msg = "";

$getCategories = "SELECT * FROM Category";
$categoryResult = mysqli_query($conn, $getCategories);

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date_reported'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $category = $_POST['category_id'];

    $query = "
        INSERT INTO Item
        (
            name,
            description,
            date_reported,
            location,
            status,
            category_id
        )

        VALUES
        (
            '$name',
            '$description',
            '$date',
            '$location',
            '$status',
            '$category'
        )
    ";

    $run = mysqli_query($conn, $query);

    if($run){

        header("Location: items.php");
        exit();

    } else {

        $msg = mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Add Item</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="main-box">

    <h1>Add Item</h1>

    <?php

    if($msg != ""){

        ?>

        <p style="color:red;">
            <?php echo $msg; ?>
        </p>

        <?php
    }

    ?>

    <form method="POST">

        <label>Item Name</label>
        <br>

        <input
            type="text"
            name="name"
            required
        >

        <br><br>

        <label>Description</label>
        <br>

        <input
            type="text"
            name="description"
            required
        >

        <br><br>

        <label>Date</label>
        <br>

        <input
            type="date"
            name="date_reported"
            required
        >

        <br><br>

        <label>Location</label>
        <br>

        <input
            type="text"
            name="location"
            required
        >

        <br><br>

        <label>Status</label>
        <br>

        <select name="status">

            <option value="Lost">
                Lost
            </option>

            <option value="Found">
                Found
            </option>

        </select>

        <br><br>

        <label>Category</label>
        <br>

        <select name="category_id">

            <?php

            while($cat = mysqli_fetch_assoc($categoryResult)){

                ?>

                <option value="<?php echo $cat['category_id']; ?>">

                    <?php echo $cat['category_name']; ?>

                </option>

                <?php
            }

            ?>

        </select>

        <br><br>

        <button type="submit" name="submit">
            Add Item
        </button>

    </form>

    <br>

    <a href="items.php">
        View Items
    </a>

</div>

</body>
</html>