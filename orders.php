<?php
session_start();
include 'dbconnection.php';

// Check if the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Handle order cancellation
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    $query = "UPDATE orders SET status='cancelled' WHERE order_id=? AND user_id=? AND status='pending'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
}

// Fetch user orders with product image
$query = "SELECT orders.*, products.name, products.image 
          FROM orders 
          JOIN products ON orders.product_id = products.id 
          WHERE orders.user_id=? 
          ORDER BY orders.order_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Orders Table */
        .container {
            width: 90%;
            max-width: 1020px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 90px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #222;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        /* Product Image */
        .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-info img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Status Colors */
        .status {
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
        }

        .pending { color: #ff9800; }
        .shipped { color: #2196f3; }
        .delivered { color: #4caf50; }
        .cancelled { color: #f44336; }

        /* Buttons */
        .btn-cancel {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-cancel:hover {
            background-color: #d32f2f;
        }

        .btn-disabled {
            background-color: #aaa;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: not-allowed;
            border-radius: 5px;
        }
    </style>
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

<!-- Orders Table -->
<div class="container">
    <h2>My Orders</h2>
    <table>
        <thead>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Payment Method</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Action</th>
    </tr>
</thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <div class="product-info">
                        <img src="<?= 'uploads/' . basename($row['image']) ?>" 
                            alt="<?= htmlspecialchars($row['name']) ?>" 
                            onerror="this.src='default.jpg';">
                        <span><?= htmlspecialchars($row['name']) ?></span>
                    </div>
                </td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['payment_method']) ?></td>
                <td class="status <?= $row['status'] ?>">
                    <?= ucfirst(htmlspecialchars($row['status'])) ?>
                </td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td>
                    <?php if ($row['status'] == 'pending'): ?>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                            <button type="submit" name="cancel_order" class="btn-cancel">Cancel</button>
                        </form>
                    <?php else: ?>
                        <button class="btn-disabled" disabled>Not Available</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>

    </table>
</div>

</body>
</html>
