<?php

include "auth_check.php";
include "db.php";

$query = "

    SELECT
        Report.*,
        Item.name AS item_name,
        User.name AS user_name

    FROM Report

    LEFT JOIN Item
    ON Report.item_id = Item.item_id

    LEFT JOIN User
    ON Report.user_id = User.user_id

    ORDER BY Report.report_id DESC

";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Reports</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="main-box">

    <h1>Reports</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="add_report.php">Add Report</a>
    </div>

    <br><br>

    <table width="100%" border="1" cellspacing="0">

        <tr>

            <th>ID</th>
            <th>User</th>
            <th>Item</th>
            <th>Type</th>
            <th>Date</th>

        </tr>

        <?php

        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_assoc($result)){

                ?>

                <tr>

                    <td>
                        <?php echo $row['report_id']; ?>
                    </td>

                    <td>
                        <?php echo $row['user_name']; ?>
                    </td>

                    <td>
                        <?php echo $row['item_name']; ?>
                    </td>

                    <td>
                        <?php echo $row['report_type']; ?>
                    </td>

                    <td>
                        <?php echo $row['report_date']; ?>
                    </td>

                </tr>

                <?php
            }

        } else {

            ?>

            <tr>

                <td colspan="5">
                    no reports yet
                </td>

            </tr>

            <?php
        }

        ?>

    </table>

</div>

</body>
</html>