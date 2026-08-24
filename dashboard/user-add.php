<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
}

?>

<!doctype html>
<html class="fixed">
    <?php include 'main/header.php'; ?>
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
                        <h2>Añadir Nuevo Usuario</h2>
                    
                        <div class="right-wrapper pull-right">
                            <ol class="breadcrumbs">
                                <li>
                                    <a href="index.php">
                                        <i class="fa fa-home"></i>
                                    </a>
                                </li>
                                <li><span>Usuario</span></li>
                                <li><span>Añadir Usuario</span></li>
                            </ol>
                    
                            <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
                        </div>
                    </header>

                    <!-- start: page -->
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <section class="panel">
                                <header class="panel-heading">
                                    <div class="panel-actions">
                                        <a href="#" class="fa fa-caret-down"></a>
                                        <a href="#" class="fa fa-times"></a>
                                    </div>

                                    <h2 class="panel-title">Usuario Nuevo</h2>
                                </header>
                                <div class="panel-body">
                                    <form method="POST" action="user-manage.php" class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="username">Nombre</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="username" required placeholder="eg: Sandaru Malith">
                                            </div>
                                        </div>

                                        
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="email">Correo</label>
                                            <div class="col-md-6">
                                                <input type="email" class="form-control" name="email" required placeholder="eg: abc@gmail.com">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="phone">Telefono</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="phone" maxlength="10" required pattern="\d{10}" title="Please enter a 10-digit phone number" placeholder="eg: 0771234567">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="gender">Genero</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="gender" required>
                                                  <option value=""> -Seleciona- </option>
                                                  <option value="Male">Hombre</option>
                                                  <option value="Female">Mujer</option>
                                                  <option value="Other"> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="address">Dirección</label>
                                            <div class="col-md-6">
                                                <textarea class="form-control" name="address" required placeholder="eg: No 35/A, Nugegoda, Colombo"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="password">Contraseña</label>
                                            <div class="col-md-6">
                                                <input type="password" class="form-control" name="password" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-3">
                                                <button type="submit" name="add_user" class="btn btn-primary">Añadir</button>
                                                <a href="user-list.php" class="btn btn-default">Cancelar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                    <!-- end: page -->
                </section>
            </div>

            <?php include 'main/right-bar.php'; ?>
        </section>

        <!-- Vendor -->
        <script src="assets/vendor/jquery/jquery.js"></script>
        <script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
        <script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

        <!-- Theme Base, Components and Settings -->
        <script src="assets/javascripts/theme.js"></script>

        <!-- Theme Custom -->
        <script src="assets/javascripts/theme.custom.js"></script>

        <!-- Theme Initialization Files -->
        <script src="assets/javascripts/theme.init.js"></script>
    </body>
</html>
