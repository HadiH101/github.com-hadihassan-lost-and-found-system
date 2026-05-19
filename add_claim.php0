<?php

include "auth_check.php";
include "db.php";

$msg = "";

$getItems = "SELECT * FROM Item";
$itemResult = mysqli_query($conn, $getItems);

$userId = $_SESSION['user_id'];

if(isset($_POST['submit'])){

    $item = $_POST['item_id'];
    $date = $_POST['claim_date'];

    $query = "

        INSERT INTO Claim
        (
            user_id,
            item_id,
            claim_status,
            claim_date
        )

        VALUES
        (
            '$userId',
            '$item',
            'Pending',
            '$date'
        )

    ";

    $run = mysqli_query($conn, $query);

    if($run){

        header("Location: claims.php");
        exit();

    } else {

        $msg = mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Submit Claim</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="main-box">

    <h1>Submit Claim</h1>

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

        <label>Claim Date</label>
        <br>

        <input
            type="date"
            name="claim_date"
            required
        >

        <br><br>

        <button type="submit" name="submit">
            Submit Claim
        </button>

    </form>

    <br>

    <a href="claims.php">
        View Claims
    </a>

</div>

</body>
</html>