<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== TRUE) {
    echo '<script>alert("You must be logged in to order food."); window.location.href="login.php";</script>';
    exit();
}

// Database connection
include 'dbCon.php';
$con = connect();

// Handle search
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Fetch all food items from the menu_item table, filtered by search
$sql = "SELECT * FROM `menu_item` WHERE `item_name` LIKE '%$search%' ORDER BY FIELD(food_type, 'Main Cuisine', 'Dessert', 'Drink')";
$result = $con->query($sql);

// Fetch user details from the session
$user_name = $_SESSION['username'];
$user_email = $_SESSION['email'];

?>

<?php include 'main/header.php'; ?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    </style>
</head>

<body>
    <?php include 'main/nav-bar.php'; ?>

    <section class="home-slider owl-carousel" style="height: 400px;">
        <div class="slider-item" style="background-image: url('images/order1A.jpg');" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text align-items-center justify-content-center">
                    <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
                        <p class="breadcrumbs">
                            <span class="mr-2"><a href="index.php">Home</a></span>
                            <span>Food Order</span>
                        </p>
                        <h1 class="mb-3">Orderna Tu Platillo</h1>
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
                    <h2>Explore Our Delicious Menu</h2>
                </div>
            </div>

            <!-- Menu tabs -->
            <ul class="nav nav-tabs justify-content-center" id="menuTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="main-cuisine-tab" data-toggle="tab" href="#main-cuisine" role="tab" aria-controls="main-cuisine" aria-selected="true">Main Cuisine</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="dessert-tab" data-toggle="tab" href="#dessert" role="tab" aria-controls="dessert" aria-selected="false">Dessert</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="drink-tab" data-toggle="tab" href="#drink" role="tab" aria-controls="drink" aria-selected="false">Drink</a>
                </li>
            </ul>

            <br>
            <div class="tab-content" id="menuTabContent">
                <!-- Search bar -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8 text-center">
                        <form method="POST" action="" id="search-form">
                            <div class="input-group">
                                <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>" class="form-control search-input" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn search-btn">Search</button>
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

                <div class="tab-pane fade show active" id="main-cuisine" role="tabpanel" aria-labelledby="main-cuisine-tab">
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

        <div class="container">
            <div class="row">
                <!-- Left panel for cart -->
                <div class="col-md-4">
                    <h3>Your Cart</h3>
                    <div id="cart-items" style="background-color: #f8f9fa; padding: 15px; border-radius: 10px;">
                        <p id="empty-cart-msg">Your cart is empty.</p>
                        <ul id="cart-list" class="list-group"></ul>
                        <div id="cart-total" style="margin-top: 20px;">
                            <h5>Total: $<span id="total-price">0.00</span></h5>
                        </div>
                        <button id="checkout-btn" class="btn btn-success" style="display: none;">Checkout</button>
                    </div>
                </div>

                <!-- Right panel for menu items -->
                <div class="col-md-8">
                    <div class="tab-content" id="menuTabContent">
                        <!-- The rest of your tabs code goes here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'main/instagram.php'; ?>
    <?php include 'main/footer.php'; ?>
    <?php include 'main/script.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Function to render a menu item
        function renderMenuItem(item) {
            return `
                <div class="col-md-4">
                    <div class="menu-item-card">
                        <div class="image-container">
                            <img src="${item.image}" alt="${item.item_name}">
                        </div>
                        <div class="menu-info">
                            <h3>${item.item_name}</h3>
                            <p>${item.madeby}</p>
                            <p class="price">$${item.price}</p>
                            <button class="order-btn" onclick="addToCart('${item.item_name}', '${item.food_type}', ${item.price})">Add to Cart</button>
                        </div>
                    </div>
                </div>`;
        }

        let cart = [];
        let totalPrice = 0;

        function addToCart(itemName, foodType, price) {
            cart.push({ itemName, foodType, price });
            totalPrice += price;
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartList = document.getElementById('cart-list');
            const emptyCartMsg = document.getElementById('empty-cart-msg');
            const totalPriceDisplay = document.getElementById('total-price');
            const checkoutBtn = document.getElementById('checkout-btn');

            cartList.innerHTML = '';
            cart.forEach(item => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = `${item.itemName} (${item.foodType}) - $${item.price.toFixed(2)}`;
                cartList.appendChild(li);
            });

            if (cart.length === 0) {
                emptyCartMsg.style.display = 'block';
                checkoutBtn.style.display = 'none';
            } else {
                emptyCartMsg.style.display = 'none';
                checkoutBtn.style.display = 'block';
            }

            totalPriceDisplay.textContent = totalPrice.toFixed(2);
        }

        // Handle checkout button
        document.getElementById('checkout-btn').addEventListener('click', function() {
            Swal.fire({
                title: 'Confirm Order',
                text: `You are about to order ${cart.length} items totaling $${totalPrice.toFixed(2)}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, place order!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const orderDetails = cart.map(item => `${item.itemName} (${item.foodType})`).join(', ');
                    const orderDateTime = new Date().toLocaleString();
                    const customerName = '<?php echo $user_name; ?>';
                    const customerEmail = '<?php echo $user_email; ?>';

                    // Simulate AJAX request to place order
                    $.ajax({
                        url: 'place_order.php',
                        method: 'POST',
                        data: {
                            customerName,
                            customerEmail,
                            orderDetails,
                            orderDateTime,
                            totalPrice
                        },
                        success: function(response) {
                            Swal.fire('Order placed!', 'Your order has been successfully placed.', 'success');
                            cart = [];
                            totalPrice = 0;
                            updateCartDisplay();
                        },
                        error: function(error) {
                            Swal.fire('Error!', 'There was an error placing your order.', 'error');
                        }
                    });
                }
            });
        });

        // Reset search functionality
        document.getElementById('reset-search').addEventListener('click', function() {
            document.getElementById('search-form').reset();
            location.reload();
        });
    </script>
</body>
