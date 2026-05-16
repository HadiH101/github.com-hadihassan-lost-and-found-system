<?php
include "db.php";

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO User (name, email, phone)
            VALUES ('$name', '$email', '$phone')";

    if(mysqli_query($conn, $sql)){
        header("Location: users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
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

    <form method="POST">
        <label>Name</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone</label><br>
        <input type="text" name="phone" required><br><br>

        <button type="submit" name="submit">Add User</button>
    </form>

    <br>
    <a href="users.php">View Users</a>
</div>

</body>
</html>