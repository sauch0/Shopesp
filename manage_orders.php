<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'dbconnection.php';

// Handle Order Status Update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $query = "UPDATE orders SET status=? WHERE order_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
}

// Fetch all orders with product and user details
$query = "SELECT orders.*, products.name AS product_name, products.image, users.username 
          FROM orders 
          JOIN products ON orders.product_id = products.id 
          JOIN users ON orders.user_id = users.id 
          ORDER BY orders.order_date DESC";


$stmt = $conn->prepare($query);
if ($stmt->execute()) {
    $result = $stmt->get_result();
} else {
    echo "Error executing query: " . $stmt->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
    max-width: 1200px;
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

.status {
    font-weight: bold;
    padding: 5px;
    border-radius: 5px;
}

/* Status Colors */
.pending {
    color: #ff9800;
}

.shipped {
    color: #2196f3;
}

.delivered {
    color: #4caf50;
}

.cancelled {
    color: #f44336;
}

/* Buttons */
.btn-update {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 5px;
}

.btn-update:hover {
    background-color: #388E3C;
}

.status-dropdown {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

/* Product Info Styling */
.product-info {
    display: flex;
    align-items: center;
    gap: 10px; /* Space between image and text */
}

/* Product Image Styling */
.product-info img {
    width: 50px;  /* Adjust image size */
    height: 50px; /* Keep it square */
    object-fit: cover;
    border-radius: 5px; /* Slightly rounded corners */
    border: 1px solid #ddd; /* Light border */
}


    </style>
</head>
<body>

<!-- Admin Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="admin.php"><img src="logo.png" alt="Esports Shop Logo"></a>
    </div>
    <ul class="nav-links">
        <li><a href="admin.php">Products</a></li>
        <li><a href="addproducts.php">Add Products</a></li>
        <li><a href="manage_orders.php">Orders</a></li>
    </ul>
    <div class="nav-signin">
        <a href="logout.php" class="btn">Logout</a>
    </div>
</nav>

<!-- Orders Table -->
<div class="container">
    <h2>Manage Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <div class="product-info">
                            <img src="<?= 'uploads/' . basename($row['image']) ?>" 
                                 alt="<?= htmlspecialchars($row['username']) ?>" 
                                 onerror="this.src='default.jpg';">
                            <span><?= htmlspecialchars($row['product_name']) ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td class="status <?= $row['status'] ?>">
                        <?= ucfirst(htmlspecialchars($row['status'])) ?>
                    </td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                            <select name="status" class="status-dropdown">
                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="delivered" <?= $row['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn-update">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
