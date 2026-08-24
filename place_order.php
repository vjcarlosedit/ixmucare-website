<?php
session_start();

// Database connection
include 'dbCon.php';
$con = connect();

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the AJAX request
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];

    // Prepare and bind
    $stmt = $con->prepare("INSERT INTO food_orders (item_name, price, customer_name, customer_email, customer_phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $item_name, $price, $customer_name, $customer_email, $customer_phone);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Your order for ' . $item_name . ' has been placed.']);
    } else {
        echo json_encode(['message' => 'Failed to place order.']);
    }

    $stmt->close();
}
?>
