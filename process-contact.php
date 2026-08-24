<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo '<script>alert("You must be logged in to contact the admin."); window.location.href="login.php";</script>';
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();

// Prepare and bind
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID, name, and email from session
    $user_id = $_SESSION['id'];
    $user_name = $_SESSION['username']; // User's name from session
    $user_email = $_SESSION['email'];   // User's email from session
    $message = htmlspecialchars(trim($_POST['message'])); // Sanitize message input

    // Prepare the SQL statement
    $stmt = $con->prepare("INSERT INTO messages (user_id, user_name, user_email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $user_name, $user_email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<script>alert("Your message has been sent successfully."); window.location.href="profile.php";</script>';
    } else {
        echo '<script>alert("There was an error sending your message."); window.location.href="contact-admin.php";</script>';
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$con->close();
?>
