<?php
include 'dbCon.php';
$con = connect();

if (isset($_POST['user_id']) && isset($_POST['status'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    // Update user status in the database
    $sql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $status, $user_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
