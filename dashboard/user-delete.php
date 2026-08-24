<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Database connection
    include 'dbCon.php';
    $con = connect();

    // Prepare SQL statement to delete the user
    $sql = "DELETE FROM `users` WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo '<script>alert("User deleted successfully!");</script>';
    } else {
        echo '<script>alert("Error deleting user!");</script>';
    }

    // Redirect back
    echo '<script>window.location="user-list.php"</script>';
} else {
    // Redirect if ID is not provided
    echo '<script>window.location="user-edit.php"</script>';
}
?>
