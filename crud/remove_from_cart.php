<?php
session_start();

if (isset($_POST['index']) && isset($_SESSION['cart'])) {
    $index = $_POST['index'];
    
    // Remove the item from the cart using the index
    unset($_SESSION['cart'][$index]);

    // Reorganize the array keys after removal
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

header("Location: users_dashboard.php"); // Redirect back to the dashboard
exit;
?>
