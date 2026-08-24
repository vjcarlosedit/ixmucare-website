<!-- order-manage.php -->
<?php
include 'dbCon.php';
$con = connect();

// Check if `order_id` is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Start a transaction to ensure both the order and order items are deleted together
    $con->begin_transaction();

    try {
        // Delete associated items from `order_items` table first
        $deleteItemsQuery = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $con->prepare($deleteItemsQuery);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // Delete the order from `orders` table
        $deleteOrderQuery = "DELETE FROM orders WHERE id = ?";
        $stmt = $con->prepare($deleteOrderQuery);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // Commit the transaction
        $con->commit();

        // Redirect with a success message
        header("Location: food-orderList.php?message=Order deleted successfully");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $con->rollback();

        // Redirect with an error message
        header("Location: food-orderList.php?error=Failed to delete order");
        exit;
    }
} else {
    // Redirect back if no `order_id` is specified
    header("Location: food-orderList.php?error=No order ID provided");
    exit;
}
?>
