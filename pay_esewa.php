<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = 0;

// Fetch cart items
$cart_query = mysqli_query($conn, "SELECT cart.product_id, cart.quantity, products.price 
                                   FROM cart 
                                   INNER JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = '$user_id'");

while ($row = mysqli_fetch_assoc($cart_query)) {
    $total_price += $row['price'] * $row['quantity'];
}

if ($total_price <= 0) {
    echo "<script>alert('Cart is empty!'); window.location.href='cart.php';</script>";
    exit();
}

$address = mysqli_real_escape_string($conn, $_POST['address']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$order_date = date("Y-m-d H:i:s");


// Insert pending order (main order record)
mysqli_query($conn, "INSERT INTO orders (user_id, quantity, address, phone, status, order_date) 
                     VALUES ('$user_id', '$total_price', '$address', '$phone', 'pending', '$order_date')");

$order_id = mysqli_insert_id($conn);

// Save each product inside order_items
mysqli_data_seek($cart_query, 0); // reset result pointer
while ($row = mysqli_fetch_assoc($cart_query)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];

    mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')");
}


// Clear cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");

// eSewa Payment details
$amount = $total_price;
$pid = "ORDER_" . $order_id; // unique order code
$success_url = "http://localhost/shopesp2.0/esewa_success.php?q=su";
$failure_url = "http://localhost/shopesp2.0/esewa_failed.php?q=fu";
?>

<form id="esewaForm" action="https://uat.esewa.com.np/epay/main" method="POST">
    <input value="<?php echo $amount; ?>" name="tAmt" type="hidden">
    <input value="<?php echo $amount; ?>" name="amt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">
    <input value="EPAYTEST" name="scd" type="hidden">
    <input value="<?php echo $pid; ?>" name="pid" type="hidden">
    <input value="<?php echo $success_url; ?>" type="hidden" name="su">
    <input value="<?php echo $failure_url; ?>" type="hidden" name="fu">
</form>

<script>
    document.getElementById("esewaForm").submit();
</script>
