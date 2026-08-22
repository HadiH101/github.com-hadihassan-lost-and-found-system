<?php
session_start();
include "db.php";

$msg = "";

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM User WHERE email = ?");

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

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

    <?php if($msg != ""){ ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php } ?>

    <form method="POST">
        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="submit">Login</button>
    </form>

    <br>
    <a href="register.php" class="btn-link">Create Account</a>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>