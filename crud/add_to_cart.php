<?php
session_start();

// Check if the cart session variable is set, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve product information from the form submission
    $product_code = $_POST['product_code'];
    $quantity = $_POST['quantity']; // Retrieve the quantity from the form

    // Check if the product is already in the cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as $index => $cartItem) {
        if ($cartItem['product_code'] === $product_code) {
            // Increment the quantity if the product is found in the cart
            $_SESSION['cart'][$index]['quantity'] += $quantity;
            $product_exists = true;
            break;
        }
    }

    // If the product is not in the cart, add it as a new item
    if (!$product_exists) {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];

        // Create an array to represent the product being added to cart
        $product = [
            'product_code' => $product_code,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity // Set the quantity to the value from the form
        ];

        // Add the product to the cart session variable
        $_SESSION['cart'][] = $product;
    }

    // Redirect back to the products page or wherever you want after adding to cart
    header("Location: users_dashboard.php");
    exit;
}
?>
