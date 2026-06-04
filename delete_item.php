<?php
include "admin_check.php";
include "db.php";

if(isset($_GET['id'])){
    $item_id = $_GET['id'];

    $sql = "DELETE FROM Item WHERE item_id = '$item_id'";

    if(mysqli_query($conn, $sql)){
        header("Location: items.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: items.php");
    exit();
}
?>
