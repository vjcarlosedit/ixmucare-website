<?php
session_start();
include 'dbCon.php';
if (!isset($_SESSION['isLoggedIn'])) {
    echo 'You must be logged in to change the status.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableId = $_POST['table_id'];
    $newStatus = $_POST['new_status'];

    // Connect to the database
    $con = connect();
    
    // Update the status in the database
    $stmt = $con->prepare("UPDATE restaurant_tables SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $tableId);
    
    if ($stmt->execute()) {
        echo 'Status updated successfully.';
    } else {
        echo 'Error updating status: ' . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
