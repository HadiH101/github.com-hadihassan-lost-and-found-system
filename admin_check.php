<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    echo "Access denied. Admin only.";
    exit();
}
?>