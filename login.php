<?php
session_start();
include "db.php";

$msg = "";

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `User` WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            header("Location: index.php");
            exit();
        } else {
            $msg = "Wrong password";
        }
    } else {
        $msg = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Login</h1>

    <?php if($msg != "") echo "<p style='color:red;'>$msg</p>"; ?>

    <form method="POST">
        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="submit">Login</button>
    </form>

    <br>
    <a href="register.php">Create Account</a>
</div>

</body>
</html>