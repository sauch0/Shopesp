-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 09:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopesp2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(23, 10, 15, 999),
(24, 10, 11, 100),
(38, 11, 13, 1),
(43, 12, 13, 2147483647),
(75, 8, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(20) NOT NULL DEFAULT 'COD',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `quantity`, `address`, `phone`, `status`, `payment_method`, `order_date`) VALUES
(38, 8, 16, 1, 'eSewa Payment', 'N/A', 'paid', 'eSewa', '2025-09-01 11:50:26'),
(39, 8, 13, 1, 'lalitpur', '9841232154', 'cancelled', 'COD', '2025-09-01 11:52:17'),
(40, 8, 15, 1, 'eSewa Payment', 'NA', 'paid', 'eSewa', '2025-09-01 12:05:13'),
(41, 8, 13, 1, 'lalitpur', '9841232154', 'cancelled', 'COD', '2025-09-01 12:06:53'),
(42, 8, 15, 1, 'eSewa Payment', 'NA', 'paid', 'eSewa', '2025-09-01 12:07:56'),
(43, 8, 15, 1, 'eSewa Payment', 'NA', 'paid', 'eSewa', '2025-09-01 12:10:21'),
(44, 8, 15, 1, 'Unknown', '0000000000', 'paid', 'ESEWA', '2025-09-01 12:15:13'),
(45, 8, 15, 1, 'lalitpur', '9806800001', 'cancelled', 'COD', '2025-09-01 12:23:41'),
(46, 8, 15, 1, 'lalitpur', '9841232154', 'paid', 'ESEWA', '2025-09-01 12:25:48'),
(47, 8, 15, 1, 'lalitpur', '9841232154', 'paid', 'ESEWA', '2025-09-01 12:31:58'),
(48, 8, 15, 1, 'lalitpur', '9841232154', 'delivered', 'ESEWA', '2025-09-01 12:45:05'),
(49, 8, 15, 1, 'adasdwasdwa', '9806800001', 'delivered', 'ESEWA', '2025-09-02 06:22:11'),
(50, 8, 13, 1, 'lalitpur', '9806800001', 'delivered', 'COD', '2025-09-02 06:22:57'),
(51, 8, 15, 1, 'lalitpur', '9806800001', 'delivered', 'eSewa', '2025-09-02 06:43:34'),
(52, 8, 15, 1, 'lalitpur', '9806800001', 'cancelled', 'COD', '2025-09-02 08:30:56'),
(53, 13, 15, 1, 'ikhalukhu, lalitpur', '9810140317', 'paid', 'eSewa', '2025-09-02 08:37:45'),
(54, 8, 15, 2, 'lalitpur', '9806800001', 'paid', 'eSewa', '2025-09-03 00:25:25'),
(55, 8, 15, 2, 'lalitpur', '9841232154', 'paid', 'Stripe', '2025-09-04 05:49:24'),
(56, 8, 13, 1, 'lalitpur', '9806800001', 'paid', 'eSewa', '2025-09-04 05:50:53'),
(57, 8, 5, 1, 'lalitpur', '9841232154', 'paid', 'Stripe', '2025-09-04 05:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--
-- Error reading structure for table shopesp2.order_items: #1932 - Table &#039;shopesp2.order_items&#039; doesn&#039;t exist in engine
-- Error reading data for table shopesp2.order_items: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `shopesp2`.`order_items`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `type`) VALUES
(1, 'Team T1', 1000.00, 't1.jpg', 'A jersey worn by the professional players of Team T1 from Korea.', 'jersey'),
(2, 'Team Liquid Hoodie', 2000.00, 'LiquidHood.jpg', 'A hoodie worn by the professional players of team liquid ', 'hoodie'),
(3, 'Team Fnatic', 2300.00, 'uploads/fnatichoodie.jpg', 'A black hoodie worn by the professional players of Team Fnatic from Europe.', 'hoodie'),
(4, 'Team GenG', 1200.00, 'uploads/gengBlack.jpg', 'A jersey worn by the professional players of Team GenG from Korea.', 'jersey'),
(5, 'Team G2', 1300.00, 'uploads/g2.jpg', 'A jersey worn by the professional players of Team G2.', 'jersey'),
(6, 'Team Liquid', 1000.00, 'uploads/LiquidBlue.jpg', 'A jersey worn by the professional players of Team Liquid.', 'jersey'),
(7, 'Team Fnatic', 1200.00, 'uploads/fnatic.png', 'A jersey worn by the professional players of Team Fnatic.', 'jersey'),
(8, 'Team G2', 2000.00, 'uploads/g2hoodie.jpg', 'A Hoodie worn by the professional players of Team G2.', 'Shoes'),
(9, 'Team OG', 2000.00, 'uploads/oghood.jpg', 'A Hoodie worn by the professional players of Team OG.', 'hoodie'),
(10, 'Team T1', 2500.00, 'uploads/t1hoodie.jpg', 'A Hoodie worn by the professional players of Team T1.', 'hoodie'),
(11, 'Corsair K63', 1800.00, 'uploads/Corsair K63.jpg', 'Corsair K63 Compact Mechanical Gaming Keyboard Cherry MX Red (15792)', 'keyboard & mouse'),
(12, 'The ROG Azoth', 2900.00, 'uploads/The ROG Azoth.jpg', 'The ROG Azoth is a fully customizable, premium mechanical gaming keyboard', 'keyboard & mouse'),
(13, 'Logitech G413 TKL SE ', 3000.00, 'uploads/Logitech G413 TKL SE.jpg', 'Logitech G413 TKL SE Fully Customizable Gaming Keyboard. ', 'keyboard & mouse'),
(14, 'Team GenG', 2600.00, 'uploads/genghoodie.jpg', 'A Black Hoodie worn by the professional players of Team GenG who plays in LCK.', 'hoodie'),
(15, 'Logitech G502 ', 10.00, 'uploads/g502-lightspeed.png', 'Logitech G502 a light speed gaming mouse.', 'keyboard & mouse'),
(16, 'Red Dragon M914', 900.00, 'uploads/redragon-m914.jpg', 'Red Dragon M914 wireless gaming mouse with bluetooth.', 'keyboard & mouse');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(8, 'saumya', 'saumya@gmail.com', '$2y$10$vabi2GsUaqhB8NwWoIa96enWckCMwz7P5RXr5mIra9TD1x1OrMs.e', 'user'),
(9, 'admin', 'admin@gmail.com', '$2y$10$nqoLekTkdFri3oZMwurl7.K/hXlsEcr5LtV18H/ZtkykT4YrIur7W', 'admin'),
(10, 'susan', 'susan1@gmail.com', '$2y$10$TRyG9uqB6vWIluNMpeplzO.SdQG/mqm44KGQu4D6ymyT3MRVK5e/6', 'user'),
(11, 'manish', 'manish@gmail.com', '$2y$10$mgzL7oXVBzfWbHHmGFwP1Oq5h85s7OT5M/4YnOTlhxW/YLHnLzide', 'user'),
(12, 'fuck u', 'fuckumf@gmail.com', '$2y$10$kSO4s4gWLV1p7Pj.X.dqUefMUJQz/3qdoDl70VvpYBxKeO2wb/bGm', 'user'),
(13, 'yachi', 'chitrakarsaumya2004@gmail.com', '$2y$10$Ukob4YOwxpWuOEwJ0nud0uGEDyjji.boCSabFXm4usrr5uK0MPNO.', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
