<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = 0;

// Fetch user details
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Fetch cart items
$cart_query = mysqli_query($conn, "SELECT cart.product_id, cart.quantity, products.name, products.price, products.image 
                                   FROM cart 
                                   INNER JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = '$user_id'");

$cart_items = [];
while ($row = mysqli_fetch_assoc($cart_query)) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

if (empty($cart_items)) {
    echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
    exit();
}

// Handle COD order placement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method']) && $_POST['payment_method'] === "COD") {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];

        mysqli_query($conn, "INSERT INTO orders (user_id, product_id, quantity, address, phone, status, payment_method) 
                             VALUES ('$user_id', '$product_id', '$quantity', '$address', '$phone', 'pending', 'COD')");
    }

    // Clear the user's cart after order placement
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

    echo "<script>alert('Order placed successfully with Cash on Delivery!'); window.location.href='orders.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
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

<div class="checkout-container">
    <h2>Checkout</h2>

    <div class="user-info">
        <h3>Shipping Details</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <h3>Order Summary</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td style="display: flex; align-items: center;">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" width="50" height="50" style="border-radius: 5px; margin-right: 10px;">
                    <?php echo htmlspecialchars($item['name']); ?>
                </td>
                <td><?php echo $item['quantity']; ?></td>
                <td>Rs.<?php echo number_format($item['price'], 2); ?></td>
                <td>Rs.<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Total: Rs.<?php echo number_format($total_price, 2); ?></h3>

    <!-- COD FORM -->
    <form action="checkout.php" method="POST" onsubmit="return validate()">
        <input type="hidden" name="payment_method" value="COD">

        <label for="address">Enter Shipping Address:</label>
        <input type="text" name="address" id="address" required>

        <label for="phone">Enter Phone Number:</label>
        <input type="text" name="phone" id="phone" required>

        <p>Note: Write Address in the format: Name of place, City</p>
        <p>Example: Kumaripati, Lalitpur</p>

        <button type="submit" class="btn checkout-btn">Place Order (Cash on Delivery)</button>
    </form>

    <hr style="margin: 30px 0;">

    <!-- eSewa Payment Form -->
<!-- eSewa Payment Form -->
<form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" target="_blank">
    <!-- Base amount (same as total since no fees) -->
    <input type="hidden" id="amount" name="amount" value="<?php echo $total_price; ?>">

    <!-- Charges -->
    <input type="hidden" id="tax_amount" name="tax_amount" value="0">
    <input type="hidden" id="product_service_charge" name="product_service_charge" value="0">
    <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0">

    <!-- Total (must equal amount + charges) -->
    <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total_price; ?>">

    <!-- Required fields -->
    <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="">
    <input type="hidden" id="product_code" name="product_code" value="EPAYTEST">
    <input type="hidden" id="success_url" name="success_url" value="http://localhost/shopesp2.0/esewa_success.php">
    <input type="hidden" id="failure_url" name="failure_url" value="http://localhost/shopesp2.0/esewa_failure.php">
    <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
    <input type="hidden" id="signature" name="signature" value="">

    <button type="submit" class="btn checkout-btn">Pay with eSewa</button>
</form>




</div>

<?php include 'footer.php'; ?>

<script>
function validate() {
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;

    if (!/^[0-9]{10}$/.test(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        return false; 
    }

    if (address.length >= 25) {
        alert('The name of address is too long.');
        return false;
    }
    return true;
}

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
<script>
  const secret = "8gBm/:&EnhH.1/q"; // Sandbox secret
  const form = document.getElementById("esewaForm");
  const totalInput = document.getElementById("total_amount");
  const uuidInput = document.getElementById("transaction_uuid");
  const signatureInput = document.getElementById("signature");

  function generateTransactionUUID() {
      const now = new Date();
      return 'TXN-' + now.getTime();
  }

  function generateSignature() {
      const total_amount = totalInput.value;
      const transaction_uuid = uuidInput.value;
      const product_code = document.getElementById("product_code").value;

      const data = `total_amount=${total_amount},transaction_uuid=${transaction_uuid},product_code=${product_code}`;
      const hash = CryptoJS.HmacSHA256(data, secret);
      signatureInput.value = CryptoJS.enc.Base64.stringify(hash);
  }

  form.addEventListener("submit", function() {
      uuidInput.value = generateTransactionUUID();
      generateSignature();
  });
</script>


</body>
</html>
