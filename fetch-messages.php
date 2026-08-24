<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();

// Get user ID from session
$user_id = $_SESSION['id'];
$email = $_SESSION['email'];

// Fetch sent messages
$sentMessagesQuery = "SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC";
$sentStmt = $con->prepare($sentMessagesQuery);
$sentStmt->bind_param("i", $user_id);
$sentStmt->execute();
$sentMessages = $sentStmt->get_result();

// Fetch received messages
$receivedMessagesQuery = "SELECT * FROM messages WHERE email <> ? ORDER BY created_at DESC";
$receivedStmt = $con->prepare($receivedMessagesQuery);
$receivedStmt->bind_param("i", $email);
$receivedStmt->execute();
$receivedMessages = $receivedStmt->get_result();

// Prepare messages for JSON response
$messages = [
    'sent' => [],
    'received' => []
];

while ($message = $sentMessages->fetch_assoc()) {
    $messages['sent'][] = $message;
}

while ($message = $receivedMessages->fetch_assoc()) {
    $messages['received'][] = $message;
}

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);
?>
