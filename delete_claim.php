<?php
include "admin_check.php";
include "db.php";

if(isset($_GET['id'])){
    $claim_id = $_GET['id'];

    $sql = "DELETE FROM Claim WHERE claim_id = '$claim_id'";

    if(mysqli_query($conn, $sql)){
        header("Location: claims.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: claims.php");
    exit();
}
?>
