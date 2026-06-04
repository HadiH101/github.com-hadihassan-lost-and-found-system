<?php
include "auth_check.php";
include "db.php";

$isAdmin = false;
if($_SESSION['role'] == "admin"){
    $isAdmin = true;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category_filter = isset($_GET['category_id']) ? trim($_GET['category_id']) : "";
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : "";

$where_clauses = [];
if ($search !== "") {
    $escaped_search = mysqli_real_escape_string($conn, $search);
    $where_clauses[] = "Item.name LIKE '%$escaped_search%'";
}
if ($category_filter !== "") {
    $escaped_cat = mysqli_real_escape_string($conn, $category_filter);
    $where_clauses[] = "Item.category_id = '$escaped_cat'";
}
if ($status_filter !== "") {
    $escaped_status = mysqli_real_escape_string($conn, $status_filter);
    $where_clauses[] = "Item.status = '$escaped_status'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

$query = "
    SELECT Item.*, Category.category_name
    FROM Item
    LEFT JOIN Category ON Item.category_id = Category.category_id
    $where_sql
    ORDER BY Item.item_id DESC
";

$result = mysqli_query($conn, $query);

$cat_query = "SELECT * FROM Category";
$cat_result = mysqli_query($conn, $cat_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Items</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Items</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="add_item.php">Add Item</a>
        <?php if($isAdmin){ ?>
            <a href="claims.php">All Claims</a>
        <?php } else { ?>
            <a href="my_claims.php">My Claims</a>
        <?php } ?>
    </div>

    <br>

    <form method="GET" class="search-form">
        <div class="search-field">
            <label>Item Name</label>
            <input 
                type="text" 
                name="search" 
                placeholder="search item..." 
                value="<?php echo htmlspecialchars($search); ?>"
            >
        </div>

        <div class="search-field">
            <label>Category</label>
            <select name="category_id">
                <option value="">-- All Categories --</option>
                <?php while($cat = mysqli_fetch_assoc($cat_result)){ ?>
                    <option value="<?php echo $cat['category_id']; ?>" <?php if($category_filter == $cat['category_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="search-field">
            <label>Status</label>
            <select name="status">
                <option value="">-- All Statuses --</option>
                <option value="Lost" <?php if($status_filter == 'Lost') echo 'selected'; ?>>Lost</option>
                <option value="Found" <?php if($status_filter == 'Found') echo 'selected'; ?>>Found</option>
                <option value="Claim Pending" <?php if($status_filter == 'Claim Pending') echo 'selected'; ?>>Claim Pending</option>
                <option value="Claim Approved" <?php if($status_filter == 'Claim Approved') echo 'selected'; ?>>Claim Approved</option>
                <option value="Returned" <?php if($status_filter == 'Returned') echo 'selected'; ?>>Returned</option>
            </select>
        </div>

        <button type="submit">Search & Filter</button>
    </form>

    <br>

    <table width="100%" border="1" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Item</th>
            <th>Description</th>
            <th>Date Reported</th>
            <th>Location</th>
            <th>Status</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td><?php echo $row['item_id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo $row['date_reported']; ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $class = "status-pending";
                        if($status == "Claim Approved" || $status == "Returned"){
                            $class = "status-approved";
                        } else if($status == "Lost" || $status == "Found"){
                            $class = "status-pending";
                        } else if($status == "Claim Pending") {
                            $class = "status-pending"; // Uses orange color
                        }
                        ?>
                        <span class="<?php echo $class; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td>
                        <?php if($isAdmin){ ?>
                            <a href="delete_item.php?id=<?php echo $row['item_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" style="color: var(--danger-red); font-weight: bold; text-decoration: none;">Delete</a>
                        <?php } else { ?>
                            <?php if($row['status'] == "Lost" || $row['status'] == "Found"){ ?>
                                <a href="add_claim.php?item_id=<?php echo $row['item_id']; ?>" style="color: var(--primary-orange); font-weight: bold; text-decoration: none;">Claim This</a>
                            <?php } else { ?>
                                <span style="color: #999;">-</span>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="8" align="center">No items found</td>
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