<?php
include "admin_check.php";
include "db.php";

$msg = "";

if(isset($_POST['submit'])){
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $sql = "INSERT INTO Category (category_name)
            VALUES ('$category_name')";

    if(mysqli_query($conn, $sql)){
        header("Location: categories.php");
        exit();
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Add Category</h1>

    <?php if($msg != ""){ ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php } ?>

    <div class="menu-links">
        <a href="categories.php">Back to Categories</a>
    </div>

    <br>

    <form method="POST">
        <label>Category Name</label><br>
        <input type="text" name="category_name" required><br><br>

        <button type="submit" name="submit">Add Category</button>
    </form>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>