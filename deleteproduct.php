<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'dbconnection.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid product!'); window.location.href='admin.php';</script>";
    exit();
}

$product_id = $_GET['id'];

// Step 1: Delete related orders first
// $delete_orders = "DELETE FROM orders WHERE product_id = ?";
// if ($stmt_orders = mysqli_prepare($conn, $delete_orders)) {
//     mysqli_stmt_bind_param($stmt_orders, "i", $product_id);
//     mysqli_stmt_execute($stmt_orders);
//     mysqli_stmt_close($stmt_orders);
// }

// Step 2: Delete the product after deleting orders
$delete_product = "DELETE FROM products WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $delete_product)) {
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Product deleted successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting product!'); window.location.href='admin.php';</script>";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
