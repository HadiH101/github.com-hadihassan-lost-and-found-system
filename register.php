<?php
include "db.php";

$msg = "";

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO `User` (name, email, phone, password, role)
            VALUES ('$name', '$email', '$phone', '$password', 'user')";

    if(mysqli_query($conn, $sql)){
        header("Location: login.php");
        exit();
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Register</h1>

    <?php if($msg != "") echo "<p style='color:red;'>$msg</p>"; ?>

    <form method="POST">
        <label>Name</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone</label><br>
        <input type="text" name="phone" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="submit">Register</button>
    </form>

    <br>
    <a href="login.php">Login</a>
</div>

<br><br>

<center>
    <p>
        Lost and Found System
    </p>
</center>

</body>
</html>