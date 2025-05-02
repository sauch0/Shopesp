<?php
include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST["pname"];
    $price = $_POST["priceinp"];
    $description = $_POST["description"]; // Make sure to include this
    $type = $_POST["type"]; // Matches database column name

    // Handling file upload
    $targetDir = "uploads/"; // Folder to store images
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the folder if it doesn't exist
    }

    // Check if an image is uploaded
    if (isset($_FILES["imageFile"]) && $_FILES["imageFile"]["error"] == 0) {
        $fileName = basename($_FILES["imageFile"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only image files
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $targetFilePath)) {
                // ✅ **Fix: Include `description` in the query**
                $query = "INSERT INTO products (name, price, type, image, description) VALUES (?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($conn, $query)) {
                    // ✅ **Fix: Include `description` in binding parameters**
                    mysqli_stmt_bind_param($stmt, "sdsss", $name, $price, $type, $targetFilePath, $description);

                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>alert('Product Added Successfully'); window.location.href='admin.php';</script>";
                    } else {
                        echo "<script>alert('Error adding product.');</script>";
                    }

                    mysqli_stmt_close($stmt);
                }
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image.');</script>";
    }

    mysqli_close($conn);
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
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
            <li><a href="manage_orders.php">Orders</a></li>
        </ul>
        <div class="nav-signin">
                <a href="logout.php" class="btn">Logout</a>
        </div>
    </nav>

<form action="addproducts.php" method="POST" enctype="multipart/form-data" onsubmit="return validate()">
    <h2>Add Products</h2>
    
    <input type="text" name="pname" id="pname" placeholder="Name of product" required><br>
    
    <input type="number" placeholder="Price of the Product" name="priceinp" id="priceinp" required><br>
    
    <!-- File input for selecting an image -->
    <input type="file" name="imageFile" id="image"  required><br>
    
    <textarea name="description" id="description" cols="34" rows="10" placeholder="Product Description" required></textarea><br>
    
    <label for="type">Select Product Type:</label>
    <select name="type" id="type" required>
        <option value="">Select Type</option>
        <option value="jersey">Jersey</option>
        <option value="keyboard & mouse">Keyboard & Mouse</option>
        <option value="hoodie">Hoodie</option>
    </select><br><br>

    <button type="submit">Add Product</button>
</form>



</body>
</html>

<script>
    function validate() {
        const price = document.getElementById('priceinp').value;
        const pname = document.getElementById('pname').value;
        const description = document.getElementById('description').value;
        const image = document.getElementById('image');
        const type = document.getElementById('type').value;

        // Price validation
        if (price <= 0) {
            alert("Price should be a positive number.");
            return false;
        }

        // Product name validation (length)
        if (pname.length >= 16) {
            alert("The length of the product name should be less than 16 characters.");
            return false;
        }

        if (pname.length <= 3) {
            alert("The product name should be at least 4 characters long.");
            return false;
        }

        // Description validation (length check)
        if (description.length < 10 || description.length > 500) {
            alert("Description should be between 10 to 500 characters.");
            return false;
        }

        // Dropdown validation (ensures a type is selected)
        if (type === "") {
            alert("Please select a product type.");
            return false;
        }

        // Image validation
        if (image.files.length === 0) {
            alert("Please upload an image.");
            return false;
        }

        const file = image.files[0];
        const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        const maxSize = 2 * 1024 * 1024; // 2MB

        // Check image type
        if (!allowedTypes.includes(file.type)) {
            alert("Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.");
            return false;
        }

        // Check image size
        if (file.size > maxSize) {
            alert("Image size should be less than 2MB.");
            return false;
        }

        return true;
    }
</script>

