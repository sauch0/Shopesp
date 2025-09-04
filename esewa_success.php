<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$address = $_SESSION['esewa_address'] ?? 'Unknown';
$phone   = $_SESSION['esewa_phone'] ?? '0000000000';

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
                         VALUES ('$user_id', '$product_id', '$quantity', '$address', '$phone', 'paid', 'eSewa')");
}

// clear cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

// clear temp session data
unset($_SESSION['esewa_address'], $_SESSION['esewa_phone']);

// redirect
echo "<script>alert('Payment Successful! Order placed.'); window.location.href='orders.php';</script>";
exit();
?>
