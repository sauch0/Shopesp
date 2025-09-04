<?php
session_start();
echo "<script>alert('Payment failed! Please try again.'); window.location.href='checkout.php';</script>";
exit();
