<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT products.id, products.name, products.image, products.price, cart.quantity 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_products = [];
while ($row = $result->fetch_assoc()) {
    $cart_products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
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

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (empty($cart_products)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
            <?php foreach ($cart_products as $product): ?>
                <tr>
                    <td><img src="<?php echo 'uploads/' . basename($product['image']); ?>" width="50"></td>
                    <td><?php echo $product['name']; ?></td>
                    <td>Rs.<?php echo $product['price']; ?></td>
                    <td>
                        <form action="update_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" min="1">
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>
                    <td>Rs.<?php echo $product['price'] * $product['quantity']; ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <center><a href="checkout.php" class="btn">Proceed to Checkout</a></center>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
