<?php
include 'main/header.php'; 

// Redirect if not logged in
if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit();
}



// Include DB connection
include 'dbCon.php';
$con = connect();
$admin_id = $_SESSION['id']; // Using session to retrieve the admin's ID

// Fetch admin details using the ID from the session
$sql = "SELECT * FROM `admin` WHERE id = '$admin_id';";
$result = $con->query($sql);
$admin = mysqli_fetch_assoc($result); // Fetch the row as an associative array

// Handle form submission for updating profile information
if (isset($_POST['save'])) {
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Verify if the current password matches the stored MD5 password
    if (MD5($current_password) == $admin['password']) {
        // Hash the new password using MD5
        $hashed_password = MD5($new_password);

        // Update the admin record in the database
        $sql = "UPDATE `admin` SET `email`='$email', `password`='$hashed_password' WHERE `id`='$admin_id'";
        $cur = $con->query($sql);

        if ($cur) {
            echo '<script>alert("Account has been updated.")</script>';
            echo '<script>window.location="profile.php"</script>';
        }
    } else {
        // SweetAlert error message if the current password is incorrect
        echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Current Password Incorrect",
                  text: "The current password you entered is wrong. Please try again."
                });
              </script>';
    }
}
?>
<!-- SweetAlert Script for Showing Alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body>
    <!-- start: header -->
    <?php include 'main/top-bar.php'; ?>
    <!-- end: header -->

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php include 'main/left-bar.php'; ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Modify Accounts</h2>
                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="index.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Profile</span></li>
                    </ol>
                    <a class="sidebar-right-toggle" data-open="sidebar-right"></i></a>
                </div>
            </header>

            <!-- start: page -->
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <!-- Left Col -->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div id="image-container" class="stretch">
                                        <img title="profile image" data-target="#logomodal" data-toggle="modal"
                                            src="assets/images/admin.gif" />
                                    </div>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item text-muted">Profile</li>
                                    <li class="list-group-item"><?php echo $admin['email']; ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <!-- Profile Form -->
                            <form class="form-horizontal" method="POST" action="profile.php">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label class="col-md-4 control-label">Email Address:</label>
                                            <div class="col-md-8">
                                                <input type="email" name="email" class="form-control" required
                                                    placeholder="Email"
                                                    value="<?php echo $admin['email']; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Current Password Field -->
                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label class="col-md-4 control-label">Current Password:</label>
                                            <div class="col-md-8">
                                                <input type="password" name="current_password" class="form-control"
                                                    required placeholder="Current Password">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Password Field -->
                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label class="col-md-4 control-label">New Password:</label>
                                            <div class="col-md-8">
                                                <input type="password" name="new_password" class="form-control" required
                                                    placeholder="New Password">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-7">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-8">
                                                <input type="submit" value="Save" name="save"
                                                    class="btn btn-primary py-3 px-5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- /col-lg-9 -->
                    </div><!-- /row -->
                </div><!-- /container -->
            </section>
            <!-- end: page -->
        </section>
    </div>

    <?php include 'main/right-bar.php'; ?>
    <?php include 'main/script-res.php'; ?>
</body>
</html>



