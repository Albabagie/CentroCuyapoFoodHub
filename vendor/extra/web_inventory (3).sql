-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2024 at 03:20 PM
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
-- Database: `web_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `admin_name`, `admin_description`) VALUES
(1, 1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `remove_item` tinyint(1) NOT NULL DEFAULT 0,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `item_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `item_id`, `customer_id`, `remove_item`, `order_date`, `item_status`) VALUES
(13, 23, 7, 1, '2024-04-02 10:19:55', 0),
(14, 29, 7, 1, '2024-04-02 10:20:18', 0),
(15, 29, 15, 1, '2024-04-02 20:25:41', 1),
(16, 30, 15, 0, '2024-04-02 20:25:57', 1),
(17, 28, 15, 0, '2024-04-02 20:26:46', 1),
(18, 27, 15, 0, '2024-04-02 20:37:23', 1),
(19, 34, 15, 0, '2024-04-02 20:37:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `name` varchar(255) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `price` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `cart_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`name`, `qty`, `price`, `customer_id`, `item_id`, `cart_id`) VALUES
('item 1-test upload k', 2, 100, 7, 23, 13),
('BIBIMBAP MEAL-with pork', 1, 75, 7, 29, 14),
('namename-des', 1, 123, 15, 27, 18),
('BIBIMBAP MEAL-with spam', 1, 65, 15, 28, 17),
('BIBIMBAP MEAL-with pork', 1, 75, 15, 29, 15),
('BIBIMBAP MEAL-with beef', 1, 80, 15, 30, 16),
('BAKED SUSHI-spam', 1, 145, 15, 34, 19);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `user_id`, `name`, `birthdate`, `gender`, `date_created`) VALUES
(1, 2, 'Lau', '2024-03-13', 'Male', '2024-03-28 00:00:00'),
(7, 37, 'user1', '2005-12-13', 'Male', '2024-03-28 00:00:00'),
(15, 53, 'lau', '2001-12-12', 'Male', '2024-03-31 18:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_type` varchar(255) NOT NULL,
  `employee_status` enum('Active','Out') NOT NULL,
  `employee_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `user_id`, `employee_name`, `employee_type`, `employee_status`, `employee_date`) VALUES
(12, 34, 'Employee One', 'Cook', 'Active', '2024-03-21'),
(14, 36, 'employee two', 'Cashier', 'Active', '2024-03-22'),
(15, 54, 'Employee three', 'Cook', 'Active', '2024-04-15');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `date_in` date NOT NULL DEFAULT current_timestamp(),
  `exp_date` date NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`product_id`, `product_name`, `product_qty`, `product_price`, `date_in`, `exp_date`, `product_description`, `state`) VALUES
(11, 'product test', 1800, 100, '2024-03-16', '2025-11-12', 'okay', 0),
(12, 'product 3', 11000, 200, '2024-03-16', '2024-05-20', 'test', 1),
(13, 'product 4', 0, 1222, '2024-03-17', '2024-03-26', 'okay', 1),
(14, 'prodcut 23', 23, 3000, '2024-03-19', '2024-04-05', 'okay', 1),
(15, 'product 222', 350, 50, '2024-03-22', '2024-03-27', 'dsds', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` int(11) NOT NULL,
  `item_status` enum('available','not') NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `item_img` text NOT NULL,
  `item_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `category_id`, `item_name`, `item_price`, `item_status`, `item_description`, `item_img`, `item_date`) VALUES
(23, 7, 'item 1', 100, 'available', 'test upload k', '../uploads/Acer_Wallpaper_05_3840x2400.jpg', '2024-03-23'),
(24, 7, 'item 2', 100, 'available', 'test okay k', '../uploads/Acer_Wallpaper_01_3840x2400.jpg', '2024-03-21'),
(25, 7, 'item 3', 600, 'available', 'desccc', '../uploads/Acer_Wallpaper_05_3840x2400.jpg', '2024-03-23'),
(26, 7, 'name', 123, 'not', 'okay', '../uploads/Acer_Wallpaper_02_3840x2400.jpg', '2024-03-23'),
(27, 9, 'namename', 123, 'available', 'des', '../uploads/Acer_Wallpaper_02_3840x2400.jpg', '2024-03-23'),
(28, 8, 'BIBIMBAP MEAL', 65, 'available', 'With Spam', '../uploads/bibimbap1.jpg', '2024-03-29'),
(29, 8, 'BIBIMBAP MEAL', 75, 'available', 'With Pork', '../uploads/bibimbap1.jpg', '2024-03-29'),
(30, 8, 'BIBIMBAP MEAL', 80, 'available', 'With Beef', '../uploads/bibimbap1.jpg', '2024-03-29'),
(31, 8, 'BIBIMBAP MEAL', 65, 'available', 'With Chicken', '../uploads/Acer_Wallpaper_03_3840x2400.jpg', '2024-03-23'),
(32, 8, 'RAMYEON', 75, 'available', 'CLASSIC', '../uploads/Acer_Wallpaper_02_3840x2400.jpg', '2024-03-23'),
(33, 8, 'RAMYEON', 85, 'available', 'CHEESE', '../uploads/Acer_Wallpaper_03_3840x2400.jpg', '2024-03-23'),
(34, 8, 'BAKED SUSHI', 145, 'available', 'SPAM', '../uploads/Acer_Wallpaper_04_3840x2400.jpg', '2024-03-23'),
(35, 8, 'BAKED SUSHI', 135, 'available', 'CALIFORNIA', '../uploads/Acer_Wallpaper_04_3840x2400.jpg', '2024-03-23'),
(36, 11, 'milktea', 178, 'available', 'okay', '../uploads/MAMANGS.png', '2024-03-25');

-- --------------------------------------------------------

--
-- Table structure for table `item_rating`
--

CREATE TABLE `item_rating` (
  `rating_id` int(11) NOT NULL,
  `rating` mediumint(15) NOT NULL,
  `feedback` text NOT NULL,
  `customer_id` bigint(25) NOT NULL,
  `item_id` bigint(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `image_stall` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`category_id`, `category`, `status`, `image_stall`) VALUES
(7, 'K - Street Food', 'available', '../uploads/Screenshot 2024-03-23 175656.png'),
(8, 'K-Corner', 'available', '../uploads/Screenshot 2024-03-23 174757.png'),
(9, 'Centro Bar Pulutan', 'available', '../uploads/Screenshot 2024-03-23 175156.png'),
(10, 'Black Crust Pizza.Co', 'available', '../uploads/Screenshot 2024-03-23 180649.png'),
(11, 'Mamang\'s', 'available', '../uploads/MAMANGS.png'),
(12, 'Mukbang 199', 'available', '../uploads/MUKBANG.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `order_number` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `status`, `order_number`) VALUES
(49, 7, '2024-03-31 20:17:50', 1, 134),
(50, 7, '2024-04-02 10:05:08', 1, 245),
(51, 15, '2024-04-02 20:32:49', 1, 984),
(52, 15, '2024-04-02 20:37:42', 1, 559);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(12,2) NOT NULL,
  `item_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `item_id`, `item_name`, `item_price`, `item_qty`) VALUES
(49, 28, 'BIBIMBAP MEAL-with spam', 65.00, 1),
(49, 29, 'BIBIMBAP MEAL-with pork', 75.00, 2),
(50, 28, 'BIBIMBAP MEAL-with spam', 65.00, 1),
(50, 29, 'BIBIMBAP MEAL-with pork', 75.00, 4),
(51, 28, 'BIBIMBAP MEAL-with spam', 65.00, 1),
(51, 30, 'BIBIMBAP MEAL-with beef', 80.00, 1),
(52, 27, 'namename-des', 123.00, 1),
(52, 34, 'BAKED SUSHI-spam', 145.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_total`
--

CREATE TABLE `order_total` (
  `order_id` bigint(20) NOT NULL,
  `order_total` bigint(20) NOT NULL,
  `total_name` varchar(255) NOT NULL,
  `total_amt` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_total`
--

INSERT INTO `order_total` (`order_id`, `order_total`, `total_name`, `total_amt`) VALUES
(49, 1, 'total', 215.00),
(50, 1, 'total', 365.00),
(51, 1, 'total', 145.00),
(52, 1, 'total', 268.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `account_type`) VALUES
(1, 'admin@global.com', 'admin123', 'admin'),
(2, 'Lau@gmail.com', 'password', 'customer'),
(34, 'employee@one.com', 'password123', 'employee'),
(36, '305649', '123Admin', 'employee'),
(37, 'user1@gmail.com', 'Password@123', 'customer'),
(53, 'lau@user.com', 'Password@123', 'customer'),
(54, '659121', 'testemployee', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`,`item_id`,`customer_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`customer_id`,`item_id`,`cart_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_rating`
--
ALTER TABLE `item_rating`
  ADD PRIMARY KEY (`rating_id`,`customer_id`,`item_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`,`customer_id`),
  ADD KEY `order_date` (`order_date`,`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`item_id`);

--
-- Indexes for table `order_total`
--
ALTER TABLE `order_total`
  ADD PRIMARY KEY (`order_id`,`order_total`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `item_rating`
--
ALTER TABLE `item_rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
