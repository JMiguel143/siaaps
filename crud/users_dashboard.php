<?php
session_start();

// Redirect if not logged in
function redirectToLogin() {
    header("Location: users_login_form.php");
    exit;
}

// Logout functionality
function logoutUser() {
    $_SESSION = array();
    session_destroy();
    redirectToLogin();
}

// Check if user is logged in
if (!isset($_SESSION['email_username'])) {
    redirectToLogin();
}

// Logout action
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    logoutUser();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include external CSS -->
    <style>
        /* Basic styling for layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        #products,
        #cart {
            width: 48%; /* Adjust the width as needed */
        }
        section {
            margin-bottom: 30px;
        }
        .products-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .products-section div {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: calc(33.33% - 20px);
        }
        .order-btn {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .order-btn:hover {
            background-color: #45a049;
        }
        .cart-section {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
        }
        .cart-section ul {
            list-style-type: none;
            padding: 0;
        }
        .cart-section li {
            margin-bottom: 10px;
        }
        .checkout-btn {
            margin-top: 10px;
            padding: 8px 20px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background-color: #286090;
        }
        .empty-cart-msg {
            color: #999;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome to the Users Dashboard</h1>
        <nav>
            <form method="get">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </nav>
    </header>

    <main class="container">

        <!-- Products Section -->
        <section id="products">
            <h2>Menu Products</h2>
            <div class="products-section">
                <?php
                // Fetch and display products from the database
                include('connection_db.php');

                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    // Display product details and add-to-cart button
                ?>
                    <div>
                        <h3><?= $row['product_name'] ?></h3>
                        <p>Price: $<?= $row['price'] ?></p>
                        <form method="post" action="add_to_cart.php">
    <input type="hidden" name="product_code" value="<?= $row['product_code'] ?>">
    <input type="hidden" name="product_name" value="<?= $row['product_name'] ?>">
    <input type="hidden" name="price" value="<?= $row['price'] ?>">
    <input type="number" name="quantity" value="1" min="1"> <!-- Quantity input field -->
    <button type="submit" class="order-btn">Add to Cart</button>
</form>
                    </div>
                <?php } ?>
            </div>
        </section>

        <!-- Cart Section -->
        <section id="cart">
            <div class="cart-section">
                <h2>Cart Items</h2>
                <ul>
                    <?php
                    $total = 0;

                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        // Display cart items and remove button
                    ?>
                        <ul>
                            <?php foreach ($_SESSION['cart'] as $index => $cartItem) { ?>
                                <li>
                                    <?= $cartItem['product_name'] ?> - $<?= $cartItem['price'] ?>
                                    (Qty: <?= $cartItem['quantity'] ?>)
                                    <form method="post" action="remove_from_cart.php">
                                        <input type="hidden" name="index" value="<?= $index ?>">
                                        <button type="submit">Remove</button>
                                    </form>
                                </li>
                                <?php
                                $total += $cartItem['price'];
                            } ?>
                            <li><strong>Total: $<?= number_format($total, 2) ?></strong></li>
                        </ul>
                    <?php } else {
                        echo '<li>Your cart is empty</li>';
                    } ?>
                </ul>
                <form method="post" action="checkout.php">
                    <button type="submit" class="order-btn">Checkout</button>
                </form>
            </div>
        </section>

    </main>

    <footer>
        <!-- Footer content -->
    </footer>

</body>
</html>
