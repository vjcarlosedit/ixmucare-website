<?php
session_start();
include 'dbCon.php';
$con = connect();

// Check if user is logged in and email session variable is set
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE || !isset($_SESSION['email'])) {
    echo '<script>alert("You must be logged in to view your profile."); window.location.href="login.php";</script>';
    exit();
}

$user_name = $_SESSION['username'];
$user_email = $_SESSION['email'];
$user_phone = $_SESSION['phone'];
$user_gender = $_SESSION['gender'];
$user_address = $_SESSION['address'];

// Prepare query to fetch reservations linked to the logged-in user's email
$sql_reservations = "
    SELECT r.*, t.table_name 
    FROM reservations r 
    JOIN restaurant_tables t ON r.table_id = t.id 
    WHERE r.email = ?
";
$stmt = $con->prepare($sql_reservations);

if ($stmt === false) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("s", $user_email); 
$stmt->execute();
$reservation_result = $stmt->get_result();

include 'main/header.php';
?>

<body>
    <?php include 'main/nav-bar.php'; ?>

    <section class="home-slider owl-carousel" style="height: 400px;">
        <div class="slider-item" style="background-image: url('images/profile1.jpg');" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text align-items-center justify-content-center">
                    <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
                        <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Inicio</a></span> <span>Perfil</span></p>
                        <h1 class="mb-3">Mesas Reservadas</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Mesas</span>
                    <h2>Próximas reservas</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <?php if ($reservation_result->num_rows > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre de Mesa</th>
                                    <th>Invitados</th>
                                    <th>Dia</th>
                                    <th>Hora</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($reservation = $reservation_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reservation['table_name']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['guests']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['date']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['time']); ?></td>
                                        <td>
                                            <?php 
                                            switch ($reservation['status']) {
                                                case 0:
                                                    echo "Pending";
                                                    break;
                                                case 1:
                                                    echo "Confirmed";
                                                    break;
                                                case 9:
                                                    echo "Rejected";
                                                    break;
                                                default:
                                                    echo "Unknown";
                                                    break;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning">No has reservado, Ve hacer una!.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'main/footer.php'; ?>
    <?php include 'main/script.php'; ?>

    <style>
        .profile-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .profile-title {
            margin-bottom: 20px;
            font-size: 24px;
            color: #2c3e50;
        }

        .form-group label {
            font-weight: bold;
            color: #34495e;
        }

        .form-control {
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }

        .form-control[readonly] {
            background-color: #ecf0f1;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</body>
</html>
