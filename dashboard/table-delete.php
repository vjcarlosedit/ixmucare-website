<?php
include 'dbCon.php';
$con = connect(); 

if (isset($_GET['table_id'])) {
    $table_id = $_GET['table_id'];

    $sql = "DELETE FROM `restaurant_tables` WHERE `id` = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $table_id);

    if ($stmt->execute()) {
        // Success message and redirect back to the table list page
        echo '<script>alert("Table deleted successfully."); window.location="table-list.php";</script>';
    } else {

        echo '<script>alert("Failed to delete table. Please try again."); window.location="table-list.php";</script>';
    }

    $stmt->close();
}

$con->close();
?>
