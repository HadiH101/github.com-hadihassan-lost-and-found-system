<?php
include "admin_check.php";
include "db.php";

if(isset($_GET['id'])){
    $category_id = $_GET['id'];

    $sql = "DELETE FROM Category WHERE category_id = '$category_id'";

    if(mysqli_query($conn, $sql)){
        header("Location: categories.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: categories.php");
    exit();
}
?>
