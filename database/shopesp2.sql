-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 06:29 AM
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
(43, 12, 13, 2147483647);

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
  `status` enum('pending','delivered','cancelled') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `quantity`, `address`, `phone`, `status`, `order_date`) VALUES
(11, 8, 11, 1, 'Ikhalukhu, Lalitpur', '9841223456', 'delivered', '2025-03-17 12:10:14'),
(12, 8, 4, 1, 'Ikhalukhu, Lalitpur', '9841223456', 'delivered', '2025-03-17 12:10:14'),
(13, 8, 14, 2, 'Ikhalukhu, Lalitpur', '9841223456', 'delivered', '2025-03-17 12:10:14'),
(14, 8, 16, 1, 'Ikhalukhu, Lalitpur', '9841223456', 'cancelled', '2025-03-17 12:10:14'),
(15, 8, 2, 1, 'Ikhalukhu, Lalitpur', '2342334523', 'cancelled', '2025-03-17 12:12:33'),
(16, 8, 4, 4, 'Ikhalukhu, Lalitpur', '3214569874', 'delivered', '2025-03-18 01:13:15'),
(17, 8, 13, 3, 'Ikhalukhu, Lalitpur', '2342334523', 'delivered', '2025-03-18 03:33:12'),
(18, 8, 1, 3, 'Lalitpur, Nepal', '1111111111', 'delivered', '2025-03-18 03:45:26'),
(19, 8, 12, 1, 'Lalitpur, Nepal', '2342334523', 'cancelled', '2025-03-22 23:23:15'),
(20, 8, 11, 3, 'Lalitpur, Nepal', '2342334523', 'cancelled', '2025-03-22 23:23:15'),
(21, 8, 9, 4, 'Lalitpur, Nepal', '2342334523', 'pending', '2025-03-23 03:05:08'),
(22, 8, 5, 4, 'Lalitpur, Nepal', '2342334523', 'pending', '2025-03-23 03:05:08'),
(23, 8, 12, 1, 'mega college,kumaripati', '0098465995', 'delivered', '2025-03-23 03:15:02'),
(24, 8, 15, 10000, 'mega college,kumaripati', '0098465995', 'cancelled', '2025-03-23 03:15:02'),
(25, 8, 12, 1, 'ftjhfukukkyujjutujt', '4545677777', 'pending', '2025-03-23 03:48:47'),
(26, 11, 15, 1, 'Ikhalukhu, Lalitpur', '9841223456', 'pending', '2025-03-24 01:57:45'),
(27, 8, 12, 4, 'Ikhalukhu, Lalitpur', '9841223456', 'cancelled', '2025-03-25 06:52:43'),
(28, 8, 13, 1, 'Ikhalukhu, Lalitpur', '9841223456', 'delivered', '2025-03-25 06:52:43'),
(29, 8, 4, 1, 'Ikhalukhu, Lalitpur', '9841223456', 'pending', '2025-03-25 06:52:43'),
(30, 12, 11, 101, 'lagenkhel', '2342334523', 'delivered', '2025-03-27 02:32:04'),
(31, 12, 16, 100, 'lagenkhel', '2342334523', 'delivered', '2025-03-27 02:32:04'),
(32, 12, 5, 100, 'lagenkhel', '2342334523', 'delivered', '2025-03-27 02:32:04'),
(33, 8, 15, 4, 'ADDa', '9841232154', 'delivered', '2025-04-26 12:24:24');

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
(15, 'Logitech G502 ', 800.00, 'uploads/g502-lightspeed.png', 'Logitech G502 a light speed gaming mouse.', 'keyboard & mouse'),
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
(12, 'fuck u', 'fuckumf@gmail.com', '$2y$10$kSO4s4gWLV1p7Pj.X.dqUefMUJQz/3qdoDl70VvpYBxKeO2wb/bGm', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
