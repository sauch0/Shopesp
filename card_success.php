<?php
session_start();
include 'dbconnection.php';
require 'vendor/autoload.php';

// \Stripe\Stripe::setApiKey('sk_test_51S2NPZCmz2TmtiKUqiXWpzJ8tFHyGaRW1iOGPd2Srt6PxbMWl8EiMtunKdxEHfsBr84GQgWqXjzz6bs3HYeKhtjq00Y3Lt7c1b');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get session ID from Stripe redirect
if (!isset($_GET['session_id'])) {
    die("Invalid request. No session ID found.");
}

$session_id = $_GET['session_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session->payment_status === 'paid') {
        // Fetch user details
        $user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
        $user = mysqli_fetch_assoc($user_query);

        $address = isset($_SESSION['stripe_address']) ? $_SESSION['stripe_address'] : 'Not Provided';
        $phone   = isset($_SESSION['stripe_phone']) ? $_SESSION['stripe_phone'] : 'Not Provided';

        // Fetch cart items
        $cart_query = mysqli_query($conn, "
            SELECT cart.product_id, cart.quantity 
            FROM cart 
            INNER JOIN products ON cart.product_id = products.id 
            WHERE cart.user_id = '$user_id'
        ");

        while ($item = mysqli_fetch_assoc($cart_query)) {
            $product_id = $item['product_id'];
            $quantity   = $item['quantity'];

            mysqli_query($conn, "INSERT INTO orders (user_id, product_id, quantity, address, phone, status, payment_method) 
                                 VALUES ('$user_id', '$product_id', '$quantity', '$address', '$phone', 'paid', 'Stripe')");
        }

        // Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

        // Redirect to orders page
        header("Location: orders.php?success=1");
        exit();
    } else {
        header("Location: failure.php?reason=unpaid");
        exit();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
