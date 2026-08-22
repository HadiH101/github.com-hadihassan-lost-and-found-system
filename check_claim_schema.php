<?php
// check_claim_schema.php
require __DIR__ . '/db.php';

function columnExists($conn, $table, $column) {
    $q = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $r = mysqli_query($conn, $q);
    return $r && mysqli_num_rows($r) > 0;
}

if (columnExists($conn, 'Claim', 'proof_description')) {
    echo "Column proof_description exists.<br>";
} else {
    echo "Column proof_description NOT found.<br>";
}

if (columnExists($conn, 'Claim', 'proof_image')) {
    echo "Column proof_image exists.<br>";
} else {
    echo "Column proof_image NOT found.<br>";
}
?>
