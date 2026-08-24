<!-- food-orderList.php -->
<?php
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
                    <h2>Ordenes de PLATILLOS</h2>
                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="index.php">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li><span>Mesas</span></li>
                            <li><span>Ver Ordenes</span></li>
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
                        <h2 class="panel-title">Ordenes</h2>
                    </header>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped mb-none" id="datatable-tabletools">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Usuario</th>
                                    <th>Correo</th>
                                    <th>Platillo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Día de la Orden</th>
                                    <th>Estatus</th>
                                    <th class="hidden-phone">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'dbCon.php';
                                $con = connect();

                                // Fetch data from orders and order_items tables
                                $query = "
                                    SELECT 
                                        o.id AS order_id,
                                        o.username,
                                        o.email,
                                        oi.item_name,
                                        oi.quantity,
                                        o.total_price,
                                        o.order_date,
                                        o.status
                                    FROM 
                                        orders o
                                    JOIN 
                                        order_items oi ON o.id = oi.order_id
                                ";
                                $result = $con->query($query);

                                foreach ($result as $row) {
                                    ?>
                                    <tr class="gradeX">
                                        <td class="center"><?php echo $row['order_id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                                        <td><?php echo $row['order_date']; ?></td>
                                        <td>
                                            <?php if ($row['status'] == 0) { ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-warning dropdown-toggle" type="button"
                                                        data-toggle="dropdown">Pendiente
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#" class="text-primary"
                                                                onclick="updateOrderStatus(<?php echo $row['order_id']; ?>, 1)">Completo</a>
                                                        </li>
                                                        <li><a href="#" class="text-danger"
                                                                onclick="updateOrderStatus(<?php echo $row['order_id']; ?>, 9)">Cancelar</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php } else { ?>
                                                <?php
                                                if ($row['status'] == 1) {
                                                    echo '<span class="text-primary">Completed</span>';
                                                } else {
                                                    echo '<span class="text-danger">Cancelled</span>';
                                                }
                                                ?>
                                            <?php } ?>
                                        </td>
                                        <td class="center hidden-phone">
                                            <a href="order-manage.php?order_id=<?php echo $row['order_id']; ?>"
                                                class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this order?');">Eliminar</a>
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

        <?php include 'main/right-bar.php'; ?>
    </section>

    <!-- Vendor -->
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="assets/javascripts/theme.js"></script>

    <!-- AJAX script to update order status -->
    <script>
        function updateOrderStatus(orderId, status) {
            if (confirm('Are you sure you want to update the order status?')) {
                $.ajax({
                    url: 'update-order-status.php',
                    type: 'POST',
                    data: { order_id: orderId, status: status },
                    success: function (response) {
                        alert(response);
                        location.reload(); // Refresh the page to see updated status
                    },
                    error: function () {
                        alert('Failed to update order status. Please try again.');
                    }
                });
            }
        }
    </script>
</body>

</html>