<?php
include "auth_check.php";
include "db.php";

$userId = $_SESSION['user_id'];

$query = "
    SELECT 
        Claim.*, 
        Item.name AS item_name,
        Item.status AS item_status
    FROM Claim
    LEFT JOIN Item ON Claim.item_id = Item.item_id
    WHERE Claim.user_id = ?
    ORDER BY Claim.claim_id DESC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Claims</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>My Claims</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="items.php">Items</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <br>

    <table width="100%" border="1" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Claim Date</th>
            <th>Claim Status</th>
            <th>Item Current Status</th>
            <th>Rejection Reason</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td><?php echo $row['claim_id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['item_name']); ?></strong></td>
                    <td><?php echo $row['claim_date']; ?></td>
                    <td>
                        <?php
                        $c_status = $row['claim_status'];
                        $class = "status-pending";
                        if($c_status == "Approved"){
                            $class = "status-approved";
                        } else if($c_status == "Rejected"){
                            $class = "status-rejected";
                        }
                        ?>
                        <span class="<?php echo $class; ?>">
                            <?php echo htmlspecialchars($c_status); ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $i_status = $row['item_status'];
                        $i_class = "status-pending";
                        if($i_status == "Claim Approved" || $i_status == "Returned"){
                            $i_class = "status-approved";
                        } else if($i_status == "Lost" || $i_status == "Found"){
                            $i_class = "status-pending";
                        }
                        ?>
                        <span class="<?php echo $i_class; ?>">
                            <?php echo htmlspecialchars($i_status); ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        if ($c_status == "Rejected" && !empty($row['rejection_reason'])) {
                            echo '<span style="color: var(--danger-red); font-style: italic;">' . htmlspecialchars($row['rejection_reason']) . '</span>';
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">No claims submitted yet.</td>
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
