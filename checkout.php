<?php
// Ensure this is receiving data
$data = file_get_contents('php://input');
$cart = json_decode($data, true);  // Convert JSON data to PHP array

// Debug: Print received data
error_log(print_r($cart, true));  // Check the server log for this output

if (!$cart) {
    echo json_encode(['message' => 'No cart data received.']);
    exit();
}

include 'dbCon.php';  // Include your database connection

$con = connect();  // Establish the connection

$customer_name = $cart['customer_name'];
$customer_email = $cart['customer_email'];
$customer_phone = $cart['customer_phone'];
$order_date = date('Y-m-d H:i:s');
$created_at = date('Y-m-d H:i:s');

// Loop through the items in the cart and either insert them or update the food_count if they already exist
foreach ($cart['items'] as $item) {
    $item_name = $item['name'];
    $price = $item['price'];
    $quantity = $item['quantity'];  // This will be the number of items ordered

    // Check if the item already exists in the foodOrder table for this customer
    $stmt = $con->prepare("SELECT id, food_count FROM foodOrder WHERE customer_email = ? AND item_name = ?");
    $stmt->bind_param('ss', $customer_email, $item_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // If the item already exists, update the food_count
        $row = $result->fetch_assoc();
        $new_food_count = $row['food_count'] + $quantity;
        
        $update_stmt = $con->prepare("UPDATE foodOrder SET food_count = ?, order_date = ? WHERE id = ?");
        $update_stmt->bind_param('isi', $new_food_count, $order_date, $row['id']);
        
        if (!$update_stmt->execute()) {
            echo json_encode(['message' => 'Error updating the order. Please try again.']);
            exit();
        }
    } else {
        // If the item does not exist, insert it as a new order
        $food_type = '';  // Set or fetch the food type if necessary
        $insert_stmt = $con->prepare("INSERT INTO food_order (customer_name, customer_email, customer_phone, item_name, food_type, food_count, order_date, created_at)
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param('sssssiis', $customer_name, $customer_email, $customer_phone, $item_name, $food_type, $quantity, $order_date, $created_at);
        
        if (!$insert_stmt->execute()) {
            echo json_encode(['message' => 'Error placing the order. Please try again.']);
            exit();
        }
    }
}

// Return a success message
echo json_encode(['message' => 'Your order has been successfully placed!']);
exit();
?>
