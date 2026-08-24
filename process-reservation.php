<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo '<script>alert("You must be logged in to make a reservation."); window.location.href="login.php";</script>';
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();

// Get reservation details from the form
$table_id = $_POST['table_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$guests = $_POST['guests'];
$date = $_POST['date'];
$time = $_POST['time'];

// Insert the reservation into the database
$sql = "INSERT INTO reservations (table_id, name, email, phone, guests, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("isssiss", $table_id, $name, $email, $phone, $guests, $date, $time);

if ($stmt->execute()) {
    echo '<script>alert("Your table has been reserved successfully!"); window.location.href="index.php";</script>';
} else {
    echo '<script>alert("There was an error processing your reservation. Please try again."); window.location.href="table-reservation.php";</script>';
}

$stmt->close();
$con->close();
?>
