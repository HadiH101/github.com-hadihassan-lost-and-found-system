<?php
include "auth_check.php";
include "db.php";

$userId = $_SESSION['user_id'];

$query = "SELECT name, email, phone, role FROM User WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>My Profile</h1>

    <div class="menu-links">
        <a href="index.php">Home</a>
        <a href="items.php">Items</a>
        <?php if($_SESSION['role'] == "admin"){ ?>
            <a href="claims.php">All Claims</a>
            <a href="users.php">Users</a>
            <a href="categories.php">Categories</a>
        <?php } else { ?>
            <a href="my_claims.php">My Claims</a>
        <?php } ?>
        <a href="logout.php">Logout</a>
    </div>

    <br>

    <div class="profile-info">
        <div class="profile-row">
            <span class="profile-label">Name</span>
            <span class="profile-value"><?php echo htmlspecialchars($user['name']); ?></span>
        </div>
        <div class="profile-row">
            <span class="profile-label">Email</span>
            <span class="profile-value"><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="profile-row">
            <span class="profile-label">Phone</span>
            <span class="profile-value"><?php echo htmlspecialchars($user['phone']); ?></span>
        </div>
        <div class="profile-row">
            <span class="profile-label">Role</span>
            <span class="profile-value" style="text-transform: capitalize; font-weight: bold; color: var(--primary-orange);">
                <?php echo htmlspecialchars($user['role']); ?>
            </span>
        </div>
    </div>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>
