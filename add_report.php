<?php

include "auth_check.php";
include "db.php";

$msg = "";

$getItems = "SELECT * FROM Item";
$itemResult = mysqli_query($conn, $getItems);

$userId = $_SESSION['user_id'];

if(isset($_POST['submit'])){

    $item = $_POST['item_id'];
    $type = $_POST['report_type'];
    $date = $_POST['report_date'];

    $query = "

        INSERT INTO Report
        (
            user_id,
            item_id,
            report_type,
            report_date
        )

        VALUES
        (
            '$userId',
            '$item',
            '$type',
            '$date'
        )

    ";

    $run = mysqli_query($conn, $query);

    if($run){

        header("Location: reports.php");
        exit();

    } else {

        $msg = mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Add Report</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="main-box">

    <h1>Add Report</h1>

    <?php

    if($msg != ""){

        ?>

        <p style="color:red;">
            <?php echo $msg; ?>
        </p>

        <?php
    }

    ?>

    <form method="POST">

        <label>Item</label>
        <br>

        <select name="item_id">

            <?php

            while($item = mysqli_fetch_assoc($itemResult)){

                ?>

                <option value="<?php echo $item['item_id']; ?>">

                    <?php echo $item['name']; ?>

                </option>

                <?php
            }

            ?>

        </select>

        <br><br>

        <label>Report Type</label>
        <br>

        <select name="report_type">

            <option value="Lost">
                Lost
            </option>

            <option value="Found">
                Found
            </option>

        </select>

        <br><br>

        <label>Date</label>
        <br>

        <input
            type="date"
            name="report_date"
            required
        >

        <br><br>

        <button type="submit" name="submit">
            Submit Report
        </button>

    </form>

    <br>

    <a href="reports.php">
        View Reports
    </a>

</div>

</body>
</html>