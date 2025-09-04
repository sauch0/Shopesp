<?php 
session_start();
include 'dbconnection.php';
require 'vendor/autoload.php'; // Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51S2NPZCmz2TmtiKUqiXWpzJ8tFHyGaRW1iOGPd2Srt6PxbMWl8EiMtunKdxEHfsBr84GQgWqXjzz6bs3HYeKhtjq00Y3Lt7c1b'); // Stripe Test Secret Key

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
$cart_query = mysqli_query($conn, "
    SELECT cart.product_id, cart.quantity, products.name, products.price, products.image 
    FROM cart 
    INNER JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = '$user_id'
");

$cart_items = [];
while ($row = mysqli_fetch_assoc($cart_query)) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

if (empty($cart_items)) {
    echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $method  = $_POST['payment_method'];

    if ($method === "COD") {
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity   = $item['quantity'];

            mysqli_query($conn, "INSERT INTO orders (user_id, product_id, quantity, address, phone, status, payment_method) 
                                 VALUES ('$user_id', '$product_id', '$quantity', '$address', '$phone', 'pending', 'COD')");
        }
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
        echo "<script>alert('Order placed successfully with Cash on Delivery!'); window.location.href='orders.php';</script>";
        exit();
    }

    if ($method === "eSewa") {
        $_SESSION['esewa_address'] = $address;
        $_SESSION['esewa_phone']   = $phone;

        $total_amount = $total_price;
        $transaction_uuid = 'TXN-' . time();

        $secret = "8gBm/:&EnhH.1/q";
        $data = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=EPAYTEST";
        $signature = base64_encode(hash_hmac('sha256', $data, $secret, true));

        echo "
        <form id='esewaPay' action='https://rc-epay.esewa.com.np/api/epay/main/v2/form' method='POST'>
            <input type='hidden' name='amount' value='$total_amount'>
            <input type='hidden' name='tax_amount' value='0'>
            <input type='hidden' name='product_service_charge' value='0'>
            <input type='hidden' name='product_delivery_charge' value='0'>
            <input type='hidden' name='total_amount' value='$total_amount'>
            <input type='hidden' name='transaction_uuid' value='$transaction_uuid'>
            <input type='hidden' name='product_code' value='EPAYTEST'>
            <input type='hidden' name='success_url' value='http://localhost/shopesp2.0/esewa_success.php'>
            <input type='hidden' name='failure_url' value='http://localhost/shopesp2.0/esewa_failure.php'>
            <input type='hidden' name='signed_field_names' value='total_amount,transaction_uuid,product_code'>
            <input type='hidden' name='signature' value='$signature'>
        </form>
        <script>document.getElementById('esewaPay').submit();</script>
        ";
        exit();
    }

    if ($method === "Stripe") {
        // Convert NPR to USD
        $conversion_rate = 133; // Example: 1 USD = 133 NPR
        $total_price_usd = round($total_price / $conversion_rate, 2);

        // Stripe requires minimum $0.50
        if ($total_price_usd < 0.5) {
            $total_price_usd = 0.5;
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order by user #' . $user_id,
                    ],
                    'unit_amount' => intval($total_price_usd * 100), // in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost/shopesp2.0/card_success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost/shopesp2.0/failure.php',
            'metadata' => ['user_id' => $user_id],
        ]);

        header("Location: " . $session->url);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f9f9f9; }
        .checkout-container { max-width: 900px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f1f1f1; }
        .btn { padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; margin-top: 15px; background: #27ae60; color: #fff; }
        .btn:hover { background: #219150; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .order-summary { margin-top: 30px; }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <h3>Shipping Details</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>

    <div class="order-summary">
        <h3>Order Summary</h3>
        <table>
            <tr>
                <th>Product</th><th>Quantity</th><th>Price</th><th>Total</th>
            </tr>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td>
                    <img src="<?= htmlspecialchars($item['image']); ?>" width="50" height="50" style="border-radius:5px;margin-right:10px;">
                    <?= htmlspecialchars($item['name']); ?>
                </td>
                <td><?= $item['quantity']; ?></td>
                <td>Rs.<?= number_format($item['price'], 2); ?></td>
                <td>Rs.<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>Total: Rs.<?= number_format($total_price, 2); ?></h3>
    </div>

    <!-- Combined Form -->
    <form id="checkoutForm" method="POST" onsubmit="return validateForm()">
        <label for="address">Shipping Address</label>
        <input type="text" name="address" id="address" required>
        
        <label for="phone">Phone Number</label>
        <input type="text" name="phone" id="phone" required>

        <label for="payment_method">Select Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="">-- Select --</option>
            <option value="COD">Cash on Delivery</option>
            <option value="eSewa">eSewa</option>
            <option value="Stripe">Stripe</option>
        </select>

        <button type="submit" class="btn">Place Order</button>
    </form>
</div>

<script>
function validateForm() {
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;

    if (!/^[0-9]{10}$/.test(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        return false;
    }
    if (address.length < 5 || address.length > 100) {
        alert("Address must be between 5 and 100 characters.");
        return false;
    }
    return true;
}
</script>
</body>
</html>
