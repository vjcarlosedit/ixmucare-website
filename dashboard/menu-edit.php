<?php 
session_start();
include 'main/header.php'; 

if (!isset($_SESSION['isLoggedIn'])) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

include 'dbCon.php';
$con = connect();

// Get the menu ID from the URL
$menu_id = $_GET['menu_id'];

// Fetch the menu item from the database
$sql = "SELECT * FROM `menu_item` WHERE id = '$menu_id';";
$result = $con->query($sql);
$item = $result->fetch_assoc();

$currentImage = $item['image']; // Store the current image name

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $item_name = $_POST['item_name'];
    $food_type = $_POST['food_type'];
    $cuisine_type = $_POST['cuisine_type'];
    $price = $_POST['price'];
    $madeby = $_POST['madeby'];

    // Check if a new file was uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "item-image/";
        $target_file = $target_dir . basename($image);

        // Update the item in the database with new image
        $update_sql = "UPDATE `menu_item` SET item_name='$item_name', food_type='$food_type', cuisine_type='$cuisine_type', price='$price', madeby='$madeby', image='$image' WHERE id='$menu_id';";
        
        if ($con->query($update_sql) === TRUE) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            echo '<script>alert("Menu item updated successfully!"); window.location="menu-list.php";</script>';
        } else {
            echo "Error updating record: " . $con->error;
        }
    } else {
        // Update the item without changing the image
        $update_sql = "UPDATE `menu_item` SET item_name='$item_name', food_type='$food_type', cuisine_type='$cuisine_type', price='$price', madeby='$madeby' WHERE id='$menu_id';";
        
        if ($con->query($update_sql) === TRUE) {
            echo '<script>alert("Menu item updated successfully!"); window.location="menu-list.php";</script>';
        } else {
            echo "Error updating record: " . $con->error;
        }
    }
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
                <h2>Edit Menu Item</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="index.php">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Food Menu</span></li>
                        <li><span>Edit Menu</span></li>
                    </ol>

                    <a class="sidebar-right-toggle" data-open="sidebar-right"></a>
                </div>
            </header>

            <!-- start: page -->
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <section class="panel">
                            <header class="panel-heading">
                                <div class="panel-actions">
                                    <a href="#" class="fa fa-caret-down"></a>
                                    <a href="#" class="fa fa-times"></a>
                                </div>

                                <h2 class="panel-title">Edit Menu Item</h2>
                                <p class="panel-subtitle">
                                    To edit the <code>Menu Item</code>, please fill out all fields.
                                </p>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Item Name</label>
                                            <input type="text" name="item_name" class="form-control" value="<?php echo $item['item_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Price</label>
                                            <input type="number" name="price" class="form-control" value="<?php echo $item['price']; ?>" required min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Food Made By</label>
                                            <textarea class="form-control" name="madeby" rows="1"><?php echo $item['madeby']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Food Type</label>
                                            <select class="form-control populate" name="food_type" id="foodTypeDropdown" required>
                                                <option value=""> -Select- </option>
                                                <option value="Main Cuisine" <?php echo ($item['food_type'] == 'Main Cuisine') ? 'selected' : ''; ?>>Main Cuisine</option>
                                                <option value="Dessert" <?php echo ($item['food_type'] == 'Dessert') ? 'selected' : ''; ?>>Dessert</option>
                                                <option value="Drink" <?php echo ($item['food_type'] == 'Drink') ? 'selected' : ''; ?>>Drink</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Cuisine Type</label>
                                            <select class="form-control populate" name="cuisine_type" id="cuisineTypeDropdown" required>
                                                <option value=""> -Select- </option>
                                                <option value="Srilankan" <?php echo ($item['cuisine_type'] == 'Srilankan') ? 'selected' : ''; ?>>Sri-Lankan</option>
                                                <option value="Chinese" <?php echo ($item['cuisine_type'] == 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
                                                <option value="Italian" <?php echo ($item['cuisine_type'] == 'Italian') ? 'selected' : ''; ?>>Italian</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="control-label">Image</label>
                                            <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">
                                            <img id="imagePreview" src="item-image/<?php echo $currentImage; ?>" alt="Current Image" style="height: 100px; margin-top: 10px;">
                                            <p>Current Image: <strong><?php echo $currentImage; ?></strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <footer class="panel-footer">
                                <input type="submit" name="editItem" class="btn btn-primary" value="Update Item">
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
    document.getElementById('foodTypeDropdown').addEventListener('change', function() {
        var foodTypeValue = this.value;
        var cuisineDropdown = document.getElementById('cuisineTypeDropdown');

        // Enable or disable the Cuisine Type dropdown based on Food Type selection
        cuisineDropdown.disabled = foodTypeValue !== 'Main Cuisine'; 
        if (cuisineDropdown.disabled) {
            cuisineDropdown.value = ''; // Clear selection when disabled
        }
    });

    // Trigger change event on page load to ensure dropdown is set correctly
    document.getElementById('foodTypeDropdown').dispatchEvent(new Event('change'));

    // Image preview functionality
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block'; // Show the preview
                imagePreview.style.height = '100px'; // Set preview height
            }
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>
