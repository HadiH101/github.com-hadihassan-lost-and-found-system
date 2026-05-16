<?php
session_start();
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

    <?php if(isset($_SESSION['user_id'])){ ?>
        <p>Welcome, <?php echo $_SESSION['name']; ?></p>
        <p>Role: <?php echo $_SESSION['role']; ?></p>
    <?php } else { ?>
        <p>Please login or register to use the system.</p>
    <?php } ?>

    <div class="menu">
        <?php if(!isset($_SESSION['user_id'])){ ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php } ?>

        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="items.php">Items</a>
            <a href="reports.php">Reports</a>
            <a href="claims.php">Claims</a>
            <a href="logout.php">Logout</a>
        <?php } ?>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"){ ?>
            <a href="users.php">Users</a>
            <a href="categories.php">Categories</a>
        <?php } ?>
    </div>
</div>

</body>
</html>