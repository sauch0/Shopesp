<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    die("Please log in to modify your cart.");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

$query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();

header("Location: cart.php");
exit();
?>
