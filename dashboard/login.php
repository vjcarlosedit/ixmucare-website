<?php session_start(); ?>
<!doctype html>
<html class="fixed">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">
		<!-- Custom CSS for Animation -->

		<style>
			body {
				background-image: url('assets/images/aa.jpg'); /* Add your background image path here */
				background-size: cover; /* This will cover the entire screen */
				background-repeat: no-repeat;
				background-attachment: fixed; /* This keeps the background fixed on scroll */
				background-position: center center;
			}
			.fade-in { opacity: 0; animation: fadeIn ease 1s; animation-fill-mode: forwards; }
			@keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
		</style>
		<style>
			.fade-in { opacity: 0; animation: fadeIn ease 1s; animation-fill-mode: forwards; }
			@keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
		</style>
		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<section class="body-sign fade-in">
			<div class="center-sign">
				<a href="" class="logo pull-left">
				<h2 class="title text-uppercase text-bold" style="color:white"> IXMUCARE</h2>
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Iniciar sesión</h2>
					</div>
					<div class="panel-body">
						<!-- Nav Tabs -->
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#adminLogin">Admin</a></li>
							<li><a data-toggle="tab" href="#staffLogin"></a></li>
						</ul>

						<!-- Tab Content -->
						<div class="tab-content">
							<!-- Admin Login Form -->
							<div id="adminLogin" class="tab-pane fade in active">
								<form action="" method="POST">
									<div class="form-group mb-lg">
										<label>Correo</label>
										<div class="input-group input-group-icon">
											<input name="email" type="email" class="form-control input-lg" required />
											<span class="input-group-addon">
												<span class="icon icon-lg"><i class="fa fa-user"></i></span>
											</span>
										</div>
									</div>
									<div class="form-group mb-lg">
										<label>Contraseña</label>
										<div class="input-group input-group-icon">
											<input name="password" type="password" class="form-control input-lg" required />
											<span class="input-group-addon">
												<span class="icon icon-lg"><i class="fa fa-lock"></i></span>
											</span>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4 text-right">
											<input type="submit" class="btn btn-primary btn-block" name="login_admin" value="Sign In">
										</div>
									</div>
								</form>
							</div>

							<!-- Staff Login Form -->
							<div id="staffLogin" class="tab-pane fade">
								<form action="" method="POST">
									<div class="form-group mb-lg">
										<label>Email</label>
										<div class="input-group input-group-icon">
											<input name="email" type="email" class="form-control input-lg" required />
											<span class="input-group-addon">
												<span class="icon icon-lg"><i class="fa fa-user"></i></span>
											</span>
										</div>
									</div>
									<div class="form-group mb-lg">
										<label>Password</label>
										<div class="input-group input-group-icon">
											<input name="password" type="password" class="form-control input-lg" required />
											<span class="input-group-addon">
												<span class="icon icon-lg"><i class="fa fa-lock"></i></span>
											</span>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4 text-right">
											<input type="submit" class="btn btn-primary btn-block" name="login_staff" value="Sign In">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Vendor JS -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
	</body>
</html>

<?php
if (isset($_POST['login_admin'])) {
	$email = $_POST['email'];
	$password = md5($_POST['password']); // MD5 hash for admin passwords

	include 'dbCon.php';
	$con = connect();

	// Prepared statement for admin login
	$stmt = $con->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
	$stmt->bind_param("ss", $email, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$_SESSION['isLoggedIn'] = TRUE;
		$admin = $result->fetch_assoc();
		$_SESSION['id'] = $admin['id'];
		$_SESSION['email'] = $admin['email'];
		$_SESSION['user_name'] = $admin['name'];
		$_SESSION['role'] = $admin['role'];
		$_SESSION['user_role'] = 'admin'; 
		echo '<script>window.location="index.php"</script>';
	} else {
		echo '<script>alert("Invalid Email or Password.")</script>';
		echo '<script>window.location="login.php"</script>';
	}

	$stmt->close();
	$con->close();
}


if (isset($_POST['login_staff'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // Don't hash the password here

    include 'dbCon.php';
    $con = connect();

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
    $encryptionKey = "YourSecretKeyHere"; // Use the same key as in user login

    // Prepared statement for staff login
    $stmt = $con->prepare("SELECT * FROM staff WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        // Check if staff account is pending or deactivated
        if ($staff['status'] == 0) {
            echo '<script>alert("Your account is pending approval.")</script>';
        } elseif ($staff['status'] == 9) {
            echo '<script>alert("Your account is deactivated.")</script>';
        } else {
            // Decrypt the stored password and compare
            $decryptedPassword = decryptPassword($staff['password'], $encryptionKey);

            if ($decryptedPassword === false || $password !== $decryptedPassword) {
                echo '<script>alert("Incorrect Password.")</script>';
            } else {
                $_SESSION['isLoggedIn'] = TRUE;
                $_SESSION['id'] = $staff['id'];
                $_SESSION['email'] = $staff['email'];
                $_SESSION['user_name'] = $staff['name'];
                $_SESSION['user_role'] = 'staff';
                echo '<script>window.location="./staff-dashboard/index.php"</script>';
            }
        }
    } else {
        echo '<script>alert("Email not found.")</script>';
    }

    $stmt->close();
    $con->close();
}
?>