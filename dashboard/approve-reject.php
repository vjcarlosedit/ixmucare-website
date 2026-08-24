<?php
session_start();
include 'dbCon.php';
$con = connect();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    
    // Determine the status based on the action
    if ($action === 'approve') {
        $status = 1; // Set status to 1 for approved
    } elseif ($action === 'reject') {
        $status = 9; // Set status to 9 for rejected
    } else {
        echo '<script>alert("Invalid action."); window.location="booking-list.php";</script>';
        exit();
    }
    
    // Update the reservation status
    $sql = "UPDATE `reservations` SET `status` = ? WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    
    if ($stmt->execute()) {
        echo '<script>alert("Status updated successfully."); window.location="booking-list.php";</script>';
    } else {
        echo '<script>alert("Error updating status."); window.location="booking-list.php";</script>';
    }
    
    $stmt->close();
} else {
    echo '<script>alert("Invalid request."); window.location="booking-list.php";</script>';
}

$con->close();
?>
