<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

include 'dbCon.php';
$con = connect();

function encryptPassword($password, $key)
  {
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($password, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    return base64_encode($iv . $hmac . $ciphertext_raw);
  }

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
  $encryptionKey = "YourSecretKeyHere";
  

if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = encryptPassword($_POST['password'], $encryptionKey); // Encrypt the password
    $status = 0; // Set status to 0 for newly added users
    $role = 'user'; // Default role for newly added users

    // Insert new user data into the database
    $sql = "INSERT INTO `users` (username, email, phone, gender, address, password, status, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssssssis', $username, $email, $phone, $gender, $address, $password, $status, $role);

    if ($stmt->execute()) {
        echo '<script>alert("User added successfully!");</script>';
        echo '<script>window.location="user-list.php"</script>';
    } else {
        echo '<script>alert("Error adding user!");</script>';
    }
}
?>
