<?php 
session_start();
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
                <h2>Menu del Restaurante</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="index.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Platillos</span></li>
                        <li><span>Ver</span></li>
                    </ol>

                    <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
                </div>
            </header>

            <!-- start: page -->
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <form action="manage-insert.php" method="POST" enctype="multipart/form-data">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="panel-actions">
                                    <a href="#" class="fa fa-caret-down"></a>
                                    <a href="#" class="fa fa-times"></a>
                                </div>

                                <h2 class="panel-title">Menu</h2>
                                <p class="panel-subtitle">
                                    Para agregar un nuevo<code>platillo al menu</code>, complete todos los campos.
                                </p>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Nombre del Platillo</label>
                                            <input type="text" name="itemname" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Precio</label>
                                            <input type="number" name="price" class="form-control" required min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Cocinero</label>
                                            <textarea class="form-control" name="madeby" rows="1" id="textareaDefault"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Categoria</label>
                                            <select id="areaDropdown" class="form-control populate" name="food_type" required>
                                                <option value=""> -Selecionar- </option>
                                                <option value="Main Cuisine">Platillo</option>
                                                <option value="Dessert">Postre</option>
                                                <option value="Drink">Bebida</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Tipo</label>
                                            <select id="cousinTypeDropdown" class="form-control populate" name="cuisine_type" required>
                                                <option value=""> -Seleciona- </option>
                                                <option value="Srilankan">Platillo 1</option>
                                                <option value="Chinese">Platillo 2</option>
                                                <option value="Italian">Platillo 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
								<div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Imagen</label>
                                            <input type="file" name="image" class="form-control" id="imageInput" required accept="image/*">
                                            <img id="imagePreview" src="" alt="Image Preview" style="display:none; margin-top:10px; max-width:100%; height:auto;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <footer class="panel-footer">
                                <input type="submit" name="addItem" class="btn btn-primary" value="Add Item">
                            </footer>
                        </section>
                    </form>    
                </div>
            </div>
            <!-- end: page -->
        </section>
    </div>

    <?php include 'main/right-bar.php'; ?>
</section>


<!-- Vendor Scripts -->
<script src="assets/vendor/jquery/jquery.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="assets/vendor/select2/select2.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="assets/javascripts/theme.init.js"></script>

<!-- Custom Script to enable/disable Cuisine Type -->
<script>
    document.getElementById('areaDropdown').addEventListener('change', function() {
        var areaValue = this.value;
        var cousinDropdown = document.getElementById('cousinTypeDropdown');

        cousinDropdown.disabled = areaValue !== 'Main Cuisine'; // Enable/disable cuisine type dropdown based on area selection
        if (cousinDropdown.disabled) {
            cousinDropdown.value = ''; // Clear selection when disabled
        }
    });

    // Trigger change event on page load to ensure dropdown is set correctly
    document.getElementById('areaDropdown').dispatchEvent(new Event('change'));

    // Image preview functionality
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block'; // Show the preview
            }
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>