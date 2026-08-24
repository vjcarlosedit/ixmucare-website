<?php
session_start(); 

include 'main/header.php';
if (!isset($_SESSION['isLoggedIn'])) {
	echo '<script>window.location="login.php"</script>';
}

include 'dbCon.php';
$con = connect();

// Fetch counts from the respective tables
$user_count_query = "SELECT COUNT(*) AS user_count FROM users";
$pending_user_count_query = "SELECT COUNT(*) AS pending_user_count FROM users WHERE status = 0";
$table_count_query = "SELECT COUNT(*) AS table_count FROM restaurant_tables";
$menu_count_query = "SELECT COUNT(*) AS menu_count FROM menu_item";
$staff_count_query = "SELECT COUNT(*) AS staff_count FROM staff";
$pending_staff_count_query = "SELECT COUNT(*) AS pending_staff_count FROM staff WHERE status = 0";
// Execute queries
$user_count_result = $con->query($user_count_query);
$pending_user_count_result = $con->query($pending_user_count_query);
$table_count_result = $con->query($table_count_query);
$menu_count_result = $con->query($menu_count_query);
$staff_count_result = $con->query($staff_count_query);
$pending_staff_count_result = $con->query($pending_staff_count_query);

// Fetch the counts
$user_count = $user_count_result->fetch_assoc()['user_count'];
$pending_user_count = $pending_user_count_result->fetch_assoc()['pending_user_count'];
$table_count = $table_count_result->fetch_assoc()['table_count'];
$menu_count = $menu_count_result->fetch_assoc()['menu_count'];
$staff_count = $staff_count_result->fetch_assoc()['staff_count'];
$pending_staff_count = $pending_staff_count_result->fetch_assoc()['pending_staff_count'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IXMUCARE</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f8f9fa;
		}

		.info-box {
			background: white;
			border-radius: 20px;
			padding: 80px;
			text-align: center;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
			transition: transform 0.3s, box-shadow 0.3s;
			margin-bottom: 20px;
			cursor: pointer;
			position: relative;
			overflow: hidden;
		}

		.info-box .count {
			font-size: 36px;
			font-weight: bold;
			color: #fff;
		}

		.info-box .title {
			font-size: 18px;
			color: #fff;
			margin-top: 10px;
		}

		.info-box:hover {
			transform: translateY(-10px);
			box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
		}

		.info-box:hover i {
			transform: rotate(360deg);
			transition: all 0.5s ease-in-out;
		}

		.info-box i {
			font-size: 48px;
			color: #fff;
			padding: 20px;
			border-radius: 50%;
			position: absolute;
			top: -20px;
			right: -20px;
			transition: all 0.5s ease-in-out;
		}

		.blue-bg {
			background-color: #007bff;
		}

		.green-bg {
			background-color: darkblue;
		}

		.red-bg {
			background-color: #dc3545;
		}

		.purple-bg {
			background-color: orange;
		}

		.info-box i.blue-bg {
			background-color: #0056b3;
		}

		/* card right icon color */
		.info-box i.green-bg {
			background-color: black;
		}

		.info-box i.red-bg {
			background-color: #bd2130;
		}

		.info-box i.purple-bg {
			background-color: orangered;
		}

		/* Badge/notification styles for pending users */
		.pending-badge {
			background-color: red;
			color: white;
			font-size: 14px;
			font-weight: bold;
			padding: 2px 12px;
			border-radius: 2%;
			position: absolute;
			top: 2px;
			left: -0.1px;
			/* Position it to the left of the card */
			z-index: 1;
			animation: pulse 1.5s infinite;
			box-shadow: 0 0 70px rgba(255, 0, 0, 0.5);
		}

		.pending-badge-staff {
			background-color: black;
			color: white;
			font-size: 14px;
			font-weight: bold;
			padding: 2px 12px;
			border-radius: 2%;
			position: absolute;
			top: 2px;
			left: -0.1px;
			/* Position it to the left of the card */
			z-index: 1;
			animation: pulse 1.5s infinite;
			box-shadow: 0 0 70px rgba(255, 0, 0, 0.5);
		}

		/* Responsive Design */
		@media (max-width: 768px) {
			.col-lg-3 {
				margin-bottom: 15px;
			}
		}
	</style>
</head>

<body>
	<section class="body">

		<!-- Start: header -->
		<?php include 'main/top-bar.php'; ?>
		<!-- End: header -->

		<div class="inner-wrapper">
			<!-- Start: sidebar -->
			<?php include 'main/left-bar.php'; ?>
			<!-- End: sidebar -->

			<section role="main" class="content-body">
				<header class="page-header">
					<h2>IXMUCARE</h2>
				</header>

				<!-- Start: dashboard overview with animated cards -->
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title">Bienvenido Administrador</h2>
					</header>
					<div class="panel-body">
						<div class="row">
							<!-- Users Card -->
							<div class="col-lg-3 col-sm-6 col-xs-12">
								<a href="user-list.php">
									<div class="info-box blue-bg animate__animated animate__fadeInLeft">
										<!-- Show pending user notification -->
										<?php if ($pending_user_count > 0) { ?>
											<span class="pending-badge"><?php echo $pending_user_count; ?> Pendiente</span>
										<?php } ?>
										<i class="fas fa-users blue-bg"></i>
										<div class="count"><?php echo $user_count; ?></div>
										<div class="title">Usuarios</div>
									</div>
								</a>
							</div>

							<!-- Tables Card -->
							<div class="col-lg-3 col-sm-6 col-xs-12">
								<div class="info-box purple-bg animate__animated animate__fadeInDown">
									<i class="fas fa-utensils purple-bg"></i>
									<div class="count"><?php echo $table_count; ?></div>
									<div class="title">Mesas</div>
								</div>
							</div>

							<!-- Menus Card -->
							<div class="col-lg-3 col-sm-6 col-xs-12">
								<div class="info-box green-bg animate__animated animate__fadeInRight">
									<i class="fas fa-book green-bg"></i>
									<div class="count"><?php echo $menu_count; ?></div>
									<div class="title">Menu</div>
								</div>
							</div>

							<!-- Staff Card -->
							<div class="col-lg-3 col-sm-6 col-xs-12">
								<a href="staff-manage.php">
									<div class="info-box red-bg animate__animated animate__fadeInUp">
										<!-- Show pending staff notification -->
										<?php if ($pending_user_count > 0) { ?>
											<span class="pending-badge-staff"><?php echo $pending_staff_count; ?>
												Pending</span>
										<?php } ?>
										<i class="fas fa-user-tie red-bg"></i>
										<div class="count"><?php echo $staff_count; ?></div>
										<div class="title">Staff</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</section>
			</section>
		</div>

		<?php include 'main/right-bar.php'; ?>
	</section>

	<?php include 'main/script-res.php'; ?>
</body>

</html>