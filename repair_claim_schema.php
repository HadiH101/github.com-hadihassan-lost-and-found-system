<?php
// repair_claim_schema.php
// Run this script once via a browser (e.g., http://localhost/.../repair_claim_schema.php)
// to ensure the Claim table has the required column.
require __DIR__ . '/db.php';

function columnExists($conn, $table, $column) {
    $sql = "SELECT COUNT(*) as cnt FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $table, $column);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cnt);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $cnt > 0;
}

$column = 'proof_description';
$definition = 'TEXT NULL AFTER rejection_reason';

if (!columnExists($conn, 'Claim', $column)) {
    $alter = "ALTER TABLE Claim ADD COLUMN $column $definition";
    if (mysqli_query($conn, $alter)) {
        echo "Added column $column successfully.<br>";
    } else {
        echo "Failed to add column $column: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Column $column already exists.<br>";
}

mysqli_close($conn);
?>
