<?php
include "auth_check.php";
include "db.php";

$msg = "";
$duplicate_warning = "";
$show_duplicate_warning = false;

$name_val = "";
$desc_val = "";
$date_val = "";
$loc_val = "";
$status_val = "";
$cat_val = "";

$getCategories = "SELECT * FROM Category";
$categoryResult = mysqli_query($conn, $getCategories);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date_reported'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $category = $_POST['category_id'];

    $name_val = htmlspecialchars($name);
    $desc_val = htmlspecialchars($description);
    $date_val = htmlspecialchars($date);
    $loc_val = htmlspecialchars($location);
    $status_val = htmlspecialchars($status);
    $cat_val = htmlspecialchars($category);

    $confirm_duplicate = isset($_POST['confirm_duplicate']) ? true : false;

    // Check for similar existing item names
    $escaped_name = mysqli_real_escape_string($conn, $name);
    $check_query = "SELECT name FROM Item WHERE name LIKE '%$escaped_name%' OR '$escaped_name' LIKE CONCAT('%', name, '%')";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0 && !$confirm_duplicate) {
        $show_duplicate_warning = true;
        $similar_items = [];
        while ($row = mysqli_fetch_assoc($check_result)) {
            $similar_items[] = $row['name'];
        }
        $duplicate_warning = "Possible duplicate detected! Similar items already exist: <strong>" . htmlspecialchars(implode(', ', array_unique($similar_items))) . "</strong>. If you want to insert this item anyway, please tick the 'Confirm duplicate' checkbox below.";
    } else {
        // Safe to insert
        $escaped_desc = mysqli_real_escape_string($conn, $description);
        $escaped_date = mysqli_real_escape_string($conn, $date);
        $escaped_loc = mysqli_real_escape_string($conn, $location);
        $escaped_status = mysqli_real_escape_string($conn, $status);
        $escaped_cat = mysqli_real_escape_string($conn, $category);

        $query = "
            INSERT INTO Item
            (
                name,
                description,
                date_reported,
                location,
                status,
                original_status,
                category_id
            )
            VALUES
            (
                '$escaped_name',
                '$escaped_desc',
                '$escaped_date',
                '$escaped_loc',
                '$escaped_status',
                '$escaped_status',
                '$escaped_cat'
            )
        ";

        $run = mysqli_query($conn, $query);

        if($run){
            header("Location: items.php");
            exit();
        } else {
            $msg = mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Add Item</h1>

    <?php if($msg != ""){ ?>
        <div class="alert alert-danger">
            <?php echo $msg; ?>
        </div>
    <?php } ?>

    <?php if($show_duplicate_warning){ ?>
        <div class="alert alert-warning">
            <?php echo $duplicate_warning; ?>
        </div>
    <?php } ?>

    <form method="POST">
        <label>Item Name</label>
        <br>
        <input
            type="text"
            name="name"
            value="<?php echo $name_val; ?>"
            required
        >
        <br><br>

        <label>Description</label>
        <br>
        <input
            type="text"
            name="description"
            value="<?php echo $desc_val; ?>"
            required
        >
        <br><br>

        <label>Date</label>
        <br>
        <input
            type="date"
            name="date_reported"
            value="<?php echo $date_val; ?>"
            required
        >
        <br><br>

        <label>Location</label>
        <br>
        <input
            type="text"
            name="location"
            value="<?php echo $loc_val; ?>"
            required
        >
        <br><br>

        <label>Status</label>
        <br>
        <select name="status">
            <option value="Lost" <?php if($status_val == 'Lost') echo 'selected'; ?>>Lost</option>
            <option value="Found" <?php if($status_val == 'Found') echo 'selected'; ?>>Found</option>
        </select>
        <br><br>

        <label>Category</label>
        <br>
        <select name="category_id">
            <?php
            // Since we need to reuse the category list, let's reset the pointer if already run
            mysqli_data_seek($categoryResult, 0);
            while($cat = mysqli_fetch_assoc($categoryResult)){
                $selected = ($cat_val == $cat['category_id']) ? "selected" : "";
                ?>
                <option value="<?php echo $cat['category_id']; ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($cat['category_name']); ?>
                </option>
                <?php
            }
            ?>
        </select>
        <br><br>

        <?php if($show_duplicate_warning){ ?>
            <label style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="confirm_duplicate" value="1" style="width: auto; margin: 0;" required>
                Confirm duplicate and insert item
            </label>
            <br><br>
        <?php } ?>

        <button type="submit" name="submit">
            Add Item
        </button>
    </form>

    <br>
    <a href="items.php" class="btn-link">View Items</a>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>
