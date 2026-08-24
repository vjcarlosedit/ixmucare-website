<?php
session_start(); // Start the session to access session variables

// Check if the session email is set, redirect to login page if not
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Manage Reservation - IXMUCARE</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/newlogo.png" alt="">
                <span class="d-none d-lg-block">IXMUCARE</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <!-- Insert navigation here -->
        </nav><!-- End Icons Navigation -->
    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link " href="index.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="index.php">
                    <i class="bi bi-journal-text"></i>
                    <span>Table Reservation</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Food Order</span>
                </a>
            </li>
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Manage Food Order</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Manage Food Order</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reservation List</h5>

                            <!-- Table with stripped rows -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Order Date</th>
                                        <th>Status</th>
                                        <th class="hidden-phone">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include '.././dbCon.php';
                                    $con = connect();
                                    $query = "SELECT o.id AS order_id, o.username, o.email, oi.item_name, oi.quantity, o.total_price, o.order_date, o.status FROM orders o JOIN order_items oi ON o.id = oi.order_id";
                                    $result = $con->query($query);

                                    foreach ($result as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['order_id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo number_format($row['total_price'], 2); ?></td>
                                            <td><?php echo $row['order_date']; ?></td>
                                            <td>
                                                <?php if ($row['status'] == 0) { ?>
                                                    <div class="dropdown">
                                                        <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Pending
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <li><a class="dropdown-item text-primary" href="#" onclick="updateOrderStatus(<?php echo $row['order_id']; ?>, 1)">Confirm</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="updateOrderStatus(<?php echo $row['order_id']; ?>, 9)">Cancel</a></li>
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
                                                <a href="order-manage.php?order_id=<?php echo $row['order_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>IXMUCARE</span></strong>. All Rights Reserved
        </div>
    </footer><!-- End Footer -->

    <script>
        function updateOrderStatus(orderId, status) {
            if (confirm('Are you sure you want to update the order status?')) {
                $.ajax({
                    url: 'update-order-status.php',
                    type: 'POST',
                    data: { order_id: orderId, status: status },
                    success: function (response) {
                        alert(response);
                        location.reload();
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

</body>

</html>

