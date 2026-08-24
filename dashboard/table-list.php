<?php 
include 'main/header.php';
if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
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
                    <h2>Ver Mesas</h2>
                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="index.php">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li><span>Mesas</span></li>
                            <li><span>Ver Todas las Mesas</span></li>
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
                        <h2 class="panel-title">Todas las Mesas</h2>
                    </header>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped mb-none" id="datatable-tabletools"
                            data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                                <tr>
                                    <th>Numero</th>
                                    <th>ID</th>
                                    <th>Sillas Disponibles</th>
                                    <th>Estatus de Reserva de Mesa</th>
                                    <th class="hidden-phone">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                include 'dbCon.php';
                                $con = connect();
                                $sql = "SELECT * FROM `restaurant_tables`;";
                                $result = $con->query($sql);
                                foreach ($result as $r) {
                                    // Determine button background color based on status
                                    $statusColor = '';
                                    $statusText = '';

                                    if ($r['status'] == 0) {
                                        $statusColor = 'background-color: yellow; color: black;'; // Pending
                                        $statusText = 'No Reservada';
                                    } elseif ($r['status'] == 1) {
                                        $statusColor = 'background-color: blue; color: white;'; // Reserved
                                        $statusText = 'Reservada';
                                    }
                                    ?>
                                    <tr class="gradeX">
                                        <td class="center hidden-phone"><?php echo $count; ?></td>
                                        <td><?php echo htmlspecialchars($r['table_name']); ?></td>
                                        <td><?php echo htmlspecialchars($r['chair_count']); ?></td>
                                        <td>
                                            <span class="status-button" 
                                                  style="<?php echo $statusColor; ?> padding: 5px 10px; border-radius: 5px;"
                                                  onclick="changeStatus(<?php echo $r['id']; ?>, <?php echo $r['status']; ?>)">
                                                <?php echo htmlspecialchars($statusText); ?>
                                            </span>
                                        </td>
                                        <td class="center hidden-phone">
                                            <a href="table-edit.php?table_id=<?php echo $r['id']; ?>" class="btn btn-warning">Edit</a>
                                            <a href="table-delete.php?table_id=<?php echo $r['id']; ?>" class="btn btn-danger" onclick="if (!Done()) return false;">Eliminar</a>
                                        </td>
                                    </tr>
                                    <?php $count++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <!-- end: page -->
            </section>
        </div>

        <?php include 'main/right-bar.php'; ?>
    </section>

    <script type="text/javascript">
        function changeStatus(tableId, currentStatus) {
            // Check if the current status is 'Reserved'
            if (currentStatus === 1) {
                if (confirm("Do you want to change the status?")) {
                    // Make an AJAX request to change the status
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "change_tbl_status.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert(xhr.responseText); // Show server response
                            location.reload(); // Refresh the page to see the changes
                        }
                    };
                    xhr.send("table_id=" + tableId + "&new_status=0");
                }
            }
        }

        function Done() {
            return confirm("Are you sure?");
        }
    </script>

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

    <!-- Examples -->
    <script src="assets/javascripts/tables/examples.datatables.default.js"></script>
    <script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
    <script src="assets/javascripts/tables/examples.datatables.tabletools.js"></script>
</body>

</html>
