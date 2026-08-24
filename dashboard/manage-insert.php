<?php
include 'dbCon.php';
$con = connect();

if (isset($_POST['addItem'])) {
    $itemname = $_POST['itemname'];
    $price = $_POST['price'];
    $madeby = $_POST['madeby'];
    $food_type = $_POST['food_type'];
    $cuisine_type = $_POST['cuisine_type'];

    $res_id = $_SESSION['id'];

    // Check if the item already exists
    $checkSQL = "SELECT * FROM `menu_item` WHERE res_id = '$res_id' AND item_name = '$itemname' AND price = '$price';";
    $checkresult = $con->query($checkSQL);
    if ($checkresult->num_rows > 0) {
        echo '<script>alert("Item with this information already exists.")</script>';
        echo '<script>window.location="menu-add.php"</script>';
    } else {
        // Check if the image is uploaded
        if (isset($_FILES['image'])) {
            // Use the absolute path of the item-image folder
            $targetDirectory = __DIR__ . "/item-image";
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);

            // Ensure the directory exists, if not, create it
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true); // Create the directory with proper permissions
            }

            // Validating image extension
            if ($extension == "jpg" || $extension == "png" || $extension == "jpeg") {
                // Move the uploaded file to the target directory
                $targetPath = $targetDirectory . '/' . $file_name;

                if (move_uploaded_file($file_tmp, $targetPath)) {
                    // Insert item data into database
                    $iquery = "INSERT INTO `menu_item`(`res_id`, `item_name`, `madeby`, `food_type`, `cuisine_type`, `price`, `image`) 
                               VALUES ('$res_id','$itemname','$madeby','$food_type','$cuisine_type','$price','$file_name');";
                    if ($con->query($iquery) === TRUE) {
                        echo '<script>alert("Item added successfully")</script>';
                        echo '<script>window.location="menu-add.php"</script>';
                    } else {
                        echo "Error: " . $iquery . "<br>" . $con->error;
                    }
                } else {
                    // Error moving file
                    echo '<script>alert("Error moving the uploaded file.")</script>';
                }
            } else {
                // Invalid file type
                echo '<script>alert("Required JPG, PNG, or JPEG in the image field.")</script>';
                echo '<script>window.location="menu-add.php"</script>';
            }
        }
    }
}

// Add a Table to restaurant_tables
if (isset($_POST['addtable'])) {
    $table_name = $_POST['tablename'];
    $chair_count = $_POST['chaircount'];
    $status = 0;

    $stmt = $con->prepare("INSERT INTO restaurant_tables (table_name, chair_count, status) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }

    $stmt->bind_param("sii", $table_name, $chair_count, $status);

    if ($stmt->execute()) {
        echo '<script>alert("Table added successfully!")</script>';
        echo '<script>window.location="table-add.php";</script>';
    } else {
        echo '<script>alert("Error adding table: ' . htmlspecialchars($stmt->error) . '")</script>';
    }

    $stmt->close();
}

$con->close();

?>
