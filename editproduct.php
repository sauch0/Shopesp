<?php
include 'dbconnection.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the existing product details
    $query = "SELECT * FROM products WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $product = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }

    if (!$product) {
        echo "<script>alert('Product not found!'); window.location.href='admin.php';</script>";
        exit();
    }
}

// Handle the update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["pname"];
    $price = $_POST["priceinp"];
    $description = $_POST["description"];
    $type = $_POST["product_type"];
    
    // Handle image upload
    if (!empty($_FILES['imageFile']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["imageFile"]["name"]);
        move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file);
    } else {
        $target_file = $product['image']; // Keep existing image if not changed
    }

    $query = "UPDATE products SET name=?, price=?, image=?, description=?, type=? WHERE id=?";
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $price, $target_file, $description, $type, $product_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Product Updated Successfully'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Error Updating Product');</script>";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <a href="admin.php"><img src="logo.png" alt="Esports Shop Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="admin.php">Products</a></li>
            <li><a href="addproducts.php">Add Products</a></li>
        </ul>
        <div class="nav-signin">
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </nav>

    <form action="editproduct.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data" onsubmit="return validate()">
        <h2>Edit Product</h2>

        <input type="text" name="pname" id="pname" placeholder="Name of product" value="<?php echo $product['name']; ?>" required><br>
        <input type="text" placeholder="Price of the Product" name="priceinp" id="priceinp" value="<?php echo $product['price']; ?>" required><br>
        
        <label for="imageFile">Upload Image:</label>
        <input type="file" name="imageFile" id="imageFile" accept="image/*"><br>
        <p>Current Image:</p>
        <img src="<?php echo $product['image']; ?>" alt="Product Image" width="100"><br>
        
        <textarea name="description" id="description" cols="34" rows="10" placeholder="Product Description" required><?php echo $product['description']; ?></textarea><br>
        
        <label for="product_type">Product Type:</label>
            <select name="product_type" id="product_type" required>
                <option value="jersey" <?php if($product['type'] == 'Jersey') echo 'selected'; ?>>Jersey</option>
                <option value="hoodie" <?php if($product['type'] == 'Hoodie') echo 'selected'; ?>>Hoodie</option>
                <option value="keyboard & mouse" <?php if($product['type'] == 'Keyboard & Mouse') echo 'selected'; ?>>Keyboard & Mouse</option>
            </select>

            <br><br>
        
            <button type="submit">Update Product</button>

    </form>
    
    <script>
    function validate() {
        const price = document.getElementById('priceinp').value;
        const pname = document.getElementById('pname').value;
        const description = document.getElementById('description').value;
        const imageFile = document.getElementById('imageFile');
        const type = document.getElementById('product_type').value;

        // Price validation (should be positive)
        if (price <= 0 || isNaN(price)) {
            alert("Price should be a positive number.");
            return false;
        }

        // Product name validation (length check)
        if (pname.length < 4 || pname.length > 25) {
            alert("Product name should be between 4 and 25 characters.");
            return false;
        }

        // Description validation (length check)
        if (description.length < 10 || description.length > 500) {
            alert("Description should be between 10 and 500 characters.");
            return false;
        }

        // Dropdown validation (ensures a type is selected)
        if (type === "") {
            alert("Please select a product type.");
            return false;
        }

        // Image validation (only if a new file is selected)
        if (imageFile.files.length > 0) {
            const file = imageFile.files[0];
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            const maxSize = 2 * 1024 * 1024; // 2MB

            // Check file type
            if (!allowedTypes.includes(file.type)) {
                alert("Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.");
                return false;
            }

            // Check file size
            if (file.size > maxSize) {
                alert("Image size should be less than 2MB.");
                return false;
            }
        }

        return true;
    }
</script>

</body>
</html>