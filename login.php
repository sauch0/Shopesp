<?php
include 'dbconnection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"]; // User-entered password

    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        // Use password_verify() to compare hashed password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<head>
    <link rel="stylesheet" href="styles.css">
</head>

<form action="login.php" method="POST" onsubmit="return validate(event)">
    <img src="blacklogo.png" alt="logo">
    <br><br><hr><br>
    <input type="email" name="email" placeholder="Email" id="email" required>
    <input type="password" name="password" id="password" placeholder="Password" required> <br>  
    <button type="submit">Log in</button>
    <br>
    <br>
    <hr>
    <br>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</form>

<script>
    function validate(event) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex for email validation

        // Check if fields are empty
        if (email === "") {
            alert("Email field is empty.");
            event.preventDefault(); // Stop form submission
            return false;
        }

        // Validate email format
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            event.preventDefault();
            return false;
        }

        // preventDefault() function to prevent the form from submitting when validation fails.

        return true; // Allow form submission if all checks pass
    }
</script>



