<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'dbconnection.php';
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this product? This action cannot be undone.");
        }
    </script>

</head>

<body>

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

 <div class="products-container">
    <?php
    $types = ['keyboard & mouse' => 'keyboard-mouse', 'jersey' => 'jersey', 'hoodie' => 'hoodie'];

    foreach ($types as $type => $sectionId):
        $query = "SELECT * FROM products WHERE type = '$type'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
    ?>
        <h2 id="<?php echo $sectionId; ?>"><?php echo ucfirst($type); ?></h2>
        <div class="products">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product">
                    <img src="<?php echo 'uploads/' . basename($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" 
                         onerror="this.src='default.jpg';">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Rs.<?php echo $row['price']; ?></p>
                    <a href="editproduct.php?id=<?php echo $row['id']; ?>" class="btn">Edit Product</a>
                    <a href="deleteproduct.php?id=<?php echo $row['id']; ?>" class="btn" onclick="return confirmDelete()">Delete Product</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php 
        endif;
    endforeach;
    ?>



<?php include 'footer.php'; ?>


</body>
</html>
