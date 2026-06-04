<?php
include "admin_check.php";
include "db.php";

$msg = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: claims.php");
    exit();
}

// Fetch claim details to display context
$claim_query = "
    SELECT Claim.*, Item.name AS item_name, User.name AS claimant_name
    FROM Claim
    LEFT JOIN Item ON Claim.item_id = Item.item_id
    LEFT JOIN User ON Claim.user_id = User.user_id
    WHERE Claim.claim_id = '$id'
";
$claim_result = mysqli_query($conn, $claim_query);
$claim = mysqli_fetch_assoc($claim_result);

if (!$claim) {
    header("Location: claims.php");
    exit();
}

if(isset($_POST['submit_rejection'])){
    $reason = mysqli_real_escape_string($conn, $_POST['rejection_reason']);
    $adminId = $_SESSION['user_id'];
    $itemId = $claim['item_id'];

    if (empty(trim($reason))) {
        $msg = "Rejection reason is required.";
    } else {
        // 1. Update Claim status, verified_by, and rejection_reason
        $queryClaim = "
            UPDATE Claim
            SET claim_status = 'Rejected', verified_by = '$adminId', rejection_reason = '$reason'
            WHERE claim_id = '$id'
        ";
        mysqli_query($conn, $queryClaim);

        // 2. Revert Item status back to its original_status (Lost or Found)
        $queryItem = "
            UPDATE Item
            SET status = original_status
            WHERE item_id = '$itemId'
        ";
        mysqli_query($conn, $queryItem);

        // 3. Log the activity
        $logAction = "INSERT INTO activity_log (action, claim_id, admin_id) VALUES ('Claim Rejected', '$id', '$adminId')";
        mysqli_query($conn, $logAction);

        header("Location: claims.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reject Claim</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Reject Claim</h1>

    <?php if($msg != ""){ ?>
        <div class="alert alert-danger">
            <?php echo $msg; ?>
        </div>
    <?php } ?>

    <div class="menu-links">
        <a href="claims.php">Back to Claims</a>
    </div>

    <br>

    <div class="profile-info" style="margin-bottom: 25px;">
        <div class="profile-row">
            <span class="profile-label">Claim ID</span>
            <span class="profile-value"><?php echo $claim['claim_id']; ?></span>
        </div>
        <div class="profile-row">
            <span class="profile-label">Claimant</span>
            <span class="profile-value"><?php echo htmlspecialchars($claim['claimant_name']); ?></span>
        </div>
        <div class="profile-row">
            <span class="profile-label">Item</span>
            <span class="profile-value"><strong><?php echo htmlspecialchars($claim['item_name']); ?></strong></span>
        </div>
    </div>

    <form method="POST">
        <label>Reason for Rejection</label>
        <br>
        <textarea name="rejection_reason" rows="4" placeholder="Enter reason why this claim is being rejected..." required></textarea>
        <br><br>

        <button type="submit" name="submit_rejection" style="background: var(--danger-red);">
            Reject Claim
        </button>
    </form>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>