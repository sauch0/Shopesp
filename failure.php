<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f8d7da; color:#721c24; text-align:center; padding:50px; }
        .box { background:#f5c6cb; padding:20px; border-radius:10px; display:inline-block; }
        a { text-decoration:none; color:white; background:#721c24; padding:10px 20px; border-radius:5px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Payment Failed</h2>
        <p>Unfortunately, your payment could not be processed.</p>
        <p><?= isset($_GET['reason']) ? htmlspecialchars($_GET['reason']) : "Please try again." ?></p>
        <a href="checkout.php">Go Back to Checkout</a>
    </div>
</body>
</html>
