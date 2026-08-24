<?php
include 'main/header.php';

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
}

$approvedBy = isset($_SESSION['email']) ? $_SESSION['email'] : "Unknown";  // Use 'Unknown' if not set

// Handle approval or rejection
if (isset($_GET['bapprove_id'])) {
    $bookingId = $_GET['bapprove_id'];
    include 'dbCon.php';
    $con = connect();
    
    // Update the reservation status and set approvedBy field
    $sql = "UPDATE reservations SET status = 1, approvedBy = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $approvedBy, $bookingId);
    $stmt->execute();

    // Fetch the table_id from the reservation
    $tableIdQuery = "SELECT table_id FROM reservations WHERE id = ?";
    $stmt = $con->prepare($tableIdQuery);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $tableId = null;

    if ($row = $result->fetch_assoc()) {
        $tableId = $row['table_id']; // Get the table_id
    }

    // Update the restaurant_tables status
    if ($tableId !== null) {
        $updateTableStatusSql = "UPDATE restaurant_tables SET status = 1 WHERE id = ?";
        $updateStmt = $con->prepare($updateTableStatusSql);
        $updateStmt->bind_param("i", $tableId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $stmt->close();
    $con->close();
    
    echo '<script>alert("Reservation approved successfully!"); window.location="booking-list.php";</script>';
}

if (isset($_GET['breject_id'])) {
    $bookingId = $_GET['breject_id'];
    include 'dbCon.php';
    $con = connect();
    
    // Update the reservation status and set approvedBy field
    $sql = "UPDATE reservations SET status = 9, approvedBy = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $approvedBy, $bookingId);
    $stmt->execute();
    $stmt->close();
    $con->close();
    
    echo '<script>alert("Reservation rejected successfully!"); window.location="booking-list.php";</script>';
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
                    <h2>Reservas Mesas</h2>

                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="index.php">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li><span>Mesas</span></li>
                            <li><span>Lista Reservas</span></li>
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

                        <h2 class="panel-title">Todas las Reservas</h2>
                    </header>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped mb-none" id="datatable-tabletools"
                            data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Telefono</th>
                                    <th>Dia</th>
                                    <th>Hora</th>
                                    <th>Invitados</th>
                                    <th class="hidden-phone">Estatus</th>
                                    <th class="hidden-phone">Aprobada Por</th> <!-- New Column -->
                                    <th class="hidden-phone">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                include 'dbCon.php';
                                $con = connect();
                                // Get all reservations from the database
                                $sql = "SELECT * FROM reservations ORDER BY date DESC, time DESC;";
                                $result = $con->query($sql);
                                foreach ($result as $r) {
                                    ?>
                                    <tr class="gradeX">
                                        <td class="center hidden-phone"><?php echo $count; ?></td>
                                        <td class="center hidden-phone"><?php echo $r['id']; ?></td>
                                        <td><?php echo $r['name']; ?></td>
                                        <td><?php echo $r['phone']; ?></td>
                                        <td><?php echo $r['date']; ?></td>
                                        <td><?php echo $r['time']; ?></td>
                                        <td><?php echo $r['guests']; ?></td>
                                        <td class="center hidden-phone">
                                            <?php
                                            $status = $r['status'];
                                            if ($status == 0) {
                                                echo '<div style="background-color: yellow; padding: 5px 10px; border-radius: 5px; display: inline-block;">Pending</div>';  // Yellow for Pending
                                            } elseif ($status == 1) {
                                                echo '<div style="background-color: blue; padding: 5px 10px; border-radius: 5px; color: white; display: inline-block;">Approved</div>';  // Blue for Approved
                                            } elseif ($status == 9) {
                                                echo '<div style="background-color: red; padding: 5px 10px; border-radius: 5px; color: white; display: inline-block;">Rejected</div>';  // Red for Rejected
                                            }
                                            ?>
                                        </td>
                                        <td class="center hidden-phone"><?php echo htmlspecialchars($r['approvedBy']); ?></td> <!-- Display Approved By -->
                                        <td class="center hidden-phone">
                                            <a href="booking-list.php?bapprove_id=<?php echo $r['id']; ?>"
                                                class="btn btn-success"
                                                onclick="return confirm('Are you sure you want to approve this reservation?');">Approve</a>
                                            <a href="booking-list.php?breject_id=<?php echo $r['id']; ?>"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to reject this reservation?');">Reject</a>
                                        </td>
                                    </tr>
                                    <?php $count++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <!-- end: page -->
        </div>

        <?php include 'main/right-bar.php'; ?>
        </div>

    </section>

    <script type="text/javascript">
        function Done() {
            return confirm("Are you sure?");
        }
    </script>

    <?php include 'main/script-res.php'; ?>
</body>

</html>
