<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 100px; }
        .success { background: #27ae60; color: #fff; padding: 20px; border-radius: 10px; display: inline-block; }
        a { display: block; margin-top: 20px; color: #27ae60; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="success">
        <h2>🎉 Payment Successful!</h2>
        <p>Your order has been placed successfully via eSewa.</p>
    </div>
    <a href="orders.php">View My Orders</a>
</body>
</html>
