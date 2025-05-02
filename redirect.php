<?php
// Check if the `destination` parameter exists in the URL
if (isset($_GET['destination'])) {
    // Sanitize the input to prevent security issues
    $destination = htmlspecialchars($_GET['destination']);
    
    // Define a list of allowed destinations
    $allowedDestinations = [
        'facebook' => 'https://www.facebook.com',
        'instagram' => 'https://www.instagram.com',
        'youtube' => 'https://www.youtube.com',
        'twitter' => 'https://www.twitter.com',
        'faq' => 'faq.php', // Local FAQ page
        'about' => 'about.php', // Local About page
        'contact' => 'https://mail.google.com/mail/?view=cm&fs=1&to=shopesp@gmail.com', // Local Contact page
    ];

    // Check if the destination is allowed and redirect to the appropriate URL
    if (array_key_exists($destination, $allowedDestinations)) {
        header("Location: " . $allowedDestinations[$destination]);
        exit();
    } else {
        // If the destination is not in the allowed list, show an error
        echo "Invalid destination!";
    }
} else {
    // If no destination is provided, show an error
    echo "No destination specified!";
}
?>
