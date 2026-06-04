<?php
include "admin_check.php";
include "db.php";

$msg = "";

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "INSERT INTO User (name, email, phone, password, role)
            VALUES ('$name', '$email', '$phone', '$password', '$role')";

    if(mysqli_query($conn, $sql)){
        header("Location: users.php");
        exit();
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User - Lost and Found System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-box">
    <h1>Add User</h1>

    <?php if($msg != ""){ ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php } ?>

    <div class="menu-links">
        <a href="users.php">Back to Users</a>
    </div>

    <br>

    <form method="POST">
        <label>Name</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone</label><br>
        <input type="text" name="phone" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role</label><br>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit" name="submit">Add User</button>
    </form>
</div>

<br><br>

<center>
    <p>Lost and Found System</p>
</center>

</body>
</html>