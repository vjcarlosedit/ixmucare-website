<?php
session_start();
include 'dbCon.php';
$con = connect();

// Function to encrypt the password
function encryptPassword($password, $key)
{
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($password, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    return base64_encode($iv . $hmac . $ciphertext_raw);
}

// Encryption key - in a real scenario, this should be stored securely, not in the code
$encryptionKey = "YourSecretKeyHere";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $empId = $_POST['empId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = encryptPassword($_POST['password'], $encryptionKey); // Encrypt the password
    $mobileNo = $_POST['mobileNo'];
    $jobTitle = $_POST['jobTitle'];
    $addr = $_POST['addr'];
    $dob = $_POST['dob'];
    $status = $_POST['status'];

    // Prepare an SQL statement for updating the staff details
    $sql = "UPDATE `staff` 
            SET firstName = ?, lastName = ?, email = ?, password = ?, mobileNo = ?, jobTitle = ?, addr = ?, dob = ?, status = ? 
            WHERE empId = ?";
    $stmt = $con->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }

    // Bind parameters
    $stmt->bind_param('sssssssssi', $firstName, $lastName, $email, $password, $mobileNo, $jobTitle, $addr, $dob, $status, $empId);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            echo '<script>alert("Staff details updated successfully."); window.location="staff-manage.php";</script>';
        } else {
            echo '<script>alert("No changes made."); window.location="staff-manage.php";</script>';
        }
    } else {
        echo '<script>alert("Error updating staff details: ' . htmlspecialchars($stmt->error) . '"); window.location="staff-manage.php";</script>';
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$con->close();
?>