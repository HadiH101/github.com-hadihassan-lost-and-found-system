<?php
include "auth_check.php";
include "db.php";

$msg = "";
$userId = $_SESSION['user_id'];

// Get item ID from URL if provided
$selected_item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

if(isset($_POST['submit'])){
    $item = intval($_POST['item_id']);
    $date = mysqli_real_escape_string($conn, $_POST['claim_date']);

    // Check if the item is still available for claiming (Lost or Found)
    $check_item = mysqli_query($conn, "SELECT status FROM Item WHERE item_id = '$item'");
    $item_data = mysqli_fetch_assoc($check_item);

    if ($item_data && ($item_data['status'] == 'Lost' || $item_data['status'] == 'Found')) {
        // Start transaction or do sequential queries
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
            // Update the item status to 'Claim Pending'
            $updateItem = "UPDATE Item SET status = 'Claim Pending' WHERE item_id = '$item'";
            mysqli_query($conn, $updateItem);

            header("Location: my_claims.php");
            exit();
        } else {
            $msg = "Error submitting claim: " . mysqli_error($conn);
        }
    } else {
        $msg = "This item is not available for claiming.";
    }
}

// Fetch items that are Lost or Found, OR matches the pre-selected item from GET parameter
$getItems = "
    SELECT * FROM Item 
    WHERE status IN ('Lost', 'Found') 
    " . ($selected_item_id > 0 ? " OR item_id = $selected_item_id" : "") . "
    ORDER BY name ASC
";
$itemResult = mysqli_query($conn, $getItems);
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

    <?php if($msg != ""){ ?>
        <div class="alert alert-danger">
            <?php echo $msg; ?>
        </div>
    <?php } ?>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="items.php">View Items</a>
        <a href="my_claims.php">My Claims</a>
    </div>

    <br>

    <form method="POST">
        <label>Item</label>
        <br>
        <select name="item_id">
            <?php
            if (mysqli_num_rows($itemResult) > 0) {
                while($item = mysqli_fetch_assoc($itemResult)){
                    $selected = ($item['item_id'] == $selected_item_id) ? "selected" : "";
                    ?>
                    <option value="<?php echo $item['item_id']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($item['name']) . " (" . htmlspecialchars($item['status']) . ")"; ?>
                    </option>
                    <?php
                }
            } else {
                echo "<option value=''>No items available to claim</option>";
            }
            ?>
        </select>
        <br><br>

        <label>Claim Date</label>
        <br>
        <input
            type="date"
            name="claim_date"
            value="<?php echo date('Y-m-d'); ?>"
            required
        >
        <br><br>

        <button type="submit" name="submit">
            Submit Claim
        </button>
    </form>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>