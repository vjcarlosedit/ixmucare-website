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

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $con->query($sql);

// Handle status change
if (isset($_POST['changeStatus'])) {
    $userId = $_POST['userId'];
    $newStatus = $_POST['newStatus'];
    $updateSql = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $con->prepare($updateSql);
    $stmt->bind_param('ii', $newStatus, $userId);
    if ($stmt->execute()) {
        echo "<script>alert('Status updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating status');</script>";
    }
    $stmt->close();
    // Refresh the page to show updated data
    echo "<script>window.location.href = 'user-list.php';</script>";
    exit;
}
?>
<!doctype html>
<html class="fixed">
    <head>
        <!-- Basic -->
        <meta charset="UTF-8">
        <title>Usuarios</title>
        <meta name="keywords" content="HTML5 Admin Template" />
        <meta name="description" content="Porto Admin - Responsive HTML5 Template">
        <meta name="author" content="okler.net">
        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Web Fonts  -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
        <!-- Vendor CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
        <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
        <!-- Specific Page Vendor CSS -->
        <link rel="stylesheet" href="assets/vendor/select2/select2.css" />
        <link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
        <!-- Theme CSS -->
        <link rel="stylesheet" href="assets/stylesheets/theme.css" />
        <!-- Skin CSS -->
        <link rel="stylesheet" href="assets/stylesheets/skins/default.css" />
        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="assets/stylesheets/theme-custom.css">
        <!-- Head Libs -->
        <script src="assets/vendor/modernizr/modernizr.js"></script>
    </head>
    <body>
        <section class="body">
            <?php include 'main/top-bar.php'; ?>
            <div class="inner-wrapper">
                <?php include 'main/left-bar.php'; ?>
                <section role="main" class="content-body">
                    <header class="page-header">
                        <h2>Usuarios</h2>
                        <div class="right-wrapper pull-right">
                            <ol class="breadcrumbs">
                                <li>
                                    <a href="index.php">
                                        <i class="fa fa-home"></i>
                                    </a>
                                </li>
                                <li><span>Lista de Usuarios</span></li>
                            </ol>
                            <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
                        </div>
                    </header>
                    <!-- start: page -->
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="panel-actions">
                                <a href="#" class="fa fa-caret-down"></a>
                                <a href="#" class="fa fa-times"></a>
                            </div>
                            <h2 class="panel-title">Lista de Usuarios</h2>
                        </header>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped mb-none" id="datatable-default">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
										<th>Telefono</th>
										<th>Correo</th>
                                        <th>Contraseña</th>
                                        <th>Estatus</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        $id = $row['id'];
                                        $username = $row['username'];
                                        $email = $row['email'];
										$phone = $row['phone'];
										$address = $row['address'];
                                        $decryptedPassword = decryptPassword($row['password'], $encryptionKey);
                                        $status = $row['status'];
                                    ?>
                                    <tr class="gradeX">
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $username; ?></td>
                                        <td><?php echo $email; ?></td>
										<td><?php echo $phone; ?></td>
										<td><?php echo $address; ?></td>
                                        <td>
                                            <div class="input-group">
                                                <input type="password" class="form-control password-field" value="<?php echo htmlspecialchars($decryptedPassword); ?>" readonly>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-eye toggle-password" style="cursor: pointer;"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="userId" value="<?php echo $id; ?>">
                                                <select name="newStatus" onchange="this.form.submit()" class="form-control">
                                                    <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Pendiente</option>
                                                    <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Activa</option>
                                                    <option value="9" <?php echo $status == 9 ? 'selected' : ''; ?>>Inactiva</option>
                                                </select>
                                                <input type="hidden" name="changeStatus" value="1">
                                            </form>
                                        </td>
                                        <td class="actions">
                                            <a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
                                            <a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
                                            <a href="user-edit.php?id=<?php echo $id; ?>" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                            <a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <!-- end: page -->
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
        
        <!-- Specific Page Vendor -->
        <script src="assets/vendor/select2/select2.js"></script>
        <script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
        <script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
        
        <!-- Theme Base, Components and Settings -->
        <script src="assets/javascripts/theme.js"></script>
        
        <!-- Theme Custom -->
        <script src="assets/javascripts/theme.custom.js"></script>
        
        <!-- Theme Initialization Files -->
        <script src="assets/javascripts/theme.init.js"></script>

        <!-- Examples -->
        <script src="assets/javascripts/tables/examples.datatables.default.js"></script>
        <script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
        <script src="assets/javascripts/tables/examples.datatables.tabletools.js"></script>

        <script>
        $(document).ready(function() {
            $('.toggle-password').on('click', function() {
                const passwordField = $(this).closest('.input-group').find('.password-field');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
        </script>
    </body>
</html>