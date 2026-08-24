<?php
session_start(); // Start the session
include 'dbCon.php'; // Include the database connection file

// Fetch the count of pending orders
$order_count_query = "SELECT COUNT(*) as pending_orders FROM orders WHERE status = 0";
$order_count_result = mysqli_query($conn, $order_count_query);
if ($order_count_result) {
  $order_data = mysqli_fetch_assoc($order_count_result);
  $pending_orders = $order_data['pending_orders'];
} else {
  $pending_orders = 0; // Fallback value
}

$table_count_query = "SELECT COUNT(*) as pending_table FROM reservations WHERE status = 0";
$table_count_result = mysqli_query($conn, $table_count_query);
if ($table_count_result) {
  $table_data = mysqli_fetch_assoc($table_count_result);
  $pending_table = $table_data['pending_table'];
} else {
  $pending_table = 0; // Fallback value
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Staff - </title>
  <meta content="" name="description">
  <meta content="" name="keywords">



  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">


  <link href="assets/css/style.css" rel="stylesheet">


</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="../assets/images/newlogo.png" alt="">
        <span class="d-none d-lg-block">IXMUCARE</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span class="badge bg-primary badge-number"></span>
          </a><!-- End Logout Icon -->




      </ul>
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
      </li><!-- End Dashboard Nav -->


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Table Reservation</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="manage-reservation.php ">
              <i class="bi bi-circle"></i><span>Manage reservations</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Food Orders</span><i
            class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="food-orderList.php">
              <i class="bi bi-circle"></i><span>Manage Orders</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->


      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->


    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Users Card -->
        <div class="col-lg-6 col-md-10">
  <a href="manage-reservation.php" style="text-decoration: none;">
    <div class="card info-card users-card">
      <div class="card-body">
        <h5 class="card-title">Pending <span>| Table Reservation</span></h5>

        <div class="d-flex align-items-center">
          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bi bi-people"></i>
          </div>
          <div class="ps-3">
            <h6 style="color: inherit;">
              <?php echo $pending_table; ?>
            </h6>
          </div>
        </div>
      </div>
    </div>
  </a>
</div><!-- End Users Card -->


        <!-- Income Card -->
        <div class="col-lg-6 col-md-10">
        <a href="food-orderList.php" style="text-decoration: none;">
        <div class="card info-card users-card">
            <div class="card-body">
                <h5 class="card-title">Pending <span>| Food Orders</span></h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-border-style"></i>
                    </div>
                    <div class="ps-3">
                        <h6>
                            <?php echo $pending_orders; ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        </a>
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>