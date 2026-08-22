<?php
include 'db.php';

$sql1 = "ALTER TABLE Claim ADD COLUMN proof_description TEXT NULL AFTER rejection_reason";
$sql2 = "ALTER TABLE Claim ADD COLUMN proof_image VARCHAR(255) NULL AFTER proof_description";

if (mysqli_query($conn, $sql1)) {
    echo "Added proof_description column successfully.\n";
} else {
    echo "Error adding proof_description: " . mysqli_error($conn) . "\n";
}

if (mysqli_query($conn, $sql2)) {
    echo "Added proof_image column successfully.\n";
} else {
    echo "Error adding proof_image: " . mysqli_error($conn) . "\n";
}
?>
