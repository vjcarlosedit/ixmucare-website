<?php
session_start();


// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo '<script>alert("You must be logged in to make a reservation."); window.location.href="login.php";</script>';
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();

// Fetch all tables from the restaurant_tables table
$sql = "SELECT * FROM restaurant_tables"; // Fetch all tables
$result = $con->query($sql);

// Fetch user details from the session
$user_name = $_SESSION['username'];
$user_email = $_SESSION['email'];
$user_phone = $_SESSION['phone'];
$user_role = $_SESSION['user_role'];


if ($_SESSION['role'] == 'admin') {
    echo '<script>window.location="login.php";</script>';

}

//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
// Close the database connection
$con->close();
?>

<?php include 'main/header.php'; ?>

<head>
    <!-- Other head elements -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>

<body>
    <?php include 'main/nav-bar.php'; ?>

    <section class="home-slider owl-carousel" style="height: 400px;">
        <div class="slider-item" style="background-image: url('images/table-res.jpg');"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text align-items-center justify-content-center">
                    <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
                        <p class="breadcrumbs">
                            <span class="mr-2"><a href="index.php">Home</a></span>
                            <span>Reservation</span>
                        </p>
                        <h1 class="mb-3">Reserva Tu Mesa</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Reservation</span>
                    <h2>Elige tu mesa</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 dish-menu">
                    <div class="nav nav-pills justify-content-center ftco-animate">
                        <div class="tab-content py-5" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel">
                                <div class="row">
                                    <div class="table-container">
                                        <?php while ($row = $result->fetch_assoc()) {
                                            $table_id = $row['id'];
                                            $table_name = $row['table_name'];
                                            $chair_count = $row['chair_count'];
                                            $table_status = $row['status']; // Assuming 0 for available, 1 for reserved
                                            ?>
                                            <div class="table" data-id="<?php echo $table_id; ?>"
                                                data-chairs="<?php echo $chair_count; ?>" <?php echo ($table_status == 1) ? 'class="disabled-table"' : ''; ?>
                                                onclick="<?php echo ($table_status == 1) ? 'showAlert(); return false;' : 'selectTable(this)'; ?>">
                                                <div class="table-info">
                                                    <strong><?php echo $table_name; ?></strong>
                                                    <p>Seats: <?php echo $chair_count; ?></p>
                                                </div>
                                                <div class="chair-layout">
                                                    <?php
                                                    for ($i = 0; $i < $chair_count; $i++) {
                                                        echo '<div class="chair"></div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form id="reservationForm" action="process-reservation.php" method="POST">
                                <input type="hidden" name="table_id" id="selectedTable" value="">

                                <div class="form-group">
                                    <label for="name">Nombre Completo:</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="<?php echo $user_name; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="email">Correo:</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="<?php echo $user_email; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Telefono:</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        value="<?php echo $user_phone; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="guests">Numero de Personas:</label>
                                    <input type="number" id="guests" name="guests" class="form-control" min="1" max=""
                                        required>
                                    <small class="form-text text-muted" id="chairCountInfo"></small>
                                </div>

                                <div class="form-group">
                                    <label for="date">Fecha:</label>
                                    <input type="date" id="date" name="date" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="time">Hora:</label>
                                    <input type="time" id="time" name="time" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <input type="submit" value="Reserve Now" class="btn btn-primary py-3 px-5"
                                        id="reserveButton" disabled>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'main/instagram.php'; ?>
    <?php include 'main/footer.php'; ?>
    <?php include 'main/script.php'; ?>

    <script>
        function selectTable(tableElement) {
            // Check if the table is disabled
            if (tableElement.classList.contains('disabled-table')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Table Already Reserved',
                    text: 'This table has already been reserved. Please try another table.',
                    confirmButtonText: 'Okay'
                });
                return; // Exit if the table is disabled
            }

            // Remove the selected class from any previously selected table
            const previousSelected = document.querySelector('.table.selected');
            if (previousSelected) {
                previousSelected.classList.remove('selected');
            }

            // Mark the clicked table as selected
            tableElement.classList.add('selected');

            // Get the table ID and chair count from data attributes
            const tableId = tableElement.getAttribute('data-id');
            const chairCount = tableElement.getAttribute('data-chairs');

            // Update the hidden input with the selected table ID
            document.getElementById('selectedTable').value = tableId;

            // Set the max number of guests allowed based on the chair count
            const guestInput = document.getElementById('guests');
            guestInput.max = chairCount;
            document.getElementById('chairCountInfo').textContent = `This table has ${chairCount} available seats.`;

            // Enable the Reserve button
            document.getElementById('reserveButton').disabled = false;
        }

        function showAlert() {
            alert("This table is already reserved. Please select another table.");
        }

        // Prevent form submission if guests exceed chair count
        document.getElementById('reservationForm').addEventListener('submit', function (e) {
            const guests = parseInt(document.getElementById('guests').value);
            const maxGuests = parseInt(document.getElementById('guests').max);

            if (guests > maxGuests) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Number of Guests',
                    text: `The number of guests cannot exceed the available seats (${maxGuests}).`,
                    confirmButtonText: 'Okay'
                });
            }
        });
    </script>

    <style>
        .table-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .table {
            background-color: #2c3e50;
            padding: 20px;
            text-align: center;
            border-radius: 15px;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .table:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .table.selected {
            border: 3px solid #e74c3c;
        }

        .table.disabled-table {
            pointer-events: none;
            /* Prevent click */
            opacity: 0.5;
            /* Make it look disabled */
        }

        .table-info {
            color: white;
            margin-bottom: 10px;
        }

        .chair-layout {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .chair {
            background-color: #ecf0f1;
            width: 25px;
            height: 25px;
            margin: 5px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .table:hover .chair {
            background-color: #bdc3c7;
        }

        /* Disabled Reserve Now button style */
        #reserveButton[disabled] {
            background-color: #7f8c8d;
            cursor: not-allowed;
        }
    </style>
</body>

</html>