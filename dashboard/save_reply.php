<?php
session_start();
include 'dbCon.php';
$con = connect();

// Check if the connection was successful
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get the parent message ID and reply message from the POST request
$parent_message_id = $_POST['parent_message_id'];
$reply_message = $_POST['reply'];

// Check if the required POST variables are set
if (!isset($parent_message_id) || !isset($reply_message)) {
    echo '<script>alert("Parent message ID and reply cannot be empty.");</script>';
    echo '<script>window.location="messages.php";</script>';
    exit;
}

// Fetch admin details from session
$admin_id = $_SESSION['id'];  // Admin ID stored in session
$admin_email = $_SESSION['email'];  // Admin email stored in session
$admin_name = 'admin';  // Hardcoded as 'admin' for simplicity

// Step 1: Fetch the original message to get the sender's email
$query = "SELECT user_email FROM messages WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $parent_message_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the original message was found
if ($result->num_rows > 0) {
    $original_message = $result->fetch_assoc();
    $send_to_email = $original_message['user_email']; // Get the sender's email
} else {
    // Handle the case where the original message does not exist
    echo '<script>alert("Original message not found.");</script>';
    echo '<script>window.location="messages.php";</script>';
    exit; // Exit if the original message is not found
}

// Prepare the SQL statement to insert the reply into the messages table
$stmt = $con->prepare("
    INSERT INTO messages (admin_id, user_name, user_email, message, created_at, send_to) 
    VALUES (?, ?, ?, ?, NOW(), ?)
");
$stmt->bind_param("issss", $admin_id, $admin_name, $admin_email, $reply_message, $send_to_email);

// Execute the query
if ($stmt->execute()) {
    echo '<script>alert("Reply submitted successfully.");</script>';
    echo '<script>window.location="message.php";</script>';
} else {
    // Handle query failure (if any)
    echo '<script>alert("Error: Could not submit reply. ' . $stmt->error . '");</script>';
    echo '<script>window.location="message.php";</script>';
}

// Close the prepared statement and the database connection
$stmt->close();
$con->close();
?>
