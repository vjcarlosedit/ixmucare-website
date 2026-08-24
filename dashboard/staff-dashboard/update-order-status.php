<?php
session_start();
include '.././dbCon.php';
$con = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status in the database
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully!";
    } else {
        echo "Failed to update order status.";
    }

    $stmt->close();
    $con->close();
}
?>
