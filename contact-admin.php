<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo '<script>alert("You must be logged in to contact the admin."); window.location.href="login.php";</script>';
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();
?>

<?php include 'main/header.php'; ?>

<body>
    <?php include 'main/nav-bar.php'; ?>

    <section class="home-slider owl-carousel" style="height: 400px;">
        <div class="slider-item" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text align-items-center justify-content-center">
                    <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
                        <h1 class="mb-3">Contact Admin</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <h2>Contact Admin</h2>
                    <p>If you need to edit your information, please fill out the form below:</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="process-contact.php" method="POST">
                        <div class="form-group">
                            <label for="message">Your Message:</label>
                            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include 'main/footer.php'; ?>
    <?php include 'main/script.php'; ?>
</body>
</html>
