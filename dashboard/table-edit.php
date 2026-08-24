<?php
include 'main/header.php';
if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
}

include 'dbCon.php'; // Include the database connection file
$con = connect(); // Establish the connection

// Check if table_id is set and retrieve the current data for that table
if (isset($_GET['table_id'])) {
    $table_id = $_GET['table_id'];

    // Fetch the current table data
    $sql = "SELECT * FROM `restaurant_tables` WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $table_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $table = $result->fetch_assoc(); // Fetch the table data as an associative array
    $stmt->close();
}

// Update the table data when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_name = $_POST['table_name'];
    $chair_count = $_POST['chair_count'];
    $table_id = $_POST['table_id'];

    // Update the table data in the database
    $sql = "UPDATE `restaurant_tables` SET `table_name` = ?, `chair_count` = ? WHERE `id` = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sii", $table_name, $chair_count, $table_id);

    if ($stmt->execute()) {
        // Success message and redirect back to the table list page
        echo '<script>alert("Table updated successfully."); window.location="table-list.php";</script>';
    } else {
        // Error message if update fails
        echo '<script>alert("Failed to update table. Please try again.");</script>';
    }

    $stmt->close();
}

$con->close(); // Close the database connection
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
                    <h2>Editar Mesa</h2>
                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="index.php">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li><span>Mesa</span></li>
                            <li><span>Editar Mesa</span></li>
                        </ol>
                        <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
                    </div>
                </header>

                <!-- start: page -->
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                            <a href="#" class="fa fa-times"></a>
                        </div>
                        <h2 class="panel-title">Edit Table</h2>
                    </header>
                    <div class="panel-body">
                        <form action="table-edit.php" method="post">
                            <input type="hidden" name="table_id" value="<?php echo htmlspecialchars($table['id']); ?>">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="table_name">Table Name</label>
                                    <input type="text" class="form-control" name="table_name"
                                        value="<?php echo htmlspecialchars($table['table_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="chair_count">Chair Count</label>
                                    <input type="number" class="form-control" name="chair_count"
                                        value="<?php echo htmlspecialchars($table['chair_count']); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group"><br>
                                <button type="submit" class="btn btn-primary">Update Table</button>
                                <a href="table-list.php" class="btn" style="background-color: #ff6600; color: white;">Cancel</a>

                            </div>
                            </div>
                        </form>
                    </div>
                </section>
                <!-- end: page -->
            </section>
        </div>

        <?php include 'main/right-bar.php'; ?>
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
    <script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

    <!-- Theme Base, Components and Settings -->
    <script src="assets/javascripts/theme.js"></script>

    <!-- Theme Custom -->
    <script src="assets/javascripts/theme.custom.js"></script>

    <!-- Theme Initialization Files -->
    <script src="assets/javascripts/theme.init.js"></script>
</body>

</html>