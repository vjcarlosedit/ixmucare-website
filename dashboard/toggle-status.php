<?php
session_start();
include 'dbCon.php';
$con = connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empId = $_POST['empId'];
    $newStatus = $_POST['status'];

    // Update the staff status
    $sql = "UPDATE `staff` SET status = ? WHERE empId = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $newStatus, $empId);

    if ($stmt->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $con->error;
    }

    $stmt->close();
}

$con->close();
?>
