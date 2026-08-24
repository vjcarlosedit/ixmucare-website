<?php
session_start();
include 'dbCon.php';
$con = connect();


// Check if the empId is provided
if (isset($_GET['empId'])) {
    $empId = $_GET['empId'];

    // Prepare the SQL statement for deletion
    $sql = "DELETE FROM `staff` WHERE empId = ?";
    $stmt = $con->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }

    // Bind parameters
    $stmt->bind_param('i', $empId); // 'i' denotes the type integer

    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo '<script>alert("Staff member deleted successfully."); window.location="staff-manage.php";</script>';
        } else {
            echo '<script>alert("No staff member found with that ID."); window.location="staff-manage.php";</script>';
        }
    } else {
        echo '<script>alert("Error deleting staff member: ' . htmlspecialchars($stmt->error) . '"); window.location="staff-manage.php";</script>';
    }

    // Close the statement
    $stmt->close();
} else {
    echo '<script>alert("Invalid request."); window.location="staff-manage.php";</script>';
}

// Close the database connection
$con->close();
?>
