<?php
include 'dbCon.php';
$con = connect();

if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = intval($_POST['status']);

    // Update the order status in the database
    $stmt = $con->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $order_id);

    if ($stmt->execute()) {
        echo 'Order status updated successfully.';
    } else {
        echo 'Failed to update order status.';
    }

    $stmt->close();
}
$con->close();
?>
