<?php
session_start(); // Start the session to access session variables

// Check if the session email is set, redirect to login page if not
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

include '.././dbCon.php'; // Include the database connection file

// Get the email of the logged-in user
$approvedBy = $_SESSION['email'];

$conn = connect();


// Check if an action is performed (approve/reject)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $reservationId = $_GET['id'];

    // Update the reservation status based on the action
    if ($action == 'approve') {
        $updateStatusQuery = "UPDATE reservations SET status = 1, approvedBy = '$approvedBy' WHERE id = $reservationId";
    } elseif ($action == 'reject') {
        $updateStatusQuery = "UPDATE reservations SET status = 9, approvedBy = '$approvedBy' WHERE id = $reservationId";
    }
    $conn->query($updateStatusQuery);
}

// Retrieve reservations with status
$query = "
   SELECT reservations.id, reservations.name, reservations.date, reservations.time, reservations.guests, restaurant_tables.table_name, reservations.status, reservations.approvedBy
    FROM reservations
    JOIN restaurant_tables ON reservations.table_id = restaurant_tables.id

";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Manage Reservation - IXMUCARE</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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
                <a class="nav-link collapsed" href="food-orderList.php">
                    <i class="bi bi-journal-text"></i><span>Food Order</span>
                </a>
            </li>
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Manage Reservations</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Manage Reservations</li>
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
                                        <th scope="col">#</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">No. of Guests</th>
                                        <th scope="col">Table Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Approved By</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['date'] . "</td>";
                                            echo "<td>" . $row['time'] . "</td>";
                                            echo "<td>" . $row['guests'] . "</td>";
                                            echo "<td>" . $row['table_name'] . "</td>";

                                            // Display the status based on the value
                                            $status = $row['status'];
                                            if ($status == 0) {
                                                echo "<td>Pending</td>";
                                            } elseif ($status == 1) {
                                                echo "<td>Approved</td>";
                                            } elseif ($status == 9) {
                                                echo "<td>Rejected</td>";
                                            }

                                            echo "<td>" . $row['approvedBy'] . "</td>";


                                            // Add action buttons for approve/reject
                                            echo "<td>";
                                          
                                                echo "<a href='manage-reservation.php?action=approve&id=" . $row['id'] . "' class='btn btn-success btn-sm'>Approve</a> ";
                                                echo "<a href='manage-reservation.php?action=reject&id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Reject</a>";
                                            
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No reservations found</td></tr>";
                                    }
                                    ?>
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

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
