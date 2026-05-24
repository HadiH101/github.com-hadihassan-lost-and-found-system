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

            <p>
                Welcome back,
            <strong>
            <?php echo $_SESSION['name']; ?>
                </strong>
            </p>

            <?php } else { ?>

            <p>
                Please login or register first.
            </p>

        <?php } ?>


        <?php

        include "db.php";

        $countQuery = "SELECT COUNT(*) AS total_items FROM Item";

        $countResult = mysqli_query($conn, $countQuery);

        $totalItems = mysqli_fetch_assoc($countResult);

        ?>

        <p>

        Total Items:

        <strong>
           <?php echo $totalItems['total_items']; ?>
      </strong>

        </p>

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

<br><br>

<center>
    <p>
        Lost and Found System
    </p>
</center>

</body>
</html>