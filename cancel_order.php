<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the order is pending before canceling
    $check_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id' AND status = 'pending'");
    
    if (mysqli_num_rows($check_query) > 0) {
        mysqli_query($conn, "UPDATE orders SET status = 'canceled' WHERE id = '$order_id'");
        echo "<script>alert('Order has been canceled.'); window.location.href='orders.php';</script>";
    } else {
        echo "<script>alert('Unable to cancel. Order may have already been processed.'); window.location.href='orders.php';</script>";
    }
}
?>
