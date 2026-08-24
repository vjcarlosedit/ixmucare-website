<?php 
include 'main/header.php'; 
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

include 'dbCon.php'; 
$con = connect();

// Function to decrypt the password
function decryptPassword($encryptedPassword, $key) {
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

// Fetch the staff details based on empId
$empId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM `staff` WHERE empId = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $empId);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if (!$staff) {
    echo '<script>alert("Staff not found."); window.location="staff-manage.php";</script>';
    exit;
}

// Decrypt the password
$decryptedPassword = decryptPassword($staff['password'], $encryptionKey);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/stylesheets/theme.css">
    <link rel="stylesheet" href="assets/stylesheets/skins/default.css">
</head>
<body>
    <section class="body">
        <?php include 'main/top-bar.php'; ?>
        <div class="inner-wrapper">
            <?php include 'main/left-bar.php'; ?>
            <section role="main" class="content-body">
                <header class="page-header">
                    <h2>Edit Staff</h2>
                </header>

                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                            <a href="#" class="fa fa-times"></a>
                        </div>
                        <h2 class="panel-title">Edit Staff Details</h2>
                    </header>
                    <div class="panel-body">
                        <form action="staff-update.php" method="POST" class="form-horizontal">
                            <input type="hidden" name="empId" value="<?php echo htmlspecialchars($staff['empId']); ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">First Name</label>
                                <div class="col-sm-6">
                                    <input type="text" name="firstName" class="form-control" value="<?php echo htmlspecialchars($staff['firstName']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Last Name</label>
                                <div class="col-sm-6">
                                    <input type="text" name="lastName" class="form-control" value="<?php echo htmlspecialchars($staff['lastName']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" value="<?php echo htmlspecialchars($decryptedPassword); ?>" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mobile No</label>
                                <div class="col-sm-6">
                                    <input type="text" name="mobileNo" class="form-control" value="<?php echo htmlspecialchars($staff['mobileNo']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Job Title</label>
                                <div class="col-sm-6">
                                    <input type="text" name="jobTitle" class="form-control" value="<?php echo htmlspecialchars($staff['jobTitle']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address</label>
                                <div class="col-sm-6">
                                    <input type="text" name="addr" class="form-control" value="<?php echo htmlspecialchars($staff['addr']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Date of Birth</label>
                                <div class="col-sm-6">
                                    <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($staff['dob']); ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-6">
                                    <select name="status" class="form-control">
                                        <option value="0" <?php echo $staff['status'] == 0 ? 'selected' : ''; ?>>Pending</option>
                                        <option value="1" <?php echo $staff['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                        <option value="9" <?php echo $staff['status'] == 9 ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <button type="submit" class="btn btn-primary">Update Staff</button>
                                    <a href="staff-manage.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </section>
        </div>
    </section>
    
    <!-- Vendor -->
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
    <script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    
    <!-- Theme Base, Components and Settings -->
    <script src="assets/javascripts/theme.js"></script>
    
    <!-- Theme Custom -->
    <script src="assets/javascripts/theme.custom.js"></script>
    
    <!-- Theme Initialization Files -->
    <script src="assets/javascripts/theme.init.js"></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>