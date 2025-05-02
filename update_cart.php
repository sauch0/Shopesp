<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    die("Please log in to update your cart.");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

if ($quantity > 0) {
    $query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $stmt->execute();
}

header("Location: cart.php");
exit();
?>
