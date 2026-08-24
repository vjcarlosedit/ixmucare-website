<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

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

// Function to decrypt the password
function decryptPassword($encryptedPassword, $key)
{
    $c = base64_decode($encryptedPassword);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac)) {
        return $original_plaintext;
    }
    return false;
}

// Encryption key - in a real scenario, this should be stored securely, not in the code
$encryptionKey = "YourSecretKeyHere";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addStaff'])) {
    // Fetch form values
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $jobTitle = $_POST['jobTitle'];
    $mobileNo = $_POST['mobileNo'];
    $addr = $_POST['addr'];
    $nic = $_POST['nic'];
    $dob = $_POST['dob'];
    $password = encryptPassword($_POST['password'], $encryptionKey); // Encrypt the password
    $status = 0;


    // Handle the profile picture upload
    $photoPath = null; // Initialize photo path variable
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Use the absolute path of the staff-image folder
        $targetDirectory = __DIR__ . "/staff-image"; // Change to the appropriate path
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // Ensure the directory exists, if not, create it
        if (!is_dir($targetDirectory)) {
            if (!mkdir($targetDirectory, 0777, true)) { // Try to create the directory
                die('Failed to create folders...');
            }
        }

        // Move the uploaded file to the target directory
        $photoPath = "staff-image/" . uniqid() . '.' . $file_extension; // Create a unique file name
        if (move_uploaded_file($file_tmp, $targetDirectory . '/' . basename($photoPath))) {
            // File uploaded successfully
        } else {
            // File move failed
            echo '<script>alert("Error moving the uploaded file. Please check permissions and path.");</script>';
            exit;
        }
    } else {
        echo '<script>alert("Photo upload error: ' . $_FILES['photo']['error'] . '");</script>';
        exit;
    }


    $sql = "INSERT INTO `staff` (firstName, lastName, email, jobTitle, mobileNo, addr, nic, dob, password, photo, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssssi", $firstName, $lastName, $email, $jobTitle, $mobileNo, $addr, $nic, $dob, $password, $photoPath, $status);

    if ($stmt->execute()) {
        echo '<script>alert("Staff member added successfully!"); window.location="staff-manage.php";</script>';
    } else {
        echo '<script>alert("Error: ' . $stmt->error . '"); window.history.back();</script>';
    }

    $stmt->close();
}

$con->close();
?>