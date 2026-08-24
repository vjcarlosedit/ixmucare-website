<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();

// Get message ID from POST request
$message_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Update the message status to 'deleted' (assumed as 9)
$deleteQuery = "UPDATE messages SET user_del_status = 9 WHERE id = ?"; // Make sure to use the right message ID
$stmt = $con->prepare($deleteQuery);
$stmt->bind_param("i", $message_id);
$result = $stmt->execute();

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete message.']);
}
?>
