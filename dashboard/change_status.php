<?php
include 'dbCon.php';
$con = connect();

if (isset($_POST['user_id']) && isset($_POST['status'])) {
    $userId = $_POST['user_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $stmt = $con->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $userId);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
?>
