<?php
include "auth_check.php";
include "db.php";

$isAdmin = false;
if($_SESSION['role'] == "admin"){
    $isAdmin = true;
}

// Handler for marking items as Returned
if ($isAdmin && isset($_GET['action']) && $_GET['action'] == 'mark_returned' && isset($_GET['claim_id'])) {
    $claimId = intval($_GET['claim_id']);
    
    // Find the item_id associated with this claim
    $findItem = mysqli_query($conn, "SELECT item_id FROM Claim WHERE claim_id = '$claimId'");
    if($claim_data = mysqli_fetch_assoc($findItem)){
        $itemId = $claim_data['item_id'];
        
        // Update Item status to Returned
        mysqli_query($conn, "UPDATE Item SET status = 'Returned' WHERE item_id = '$itemId'");
        
        // Log action
        $adminId = $_SESSION['user_id'];
        mysqli_query($conn, "INSERT INTO activity_log (action, claim_id, admin_id) VALUES ('Item Marked Returned', '$claimId', '$adminId')");
    }
    header("Location: claims.php");
    exit();
}

$query = "
    SELECT
        Claim.*,
        User.name AS user_name,
        Item.name AS item_name,
        Item.status AS item_status
    FROM Claim
    LEFT JOIN User ON Claim.user_id = User.user_id
    LEFT JOIN Item ON Claim.item_id = Item.item_id
    ORDER BY Claim.claim_id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Claims</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>All Claims</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="items.php">View Items</a>
    </div>

    <br>

    <table width="100%" border="1" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Item</th>
            <th>Claim Status</th>
            <th>Item Status</th>
            <th>Date</th>
            <th>Proof Description</th>
            <th>Proof Image</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td><?php echo $row['claim_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><strong><?php echo htmlspecialchars($row['item_name']); ?></strong></td>
                    <td>
                        <?php
                        $status = $row['claim_status'];
                        $class = "status-pending";
                        if($status == "Approved"){
                            $class = "status-approved";
                        } else if($status == "Rejected"){
                            $class = "status-rejected";
                        }
                        ?>
                        <span class="<?php echo $class; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $item_status = $row['item_status'];
                        $item_class = "status-pending";
                        if($item_status == "Claim Approved" || $item_status == "Returned"){
                            $item_class = "status-approved";
                        }
                        ?>
                        <span class="<?php echo $item_class; ?>">
                            <?php echo htmlspecialchars($item_status); ?>
                        </span>
                    </td>
                    <td><?php echo $row['claim_date']; ?></td>
                    <td>
                        <?php echo $row['proof_description'] ? htmlspecialchars($row['proof_description']) : '-'; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['proof_image'])) { ?>
                            <a href="<?php echo htmlspecialchars($row['proof_image']); ?>" target="_blank">View Image</a>
                        <?php } else { ?>
                            -
                        <?php } ?>
                    </td>

                    <?php if($isAdmin){ ?>
                        <td>
                            <?php if($row['claim_status'] == "Pending"){ ?>
                                <a href="approve_claim.php?id=<?php echo $row['claim_id']; ?>" style="color: var(--success-green); font-weight: bold; text-decoration: none;">Approve</a>
                                |
                                <a href="reject_claim.php?id=<?php echo $row['claim_id']; ?>" style="color: var(--danger-red); font-weight: bold; text-decoration: none;">Reject</a>
                            <?php } else if($row['claim_status'] == "Approved" && $row['item_status'] != "Returned"){ ?>
                                <a href="claims.php?action=mark_returned&claim_id=<?php echo $row['claim_id']; ?>" style="color: var(--primary-orange); font-weight: bold; text-decoration: none;">Mark Returned</a>
                            <?php } else { ?>
                                <span style="color: #999;">-</span>
                            <?php } ?>
                            |
                            <a href="delete_claim.php?id=<?php echo $row['claim_id']; ?>" onclick="return confirm('Are you sure you want to delete this claim?');" style="color: var(--danger-red); text-decoration: none;">Delete</a>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="9" align="center">No claims submitted</td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>