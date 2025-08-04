-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2024 at 03:13 AM
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
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `cart_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `item_id`, `customer_id`, `order_date`, `cart_status`) VALUES
(41, 23, 1, '2024-05-16 08:55:58', 1),
(42, 28, 1, '2024-05-16 08:56:08', 1),
(43, 23, 7, '2024-05-16 08:56:55', 1),
(44, 28, 7, '2024-05-16 08:57:08', 1),
(45, 23, 1, '2024-05-16 09:05:38', 0),
(46, 28, 1, '2024-05-16 09:06:14', 0),
(47, 27, 1, '2024-05-16 09:06:28', 0),
(48, 23, 1, '2024-05-16 09:08:06', 0),
(49, 29, 1, '2024-05-16 09:08:29', 0),
(50, 30, 1, '2024-05-16 09:09:05', 0),
(51, 29, 1, '2024-05-16 09:09:31', 0),
(52, 23, 1, '2024-05-16 09:09:58', 0),
(53, 36, 1, '2024-05-16 09:10:17', 0);

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
  `cart_id` bigint(20) NOT NULL,
  `item_void` smallint(6) NOT NULL DEFAULT 0,
  `ordered_item` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`name`, `qty`, `price`, `customer_id`, `item_id`, `cart_id`, `item_void`, `ordered_item`) VALUES
('item 1-test upload k', 3, 100, 1, 23, 41, 1, 1),
('item 1-test upload k', 3, 100, 1, 23, 45, 1, 0),
('item 1-test upload k', 1, 100, 1, 23, 48, 1, 0),
('item 1-test upload k', 1, 100, 1, 23, 52, 1, 0),
('namename-des', 1, 123, 1, 27, 47, 1, 0),
('BIBIMBAP MEAL-with spam', 1, 65, 1, 28, 42, 1, 1),
('BIBIMBAP MEAL-with spam', 1, 65, 1, 28, 46, 1, 0),
('BIBIMBAP MEAL-with pork', 2, 75, 1, 29, 49, 1, 0),
('BIBIMBAP MEAL-with pork', 2, 75, 1, 29, 51, 1, 0),
('BIBIMBAP MEAL-with beef', 2, 80, 1, 30, 50, 1, 0),
('milktea-okay', 1, 178, 1, 36, 53, 1, 0),
('item 1-test upload k', 3, 100, 7, 23, 43, 1, 1),
('BIBIMBAP MEAL-with spam', 1, 65, 7, 28, 44, 1, 1);

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
(15, 53, 'lau', '2001-12-12', 'Male', '2024-03-31 18:26:44'),
(16, 55, 'John Laurence Fernandez', '2001-11-21', 'Male', '2024-05-09 19:42:43'),
(17, 56, 'Maria Theresa Bildan', '2001-07-13', 'Male', '2024-05-09 19:56:51'),
(18, 57, 'Testing One', '2001-12-21', 'Male', '2024-05-09 20:00:21'),
(19, 61, 'Testing Two', '2001-02-21', 'Female', '2024-05-09 20:07:17');

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
(36, 11, 'milktea', 178, 'available', 'okay', '../uploads/MAMANGS.png', '2024-03-25'),
(37, 11, 'okay na okay', 120, 'available', 'test item add', '../uploads/Screenshot 2024-03-29 154906.png', '2024-04-27');

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
(80, 7, '2024-05-16 08:57:13', 0, 199),
(81, 1, '2024-05-16 09:00:24', 0, 605);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(12,2) NOT NULL,
  `item_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `item_name`, `item_price`, `item_qty`) VALUES
(12, 80, 23, 'item 1-test upload k', 100.00, 1),
(13, 80, 28, 'BIBIMBAP MEAL-with spam', 65.00, 1),
(14, 80, 23, 'item 1-test upload k', 100.00, 1),
(15, 80, 28, 'BIBIMBAP MEAL-with spam', 65.00, 1),
(16, 81, 23, 'item 1-test upload k', 100.00, 5),
(17, 81, 28, 'BIBIMBAP MEAL-with spam', 65.00, 1);

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
(80, 1, 'total', 330.00),
(81, 1, 'total', 565.00);

-- --------------------------------------------------------

--
-- Table structure for table `otc`
--

CREATE TABLE `otc` (
  `otc_id` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `otc_date` datetime DEFAULT current_timestamp(),
  `otc_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otc`
--

INSERT INTO `otc` (`otc_id`, `item_id`, `employee_id`, `otc_date`, `otc_status`) VALUES
(3, 23, 12, '2024-04-27 14:11:40', 1),
(4, 28, 12, '2024-04-28 09:15:06', 1),
(5, 28, 12, '2024-04-28 09:15:48', 1),
(6, 31, 12, '2024-04-28 09:18:43', 1),
(7, 27, 12, '2024-04-28 09:37:42', 1),
(8, 36, 12, '2024-04-28 09:39:36', 1),
(9, 28, 12, '2024-04-28 17:46:42', 0),
(10, 29, 12, '2024-04-28 17:46:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `otc_item`
--

CREATE TABLE `otc_item` (
  `item_name` varchar(255) NOT NULL,
  `item_price` bigint(20) NOT NULL,
  `otc_qty` bigint(20) NOT NULL,
  `item_desc` text NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `otc_void` tinyint(4) NOT NULL DEFAULT 0,
  `otc_order` tinyint(4) NOT NULL DEFAULT 0,
  `otc_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otc_item`
--

INSERT INTO `otc_item` (`item_name`, `item_price`, `otc_qty`, `item_desc`, `item_id`, `employee_id`, `otc_void`, `otc_order`, `otc_id`) VALUES
('item 1', 100, 1, 'test upload k', 23, 12, 0, 1, 3),
('BIBIMBAP MEAL', 65, 2, 'With Spam', 28, 12, 0, 1, 5),
('BIBIMBAP MEAL', 65, 1, 'With Chicken', 31, 12, 0, 1, 6),
('namename', 123, 1, 'des', 27, 12, 0, 1, 7),
('milktea', 178, 1, 'okay', 36, 12, 0, 1, 8),
('BIBIMBAP MEAL', 65, 2, 'With Spam', 28, 12, 0, 0, 9),
('BIBIMBAP MEAL', 75, 1, 'With Pork', 29, 12, 0, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `over_items`
--

CREATE TABLE `over_items` (
  `over_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `over_name` text NOT NULL,
  `over_price` decimal(12,2) NOT NULL,
  `over_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `over_items`
--

INSERT INTO `over_items` (`over_id`, `item_id`, `over_name`, `over_price`, `over_qty`) VALUES
(2, 3, 'item 1test upload k', 100.00, 1),
(2, 5, 'BIBIMBAP MEALWith Spam', 65.00, 2),
(2, 6, 'BIBIMBAP MEALWith Chicken', 65.00, 1),
(2, 7, 'namenamedes', 123.00, 1),
(2, 8, 'milkteaokay', 178.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `over_orders`
--

CREATE TABLE `over_orders` (
  `over_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `over_date` datetime NOT NULL DEFAULT current_timestamp(),
  `over_status` tinyint(4) NOT NULL DEFAULT 0,
  `over_number` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `over_orders`
--

INSERT INTO `over_orders` (`over_id`, `employee_id`, `over_date`, `over_status`, `over_number`) VALUES
(2, 12, '2024-04-28 10:31:35', 1, 793);

-- --------------------------------------------------------

--
-- Table structure for table `over_total`
--

CREATE TABLE `over_total` (
  `over_id` bigint(20) NOT NULL,
  `over_total` text NOT NULL,
  `over_tname` text NOT NULL,
  `over_tamt` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `over_total`
--

INSERT INTO `over_total` (`over_id`, `over_total`, `over_tname`, `over_tamt`) VALUES
(2, '1', 'total', 596.00);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `ratings` mediumint(5) NOT NULL,
  `review` text NOT NULL,
  `order_number` int(25) NOT NULL,
  `order_id` bigint(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `email_ver_num` bigint(20) NOT NULL,
  `email_verified` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `account_type`, `email_ver_num`, `email_verified`) VALUES
(1, 'admin@global.com', 'admin123', 'admin', 0, 0),
(2, 'Lau@gmail.com', 'password', 'customer', 0, 1),
(34, 'employee@one.com', 'password123', 'employee', 0, 0),
(36, '305649', '123Admin', 'employee', 0, 0),
(37, 'user1@gmail.com', 'Password@123', 'customer', 0, 1),
(53, 'lau@user.com', 'Password@123', 'customer', 0, 0),
(54, '659121', 'testemployee', 'employee', 0, 0),
(55, 'lauvfernandez0@gmal.com', 'Laurence@21', 'customer', 123456, 0),
(56, 'lauvfernandez0@gmail.com', 'Passwordko@123', 'customer', 0, 0),
(57, 'pilosopo1999@gmail.com', 'Passwordko@123', 'customer', 0, 0),
(58, '', '', '', 263893, 0),
(59, 'lauv012113@gmail.com', 'Passwordtesting@123', 'customer', 0, 0),
(60, 'lauv012113+new@gmail.com', 'Passwordtesting@123', 'customer', 0, 0),
(61, 'lauv012113+new1@gmail.com', 'Passwordko@123', 'customer', 715214, 1);

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
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_total`
--
ALTER TABLE `order_total`
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `otc`
--
ALTER TABLE `otc`
  ADD PRIMARY KEY (`otc_id`,`item_id`,`employee_id`);

--
-- Indexes for table `otc_item`
--
ALTER TABLE `otc_item`
  ADD PRIMARY KEY (`otc_id`);

--
-- Indexes for table `over_items`
--
ALTER TABLE `over_items`
  ADD PRIMARY KEY (`over_id`,`item_id`);

--
-- Indexes for table `over_orders`
--
ALTER TABLE `over_orders`
  ADD PRIMARY KEY (`over_id`);

--
-- Indexes for table `over_total`
--
ALTER TABLE `over_total`
  ADD PRIMARY KEY (`over_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`) USING BTREE,
  ADD KEY `customer_id` (`customer_id`,`item_id`);

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
  MODIFY `cart_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `otc`
--
ALTER TABLE `otc`
  MODIFY `otc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `over_orders`
--
ALTER TABLE `over_orders`
  MODIFY `over_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
