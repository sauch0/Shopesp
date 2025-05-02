<?php
include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('User already exists with this email!');</script>";
    } else {
        // Insert user with hashed password
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Registered successfully');</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

?>

<head>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    
    <form action="register.php" method="POST" onsubmit="return validate(event)">
        <img src="blacklogo.png" alt="logo">
        <br><br><hr><br>
        <input type="text" name="name" placeholder="Username" id="name" required>
        <input type="email" name="email" placeholder="Email" id="email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <input type="password" name="cpassword" placeholder="Conform Password" id="cpassword" required> <br>  
        <button type="submit">Register</button>
        <br>
        <br>
        <hr>
        <br>
        <p>Already have an account? <a href="login.php">login</a></p>
    </form>

    <script>
    function validate(event) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const cpassword = document.getElementById('cpassword').value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

        // Check if fields are empty
        if (name === "" || email === "") {
            alert("Username or Email field is empty. Please fill all the fields.");
            event.preventDefault(); // Stop form submission
            return false;
        }

        // Validate username length
        if (name.length < 4) {
            alert("The username must contain at least 4 characters.");
            event.preventDefault();
            return false;
        }

        // Validate email format
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            event.preventDefault();
            return false;
        }

        // Check password strength (Uppercase, Lowercase, Number)
        if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/\d/.test(password)) {
            alert("Password must contain at least one uppercase letter, one lowercase letter, and one number.");
            event.preventDefault();
            return false;
        }

        // Check if passwords match
        if (password !== cpassword) {
            alert("The passwords don't match.");
            event.preventDefault(); 
            return false;
        }

        // preventDefault() function to prevent the form from submitting when validation fails.

        return true; // Allow form submission if all checks pass
    }
</script>


