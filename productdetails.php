<?php
include 'dbconnection.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<p>Product not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $product['name']; ?> Jersey</title>
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

<div class="product-details">
    <img src="<?php echo 'uploads/' . basename($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

    <div class="product-info">
        <h2><?php echo $product['name']; ?></h2> <hr> <br>
        <p><?php echo $product['description']; ?></p> <br>
        <p class="price">Rs.<?php echo $product['price']; ?></p>
        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1">

            <?php if (isset($_SESSION['user_id'])): ?>
                <button type="submit" class="btn">Add to Cart</button>
            <?php else: ?>
                <a href="login.php" class="btn">Add to Cart</a>
            <?php endif; ?>
        </form>


    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
