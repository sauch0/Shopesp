<?php
include 'dbconnection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEsp - Esports Shop</title>
    <link rel="stylesheet" href="styles.css">
    
    <style>
        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 150px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-radius: 5px;
        }

        .dropdown-content a {
            display: block;
            padding: 10px;
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Section Spacing to Prevent Overlap */
        .products-container h2 {
            padding-top: 100px; /* Prevents navbar from overlapping */
            margin-top: -100px;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Attach click event to all category links
            document.querySelectorAll(".category-link").forEach(link => {
                link.addEventListener("click", function (event) {
                    event.preventDefault(); // Prevent page reload
                    let sectionId = this.getAttribute("data-section"); // Get section ID
                    let section = document.getElementById(sectionId);
                    if (section) {
                        let offsetTop = section.offsetTop - 80; // Adjust for navbar
                        window.scrollTo({
                            top: offsetTop,
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>
</head>
<body>

<!-- 🏆 Navbar -->
<header>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php"><img src="logo.png" alt="Esports Shop Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Shop</a></li>
            <li><a href="about.php">About</a></li>
            <li class="dropdown">
                <a href="#">Categories ▼</a>
                <div class="dropdown-content">
                    <a href="#" class="category-link" data-section="keyboard-mouse">Keyboard & Mouse</a>
                    <a href="#" class="category-link" data-section="jersey">Jerseys</a>
                    <a href="#" class="category-link" data-section="hoodie">Hoodies</a>
                </div>
            </li>
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

    <div class="heroImg">
        <img src="esp.jpg" alt="heroimg">
        <div class="imgtext"><p>Welcome to ShopEsp <br> Buy High Quality Products & Esports Mech Here!!</p></div>
    </div>

</header>

<!-- 🏷️ Product Section -->
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
                    <a href="productdetails.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php 
        endif;
    endforeach;
    ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
