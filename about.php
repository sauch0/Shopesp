
<?php
include 'dbconnection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>About Us - ShopESP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <a href="index.php"><img src="logo.png" alt="Esports Shop Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Shop</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
        <div class="nav-signin">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

<div class="about-container">
    <h2>About Us</h2>
    <p>Welcome to <strong>ShopESP</strong>, your go-to store for high-quality esports jerseys of your favorite teams!</p>
    
    <h3>Who We Are</h3>
    <p>We are passionate esports fans dedicated to bringing you **authentic and stylish jerseys** from top gaming teams worldwide. Whether you're a casual gamer or a competitive pro, we've got the perfect jersey for you.</p>

    <h3>Why Choose Us?</h3>
    <ul>
        <li>✔️ High-quality, durable esports jerseys</li>
        <li>✔️ Affordable prices with great discounts</li>
        <li>✔️ Fast and reliable shipping</li>
        <li>✔️ Secure payment methods & Cash on Delivery</li>
        <li>✔️ 100% customer satisfaction</li>
    </ul>

    <h3>Contact Us</h3>
    <p>📧 Email: shopesp@gmail.com</p>
    <p>📍 Address: Kumaripati, Lalitpur, Nepal</p>
    <p>📞 Phone: +977 9810234567</p>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
