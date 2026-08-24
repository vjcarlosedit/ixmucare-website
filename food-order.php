<?php
session_start();

// Database connection
include 'dbCon.php';
$con = connect();

if ($_SESSION['role'] == 'admin') {
    echo '<script>window.location="login.php";</script>';
}

// Handle search
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Fetch all food items from the menu_item table, filtered by search
$sql = "SELECT * FROM `menu_item` WHERE `item_name` LIKE '%$search%' ORDER BY FIELD(food_type, 'Main Cuisine', 'Dessert', 'Drink')";
$result = $con->query($sql);

?>

<?php include 'main/header.php'; ?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .menu-item-card {
            background-color: #2c3e50;
            padding: 20px;
            text-align: center;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .menu-item-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 15px;
        }

        .menu-info h3 {
            color: #ecf0f1;
            margin-top: 15px;
        }

        .menu-info p {
            color: #bdc3c7;
            margin: 5px 0;
        }

        .menu-info .price {
            color: #e74c3c;
            font-weight: bold;
            margin-top: 10px;
        }

        .order-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .order-btn:hover {
            background-color: #c0392b;
        }

        .nav-tabs .nav-link {
            transition: all 0.3s ease;
            border: none;
            color: #fff;
            background-color: #2c3e50;
        }

        .nav-tabs .nav-link.active {
            background-color: #e74c3c;
            color: white;
        }

        #cart-container {
            position: fixed;
            top: 100px;
            right: 20px;
            width: 300px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #cart-list {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <?php include 'main/nav-bar.php'; ?>

    <!-- Your existing slider and menu sections here -->
    <section class="home-slider owl-carousel" style="height: 400px;">
        <div class="slider-item" style="background-image: url('images/order1A.jpg');"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text align-items-center justify-content-center">
                    <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
                        <p class="breadcrumbs">
                            <span class="mr-2"><a href="index.php">Home</a></span>
                            <span>Food Order</span>
                        </p>
                        <h1 class="mb-3">Ordena Tu Platillo</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Menu</span>
                    <h2>Explora las Deliciosas Opciones</h2>
                </div>
            </div>

            <!-- Your existing menu content here -->
            <ul class="nav nav-tabs justify-content-center" id="menuTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="main-cuisine-tab" data-toggle="tab" href="#main-cuisine" role="tab"
                        aria-controls="main-cuisine" aria-selected="true">Principal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="dessert-tab" data-toggle="tab" href="#dessert" role="tab"
                        aria-controls="dessert" aria-selected="false">Postre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="drink-tab" data-toggle="tab" href="#drink" role="tab" aria-controls="drink"
                        aria-selected="false">Bebidas</a>
                </li>
            </ul>

            <br>
            <div class="tab-content" id="menuTabContent">
                <!-- Search bar -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8 text-center">
                        <form method="POST" action="" id="search-form">
                            <div class="input-group">
                                <input type="text" name="search" placeholder="Buscalo Por Nombre"
                                    value="<?php echo htmlspecialchars($search); ?>" class="form-control search-input"
                                    required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn search-btn">Buscar</button>
                                    <?php if (!empty($search)): ?>
                                        <button type="button" class="btn reset-btn" id="reset-search" title="Reset Search">
                                            <span>&times;</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="main-cuisine" role="tabpanel"
                    aria-labelledby="main-cuisine-tab">
                    <div class="row">
                        <?php while ($row = $result->fetch_assoc()) {
                            if ($row['food_type'] === 'Main Cuisine') {
                                echo renderMenuItem($row);
                            }
                        } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="dessert" role="tabpanel" aria-labelledby="dessert-tab">
                    <div class="row">
                        <?php mysqli_data_seek($result, 0); // Reset result pointer
                        while ($row = $result->fetch_assoc()) {
                            if ($row['food_type'] === 'Dessert') {
                                echo renderMenuItem($row);
                            }
                        } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="drink" role="tabpanel" aria-labelledby="drink-tab">
                    <div class="row">
                        <?php mysqli_data_seek($result, 0);
                        while ($row = $result->fetch_assoc()) {
                            if ($row['food_type'] === 'Drink') {
                                echo renderMenuItem($row);
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this cart container -->
        <div id="cart-container">
            <h3>Tu Carta</h3>
            <ul id="cart-list" class="list-group"></ul>
            <p id="empty-cart-msg">Aun vacio</p>
            <p>Total: $<span id="total-price">0.00</span></p>
            <button id="checkout-btn" class="btn btn-primary" style="display: none;">Verificar</button>
        </div>
    </section>

    <?php include 'main/instagram.php'; ?>
    <?php include 'main/footer.php'; ?>
    <?php include 'main/script.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        let cart = [];
        let totalPrice = 0;

        function addToCart(itemName, price, quantity) {
            quantity = parseInt(quantity);
            const existingItem = cart.find(item => item.name === itemName);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({ name: itemName, price: parseFloat(price), quantity: quantity });
            }

            totalPrice += parseFloat(price) * quantity;
            updateCartDisplay();

            $.ajax({
                url: 'save_order.php',
                method: 'POST',
                data: {
                    item_name: itemName,
                    price: price,
                    quantity: quantity
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added to Cart',
                            text: quantity + ' x ' + itemName + ' has been added to your cart!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        updateCartDisplay();
                    } 
                }
            });
        }

        function removeFromCart(index) {
            const removedItem = cart.splice(index, 1)[0];
            totalPrice -= removedItem.price * removedItem.quantity;
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartList = document.getElementById('cart-list');
            const totalPriceElement = document.getElementById('total-price');
            const emptyCartMsg = document.getElementById('empty-cart-msg');
            const checkoutBtn = document.getElementById('checkout-btn');

            cartList.innerHTML = '';

            if (cart.length === 0) {
                emptyCartMsg.style.display = 'block';
                checkoutBtn.style.display = 'none';
            } else {
                emptyCartMsg.style.display = 'none';
                checkoutBtn.style.display = 'block';

                cart.forEach((item, index) => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.innerHTML = `
                        ${item.quantity} x ${item.name} - $${(item.price * item.quantity).toFixed(2)}
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">&times;</button>
                    `;
                    cartList.appendChild(listItem);
                });
            }

            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        function checkoutCart() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Your cart is empty',
                    text: 'Please add items to the cart before checking out.',
                });
                return;
            }

            const cartData = {
                items: cart,
                total_price: totalPrice
            };

            $.ajax({
                url: 'save_order.php',
                method: 'POST',
                data: JSON.stringify(cartData),
                contentType: 'application/json',
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order placed successfully!',
                        text: 'Thank you for your order!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        cart = [];
                        totalPrice = 0;
                        updateCartDisplay();
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Order failed',
                        text: 'There was an error processing your order. Please try again.',
                    });
                }
            });
        }

        document.getElementById('checkout-btn').onclick = checkoutCart;

        document.getElementById('reset-search').onclick = function () {
            document.querySelector('input[name="search"]').value = '';
            document.getElementById('search-form').submit();
        };
    </script>
</body>

</html>

<?php
function renderMenuItem($row)
{
    $item_name = $row['item_name'];
    $price = $row['price'];
    $image = $row['image'];
    $madeby = $row['madeby'];
    $food_type = $row['food_type'];
    $cuisine_type = $row['cuisine_type'];

    return '
    <div class="col-md-4 ftco-animate">
        <div class="menu-item-card">
            <div class="image-container">
                <img src="dashboard/item-image/' . $image . '" alt="' . $item_name . '">
            </div>
            <div class="menu-info">
                <h3>' . $item_name . '</h3>
                <p>Made By: ' . $madeby . '</p>
                <p>Cuisine: ' . $cuisine_type . '</p>
                <p class="price">$' . $price . '</p>
                <button class="order-btn" onclick="addToCart(\'' . $item_name . '\', \'' . $price . '\', 1)">Ordenar Ahora</button>
            </div>
        </div>
    </div>';
}
?>