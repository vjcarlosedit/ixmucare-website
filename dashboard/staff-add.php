<?php 
session_start();
include 'main/header.php'; 

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit;
    
}


?>
<body>
<section class="body">

    <!-- start: header -->
    <?php include 'main/top-bar.php'; ?>
    <!-- end: header -->

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <?php include 'main/left-bar.php'; ?>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Add Staff</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="index.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Staff</span></li>
                        <li><span>Add Staff</span></li>
                    </ol>

                    <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
                </div>
            </header>

            <!-- start: page -->
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <form action="staff-insert.php" method="POST" enctype="multipart/form-data">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="panel-actions">
                                    <a href="#" class="fa fa-caret-down"></a>
                                    <a href="#" class="fa fa-times"></a>
                                </div>

                                <h2 class="panel-title">Staff Information</h2>
                                <p class="panel-subtitle">
                                    To add a <code>Staff Member</code>, please fill out all fields.
                                </p>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">First Name</label>
                                            <input type="text" name="firstName" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Last Name</label>
                                            <input type="text" name="lastName" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Job Title</label>
                                            <select class="form-control" name="jobTitle" required>
                                                <option value="">-Select-</option>
                                                <option value="cashier">Cashier</option>
                                                <option value="chef">Chef</option>
                                                <option value="kitchenHelper">Kitchen Helper</option>
                                                <option value="manager">Manager</option>
                                                <option value="minerStaff">Miner Staff</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <input type="text" name="mobileNo" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Address</label>
                                            <textarea class="form-control" name="addr" rows="1"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">NIC</label>
                                            <input type="text" name="nic" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Date of Birth</label>
                                            <input type="date" name="dob" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Profile Picture</label>
                                            <input type="file" name="photo" class="form-control" id="photoInput" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <footer class="panel-footer">
                                <input type="submit" name="addStaff" class="btn btn-primary" value="Add Staff">
                            </footer>
                        </section>
                    </form>    
                </div>
            </div>
            <!-- end: page -->
        </section>
    </div>

    <?php include 'main/right-bar.php'; ?>
</section>

<!-- Vendor Scripts -->
<script src="assets/vendor/jquery/jquery.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="assets/vendor/select2/select2.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="assets/javascripts/theme.init.js"></script>

</body>
</html>
