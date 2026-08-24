<?php
session_start();
include 'dbCon.php';
$con = connect();

// Receive the JSON data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data) {
    $user_id = $_SESSION['id'] ?? 0; 
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $total_price = $data['total_price'];
    $order_date = date('Y-m-d H:i:s');

    // Start a transaction
    $con->begin_transaction();

    try {
        // Insert into orders table
        $order_sql = "INSERT INTO orders (user_id, username, email, total_price, order_date, status) VALUES (?, ?, ?, ?, ?, 0)";
        $order_stmt = $con->prepare($order_sql);
        $order_stmt->bind_param("issds", $user_id, $username, $email, $total_price, $order_date);
        $order_stmt->execute();
        $order_id = $con->insert_id;

        // Insert into order_items table
        $item_sql = "INSERT INTO order_items (order_id, item_name, price, quantity) VALUES (?, ?, ?, ?)";
        $item_stmt = $con->prepare($item_sql);

        foreach ($data['items'] as $item) {
            $item_stmt->bind_param("isdi", $order_id, $item['name'], $item['price'], $item['quantity']);
            $item_stmt->execute();
        }

        // Commit the transaction
        $con->commit();

        echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $con->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

    $order_stmt->close();
    $item_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No data received']);
}

$con->close();
?>