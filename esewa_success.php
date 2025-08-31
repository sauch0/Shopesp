<?php
include 'dbconnection.php';

if (isset($_GET['q']) && $_GET['q'] == 'su') {
    $refId = $_GET['refId'];
    $oid   = $_GET['oid'];
    $amt   = $_GET['amt'];

    $url = "https://uat.esewa.com.np/epay/transrec";
    $data = [
        'amt'=> $amt,
        'rid'=> $refId,
        'pid'=> $oid,
        'scd'=> 'EPAYTEST'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if (strpos($response, "Success") !== false) {
        // Mark order as paid
        mysqli_query($conn, "UPDATE orders SET status='paid' WHERE order_code='$oid'");
        echo "<h2>✅ Payment Successful!</h2>";
        echo "<p>Reference ID: $refId</p>";
        echo "<a href='orders.php'>View Orders</a>";
    } else {
        echo "<h2>❌ Payment Verification Failed!</h2>";
    }
}
?>
