<?php

include "admin_check.php";
include "db.php";

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $query = "

        UPDATE Claim

        SET claim_status = 'Approved'

        WHERE claim_id = '$id'

    ";

    mysqli_query($conn, $query);
}

header("Location: claims.php");
exit();

?>