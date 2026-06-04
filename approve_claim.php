<?php
include "admin_check.php";
include "db.php";

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $adminId = $_SESSION['user_id'];

    // 1. Get the item_id associated with this claim
    $findItem = mysqli_query($conn, "SELECT item_id FROM Claim WHERE claim_id = '$id'");
    if($claim_data = mysqli_fetch_assoc($findItem)){
        $itemId = $claim_data['item_id'];

        // 2. Update Claim status and verified_by
        $queryClaim = "
            UPDATE Claim
            SET claim_status = 'Approved', verified_by = '$adminId'
            WHERE claim_id = '$id'
        ";
        mysqli_query($conn, $queryClaim);

        // 3. Update Item status
        $queryItem = "
            UPDATE Item
            SET status = 'Claim Approved'
            WHERE item_id = '$itemId'
        ";
        mysqli_query($conn, $queryItem);

        // 4. Log the admin activity
        $logAction = "INSERT INTO activity_log (action, claim_id, admin_id) VALUES ('Claim Approved', '$id', '$adminId')";
        mysqli_query($conn, $logAction);
    }
}

header("Location: claims.php");
exit();
?>