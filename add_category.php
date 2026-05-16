<?php
include "admin_check.php";
include "db.php";

$msg = "";

if(isset($_POST['submit'])){

    $category_name = $_POST['category_name'];

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

    <?php
    if($msg != ""){
        echo "<p style='color:red;'>$msg</p>";
    }
    ?>

    <form method="POST">
        <label>Category Name</label><br>
        <input type="text" name="category_name" required><br><br>

        <button type="submit" name="submit">Add Category</button>
    </form>

    <br>
    <a href="categories.php">View Categories</a>
    <br><br>
    <a href="index.php">Home</a>
</div>

</body>
</html>