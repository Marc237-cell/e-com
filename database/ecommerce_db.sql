-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 09:27 PM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'shop', 'you cant know', 'admin@techgadgets.com', '2025-11-26 04:12:18');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `created_at`) VALUES
(1, 1, 99.98, 'pending', 'ftytfgf', '2025-11-26 04:39:47'),
(2, 4, 40000.00, 'pending', 'cameroon kumba anglican', '2025-11-26 10:37:01'),
(3, 5, 15000.00, 'pending', 'Kumba', '2025-11-26 13:28:36'),
(4, 5, 40000.00, 'completed', 'lkjhgfds', '2025-11-26 13:39:52'),
(5, 1, 15000.00, 'pending', 'sdfjk', '2025-11-26 19:51:04'),
(6, 1, 800000.00, 'processing', 'SDFGHJKXF', '2025-11-26 19:53:41'),
(7, 1, 800000.00, 'pending', 'sdrghjk', '2025-11-26 20:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 2, 49.99),
(2, 2, 1, 4, 10000.00),
(3, 3, 2, 1, 15000.00),
(4, 4, 1, 1, 10000.00),
(5, 4, 2, 2, 15000.00),
(6, 5, 2, 1, 15000.00),
(7, 6, 7, 1, 800000.00),
(8, 7, 7, 1, 800000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `stock_quantity`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Wireless Bluetooth Headphones', 'High-quality wireless headphones with noise cancellation and 30-hour battery life.', 10000.00, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80', 45, 'Audio', '2025-11-26 04:12:18', '2025-11-26 13:39:52'),
(2, 'Smart Fitness Tracker', 'Track your steps, heart rate, sleep patterns, and more with this sleek fitness tracker.', 15000.00, 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80', 24, 'Wearables', '2025-11-26 04:12:18', '2025-11-26 19:51:04'),
(3, 'Portable Power Bank', '10000mAh portable power bank with fast charging capability for all your devices.', 15000.00, 'https://as1.ftcdn.net/v2/jpg/14/89/49/60/1000_F_1489496039_iVnXmozF1wPZuoKaM1T24WmpNws4nt31.jpg', 100, 'Accessories', '2025-11-26 04:12:18', '2025-11-26 08:39:30'),
(4, 'Smartphone Gimbal Stabilizer', 'Professional smartphone stabilizer for smooth video recording and photography.', 25000.00, 'https://t4.ftcdn.net/jpg/18/05/03/09/240_F_1805030955_mr89Qtxvr4rncMZ7Bg1ftJZgfRF5ht6O.jpg', 25, 'Camera', '2025-11-26 04:12:18', '2025-11-26 08:40:05'),
(5, 'Wireless Charging Pad', 'Fast wireless charging pad compatible with all Qi-enabled devices.', 12000.00, 'https://t4.ftcdn.net/jpg/17/81/77/59/240_F_1781775926_nGfLW1GmQYGa8pCJ5bJt83nCOTkWalRE.jpg', 75, 'Accessories', '2025-11-26 04:12:18', '2025-11-26 08:40:24'),
(6, 'Smart Home Assistant', 'Voice-controlled smart home assistant with premium sound quality.', 6000.00, 'https://images.unsplash.com/photo-1589003077984-894e133dabab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80', 40, 'Smart Home', '2025-11-26 04:12:18', '2025-11-26 08:40:45'),
(7, 'iphone', 'iphone17 pro max', 800000.00, 'https://media.wired.com/photos/68c19b4a8f0bade039bb8750/3:2/w_1920,c_limit/iPhone%2017%20Pro%20SOURCE%20Julian%20Chokkattu.jpg', 28, 'phone ', '2025-11-26 10:47:26', '2025-11-26 20:12:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `address`, `phone`, `created_at`) VALUES
(1, 'marc237', 'marcbright@gmail.com', '$2y$10$PY/6EoHVdRXAdV2Jiti3UOIWZ61.Wt5j5fApZmxiW.bAgbRPwnWXu', 'Ngoh', 'Mbah', NULL, NULL, '2025-11-26 04:38:26'),
(2, 'ngalame', 'ngalame@gmail.com', '$2y$10$ONPIF2/j.ecyWGuErvLk0.HRZYIVLDYpu3NlWs1td63dpnKnH9mbC', 'ngalame', 'derella', NULL, NULL, '2025-11-26 10:30:05'),
(4, 'andy', 'andy@gmail.com', '$2y$10$UDoLBjFV2ozMtR13nvj2GOXNp9bv/06rQKJ3WUm0tI2Zh6KkM8Riq', 'ahande', 'derella', NULL, NULL, '2025-11-26 10:33:39'),
(5, 'ophicial', 'makiasergemoki@gmail.com', '$2y$10$jRPa00bwevmfiSdD3SGKHOQCe7Qr/W68tNb0Kb57EdCVOXwRuelrK', 'Dee', 'Nyc', NULL, NULL, '2025-11-26 13:26:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
