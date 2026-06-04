<?php
session_start();
include "db.php";

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && ($_SESSION['role'] == 'admin');

// Fetch dashboard stats if admin
if ($isAdmin) {
    // Total Users
    $resUsers = mysqli_query($conn, "SELECT COUNT(*) AS total FROM User");
    $totalUsers = mysqli_fetch_assoc($resUsers)['total'];

    // Total Items
    $resItems = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Item");
    $totalItems = mysqli_fetch_assoc($resItems)['total'];

    // Total Categories
    $resCats = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Category");
    $totalCategories = mysqli_fetch_assoc($resCats)['total'];

    // Total Claims
    $resClaims = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Claim");
    $totalClaims = mysqli_fetch_assoc($resClaims)['total'];

    // Pending Claims
    $resPending = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Claim WHERE claim_status = 'Pending'");
    $totalPending = mysqli_fetch_assoc($resPending)['total'];

    // Approved Claims
    $resApproved = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Claim WHERE claim_status = 'Approved'");
    $totalApproved = mysqli_fetch_assoc($resApproved)['total'];

    // Rejected Claims
    $resRejected = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Claim WHERE claim_status = 'Rejected'");
    $totalRejected = mysqli_fetch_assoc($resRejected)['total'];

    // Activity Logs
    $logsQuery = "
        SELECT activity_log.*, User.name AS admin_name 
        FROM activity_log 
        LEFT JOIN User ON activity_log.admin_id = User.user_id 
        ORDER BY activity_log.timestamp DESC 
        LIMIT 10
    ";
    $logsResult = mysqli_query($conn, $logsQuery);
} else {
    // Regular user or guest
    $countQuery = "SELECT COUNT(*) AS total_items FROM Item";
    $countResult = mysqli_query($conn, $countQuery);
    $totalItems = mysqli_fetch_assoc($countResult)['total_items'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lost and Found System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Lost and Found System</h1>

    <?php if($isLoggedIn){ ?>
        <p>
            Welcome back, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 
            (Role: <span style="text-transform: capitalize; font-weight: bold; color: var(--primary-orange);"><?php echo htmlspecialchars($_SESSION['role']); ?></span>)
        </p>
    <?php } else { ?>
        <p>Please login or register first to search items and submit claims.</p>
    <?php } ?>

    <div class="menu">
        <?php if(!$isLoggedIn){ ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php } else { ?>
            <a href="items.php">Items</a>
            
            <?php if($isAdmin){ ?>
                <a href="claims.php">All Claims</a>
                <a href="users.php">Users</a>
                <a href="categories.php">Categories</a>
            <?php } else { ?>
                <a href="my_claims.php">My Claims</a>
            <?php } ?>
            
            <a href="profile.php">My Profile</a>
            <a href="logout.php">Logout</a>
        <?php } ?>
    </div>

    <?php if ($isAdmin) { ?>
        <hr style="border: 0; border-top: 1px solid var(--light-border); margin: 30px 0;">
        <h2>Admin Dashboard</h2>
        
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-num"><?php echo $totalUsers; ?></div>
                <div class="card-title">Total Users</div>
            </div>
            <div class="card">
                <div class="card-num"><?php echo $totalItems; ?></div>
                <div class="card-title">Total Items</div>
            </div>
            <div class="card">
                <div class="card-num"><?php echo $totalCategories; ?></div>
                <div class="card-title">Categories</div>
            </div>
            <div class="card">
                <div class="card-num"><?php echo $totalClaims; ?></div>
                <div class="card-title">Total Claims</div>
            </div>
            <div class="card" style="border-top: 4px solid var(--primary-orange);">
                <div class="card-num"><?php echo $totalPending; ?></div>
                <div class="card-title">Pending Claims</div>
            </div>
            <div class="card" style="border-top: 4px solid var(--success-green);">
                <div class="card-num"><?php echo $totalApproved; ?></div>
                <div class="card-title">Approved Claims</div>
            </div>
            <div class="card" style="border-top: 4px solid var(--danger-red);">
                <div class="card-num"><?php echo $totalRejected; ?></div>
                <div class="card-title">Rejected Claims</div>
            </div>
        </div>

        <h2>Admin Activity Logs</h2>
        <table width="100%" border="1" cellspacing="0">
            <tr>
                <th>Timestamp</th>
                <th>Admin User</th>
                <th>Action</th>
                <th>Claim ID</th>
            </tr>
            <?php
            if (mysqli_num_rows($logsResult) > 0) {
                while ($log = mysqli_fetch_assoc($logsResult)) {
                    ?>
                    <tr>
                        <td><?php echo $log['timestamp']; ?></td>
                        <td><strong><?php echo htmlspecialchars($log['admin_name']); ?></strong></td>
                        <td>
                            <?php
                            $action = $log['action'];
                            $class = "";
                            if (strpos($action, 'Approved') !== false) {
                                $class = "status-approved";
                            } else if (strpos($action, 'Rejected') !== false) {
                                $class = "status-rejected";
                            } else {
                                $class = "status-pending";
                            }
                            ?>
                            <span class="<?php echo $class; ?>"><?php echo htmlspecialchars($action); ?></span>
                        </td>
                        <td><?php echo $log['claim_id'] ? $log['claim_id'] : '-'; ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="4" align="center">No admin activities logged yet</td></tr>';
            }
            ?>
        </table>
    <?php } else { ?>
        <p>
            Total Items in the system: <strong><?php echo $totalItems; ?></strong>
        </p>
    <?php } ?>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>