<?php
include 'dbconnection.php';

if (isset($_GET['q']) && $_GET['q'] == 'fu') {
    echo "<script>alert('Payment Failed!'); window.location.href='checkout.php';</script>";
}
