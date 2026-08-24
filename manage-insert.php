<?php
session_start();
include_once 'dbCon.php';
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
$encryptionKey = "YourSecretKeyHere";

if (isset($_POST['regUser'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$gender = $_POST['gender'];
	$password = encryptPassword($_POST['password'], $encryptionKey); // Encrypt the password
	$status = 0;  // Default status for newly registered users as "not activated by admin"
	$role = 'user'; // Default role for newly registered users

	// Check if email already exists in the users table
	$checkSQL = "SELECT * FROM `users` WHERE email = '$email';";
	$checkresult = $con->query($checkSQL);
	if ($checkresult->num_rows > 0) {
		echo '<script>alert("User with this email already exists.")</script>';
		echo '<script>window.location="register.php"</script>';
	}else {

			$iquery = "INSERT INTO `users`(`username`, `email`, `phone`, `address`, `gender`, `password`, `status`, `role`) 
                    VALUES ('$username','$email','$phone','$address','$gender','$password','$status', '$role');";
			if ($con->query($iquery) === TRUE) {
				echo '<script>alert("You registered successfully. Await admin approval.")</script>';
				echo '<script>window.location="login.php"</script>';
			} else {
				echo "Error: " . $iquery . "<br>" . $con->error;
			}
		}
	}



if (isset($_POST['book'])) {

	$bdinsert = false;
	$u_id = $_SESSION['id'];
	$res_id = $_POST['res_id'];
	$reservation_name = $_POST['reservation_name'];
	$reservation_phone = $_POST['reservation_phone'];
	$reservation_date = $_POST['reservation_date'];
	$reservation_time = $_POST['reservation_time'];
	$total_price = $_POST['total_price'];
	$transactionid = $_POST['transaction_id'];

	date_default_timezone_set("Asia/Colombo");
	$make_time = date("h:i:sa");
	$make_date = date("Y-m-d");
	$booking_id = uniqid();

	$iquery = "INSERT INTO `booking_details`(`booking_id`,`res_id`,`c_id`,`make_date`, `make_time`, `name`, `phone`, `booking_date`, `booking_time`, `bill`,`transactionid`) 
		    VALUES ('$booking_id','$res_id','$u_id','$make_date','$make_time','$reservation_name','$reservation_phone','$reservation_date','$reservation_time','$total_price','$transactionid');";
	if ($con->query($iquery) === TRUE) {
		$bdinsert = true;
	} else {
		echo "Error: " . $iquery . "<br>" . $con->error;
	}

	$cinsert = false;
	if ($bdinsert == true) {
		for ($q = 0; $q < count($_POST["chair"]); $q++) {
			$c_id = $_POST['chair'][$q];
			$chair_no = "";
			$sql5 = "SELECT * FROM `restaurant_chair` WHERE id = '$c_id';";
			$result5 = $con->query($sql5);
			foreach ($result5 as $r5) {
				$chair_no = $r5['chair_no'];
			}
			$ciQuery = "INSERT INTO `booking_chair`(`booking_id`, `chair_id`, `chair_no`) 
                		VALUES ('$booking_id','$c_id','$chair_no');";
			if ($con->query($ciQuery) === TRUE) {
				$cinsert = true;
			} else {
				echo "Error: " . $ciQuery . "<br>" . $con->error;
			}
		}
	}

	$iinsert = false;
	if ($cinsert == true) {
		for ($i = 0; $i < count($_POST["item"]); $i++) {
			$i_id = $_POST['item'][$i];
			$qty = $_POST['qty'][$i];
			$itmQuery = "INSERT INTO `booking_menus`(`booking_id`, `item_id`, `qty`) 
                		VALUES ('$booking_id','$i_id','$qty');";
			if ($con->query($itmQuery) === TRUE) {
				$iinsert = true;
			} else {
				echo "Error: " . $itmQuery . "<br>" . $con->error;
			}
		}
	}

	if ($bdinsert == true && $cinsert == true && $iinsert == true) {
		echo '<script>alert("Your booking is done. You will get an email soon.")</script>';
		echo '<script>window.location="index.php"</script>';
	} else {
		echo "Error: " . $iquery . "<br>" . $con->error;
		echo "Error: " . $ciQuery . "<br>" . $con->error;
		echo "Error: " . $iquery . "<br>" . $con->error;
	}
}
?>