<?php
include "admin_check.php";
include "db.php";

if(isset($_GET['id'])){
    $user_id = $_GET['id'];

    $sql = "DELETE FROM User WHERE user_id = '$user_id'";

    if(mysqli_query($conn, $sql)){
        header("Location: users.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: users.php");
    exit();
}
?>
