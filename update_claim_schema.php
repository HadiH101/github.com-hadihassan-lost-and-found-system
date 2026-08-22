<?php
require_once 'db.php';

function columnExists($conn, $table, $column) {
    $query = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = mysqli_query($conn, $query);
    return $result && mysqli_num_rows($result) > 0;
}

$added = false;
if (!columnExists($conn, 'Claim', 'proof_description')) {
    $sql = "ALTER TABLE Claim ADD COLUMN proof_description TEXT NULL AFTER rejection_reason";
    if (mysqli_query($conn, $sql)) {
        echo "Added column proof_description.<br>";
        $added = true;
    } else {
        echo "Error adding proof_description: " . mysqli_error($conn) . "<br>";
    }
}

if (!columnExists($conn, 'Claim', 'proof_image')) {
    $sql = "ALTER TABLE Claim ADD COLUMN proof_image VARCHAR(255) NULL AFTER proof_description";
    if (mysqli_query($conn, $sql)) {
        echo "Added column proof_image.<br>";
        $added = true;
    } else {
        echo "Error adding proof_image: " . mysqli_error($conn) . "<br>";
    }
}

if (!$added) {
    echo "No columns needed to be added. Schema already up to date.";
}
?>
