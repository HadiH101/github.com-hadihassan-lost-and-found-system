<?php

include "auth_check.php";
include "db.php";

$isAdmin = false;

if($_SESSION['role'] == "admin"){
    $isAdmin = true;
}

$query = "

    SELECT
        Claim.*,
        User.name AS user_name,
        Item.name AS item_name

    FROM Claim

    LEFT JOIN User
    ON Claim.user_id = User.user_id

    LEFT JOIN Item
    ON Claim.item_id = Item.item_id

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

    <h1>Claims</h1>

    <div class="menu-links">

        <a href="index.php">Home</a>
        <a href="add_claim.php">Submit Claim</a>

    </div>

    <br><br>

    <table width="100%" border="1" cellspacing="0">

        <tr>

            <th>ID</th>
            <th>User</th>
            <th>Item</th>
            <th>Status</th>
            <th>Date</th>

            <?php

            if($isAdmin){

                ?>

                <th>Action</th>

                <?php
            }

            ?>

        </tr>

        <?php

        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_assoc($result)){

                ?>

                <tr>

                    <td>
                        <?php echo $row['claim_id']; ?>
                    </td>

                    <td>
                        <?php echo $row['user_name']; ?>
                    </td>

                    <td>
                        <?php echo $row['item_name']; ?>
                    </td>

                    <td>
                        <?php echo $row['claim_status']; ?>
                    </td>

                    <td>
                        <?php echo $row['claim_date']; ?>
                    </td>

                    <?php

                    if($isAdmin){

                        ?>

                        <td>

                            <?php

                            if($row['claim_status'] == "Pending"){

                                ?>

                                <a href="approve_claim.php?id=<?php echo $row['claim_id']; ?>">
                                    Approve
                                </a>

                                |

                                <a href="reject_claim.php?id=<?php echo $row['claim_id']; ?>">
                                    Reject
                                </a>

                                <?php

                            } else {

                                echo "-";
                            }

                            ?>

                        </td>

                        <?php
                    }

                    ?>

                </tr>

                <?php
            }

        } else {

            ?>

            <tr>

                <td colspan="6">
                    no claims submitted
                </td>

            </tr>

            <?php
        }

        ?>

    </table>

</div>

</body>
</html>