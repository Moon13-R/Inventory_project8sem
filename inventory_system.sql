-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 06:34 AM
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
-- Database: `inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(12, 'Books & Stationery'),
(27, 'Cleaning Products'),
(10, 'Clothing & Apparel'),
(1, 'Demo Category'),
(9, 'Electronics'),
(3, 'Finished Goods'),
(13, 'Food & Beverages'),
(11, 'Footwear'),
(14, 'Furniture'),
(22, 'Gardening Supplies'),
(18, 'Health & Beauty Products'),
(21, 'Home Decor'),
(19, 'Jewelry & Accessories'),
(15, 'Kitchenware'),
(5, 'Machinery'),
(25, 'Musical Instruments'),
(23, 'Office Supplies'),
(4, 'Packing Materials'),
(26, 'Pet Supplies'),
(2, 'Raw Materials'),
(17, 'Sports Equipment'),
(8, 'Stationery Items'),
(20, 'Tools & Hardware'),
(16, 'Toys & Games'),
(28, 'Travel & Luggage'),
(24, 'Vehicles & Auto Parts'),
(6, 'Work in Progress');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`, `file_path`) VALUES
(9, '1759768058_coca-cola-1lt.jpg', 'jpg', ''),
(17, '1761546925_Potato - Stylized Artistic.png', 'png', ''),
(18, '1761546985_Vim Dishwash Liquid 500Ml.png', 'png', ''),
(19, '1761547042_Sunflower Oil Bottle Mockup.png', 'png', ''),
(20, '1761547180_McTamura Products.png', 'png', ''),
(21, '1761547233_emotion ???.png', 'png', ''),
(22, '1761547294_Chloroxylenol Hand Washing Soap Cosmetics PNG.png', 'png', ''),
(23, '1761547353_Ben and Jerrys ice cream.png', 'png', ''),
(24, '1761547404_3385576c-93fc-441c-8452-4ccd69e1c397.png', 'png', ''),
(25, '1761547459_18 Amazing Hair and Makeup Products You Need This Summer.png', 'png', ''),
(26, '1761627052_2 PM Akbare Chicken Noodles - Box of 20 x 100gm Packs.png', 'png', ''),
(27, '1761627108_10 Delicious Desserts Featuring Marie Biscuits – Easy and Quick!.png', 'png', ''),
(28, '1761627165_954e5c5b-f716-407a-8502-51b66f03032b.png', 'png', ''),
(29, '1761627211_bd8f5055-1b32-4c6b-9c79-fbe933a75ac3.png', 'png', ''),
(30, '1761627249_Coca cola.png', 'png', ''),
(31, '1761627319_Jolly Rancher Original 198gr - die Klassiker unter den Bonbons.png', 'png', ''),
(32, '1761627370_Soya Chunks (High Protien) - 31oz (1_9lbs) 900g - Rani Brand Authentic Indian Products.png', 'png', ''),
(33, '1761627425_With the bag cut when it gets empty hahaha.png', 'png', ''),
(34, '1761664645_10 Delicious Desserts Featuring Marie Biscuits – Easy and Quick!.png', 'png', ''),
(35, '1763029311_logo.png', 'png', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `upc` varchar(20) DEFAULT NULL,
  `value_size` varchar(20) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `sale_price` int(11) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED DEFAULT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `units_in_case` int(11) DEFAULT 0,
  `case_cost` decimal(10,2) DEFAULT 0.00,
  `unit_cost` decimal(10,2) DEFAULT 0.00,
  `case_retail` decimal(10,2) DEFAULT 0.00,
  `unit_retail` decimal(10,2) DEFAULT 0.00,
  `gpm` decimal(5,2) DEFAULT 0.00,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `upc`, `value_size`, `quantity`, `sale_price`, `categorie_id`, `vendor_id`, `media_id`, `date`, `units_in_case`, `case_cost`, `unit_cost`, `case_retail`, `unit_retail`, `gpm`, `photo`) VALUES
(41, 'potato ', '1101', '10kg', '4', 0, 13, 15, 17, '2025-10-27 07:35:25', 1, 100.00, 100.00, 120.00, 120.00, 16.67, '1761546925_Potato - Stylized Artistic.png'),
(42, 'Vim Dishwasher Liquid', '1102', '0.5l', '9', 0, 27, 2, 18, '2025-10-27 07:36:25', 1, 120.00, 120.00, 150.00, 150.00, 20.00, '1761546985_Vim Dishwash Liquid 500Ml.png'),
(43, 'Sunflower oil bottle ', '1103', '1l', '0', 0, 13, 9, 19, '2025-10-27 07:37:22', 10, 1200.00, 120.00, 1500.00, 150.00, 20.00, '1761547042_Sunflower Oil Bottle Mockup.png'),
(45, 'Shin Ramyeon', '1104', '5', '0', 0, 13, 5, 20, '2025-10-27 07:39:40', 5, 1250.00, 250.00, 1350.00, 270.00, 7.41, '1761547180_McTamura Products.png'),
(46, 'Wai wai noodles', '1105', '25 packet', '1', 0, 13, 4, 21, '2025-10-27 07:40:33', 25, 450.00, 18.00, 500.00, 20.00, 10.00, '1761547233_emotion ???.png'),
(47, 'Dettol hand wash ', '1106', '1.2l', '4', 0, 18, 9, 22, '2025-10-27 07:41:34', 1, 120.00, 120.00, 150.00, 150.00, 20.00, '1761547294_Chloroxylenol Hand Washing Soap Cosmetics PNG.png'),
(48, 'Ben and jery ice cream', '1107', '20gram', '0', 0, 13, 10, 23, '2025-10-27 07:42:33', 20, 1100.00, 55.00, 1200.00, 60.00, 8.33, '1761547353_Ben and Jerrys ice cream.png'),
(49, 'Sunsilk Shampoo', '1108', '500gram', '19', 0, 18, 10, 24, '2025-10-27 07:43:24', 1, 120.00, 120.00, 150.00, 150.00, 20.00, '1761547404_3385576c-93fc-441c-8452-4ccd69e1c397.png'),
(50, 'Dove body soap', '1109', '1', '4', 0, 18, 10, 25, '2025-10-27 07:44:19', 1, 800.00, 800.00, 85.00, 85.00, -841.18, '1761547459_18 Amazing Hair and Makeup Products You Need This Summer.png'),
(51, '2PM akbare chicken noodles 100gm', '1201', '100 gm', '1', 0, 13, 8, 26, '2025-10-28 05:50:52', 10, 1400.00, 140.00, 1700.00, 170.00, 17.65, '1761627052_2 PM Akbare Chicken Noodles - Box of 20 x 100gm Packs.png'),
(52, 'Marie Bisvcuits', '1202', '50 gram', '1', 0, 13, 15, 27, '2025-10-28 05:51:48', 12, 120.00, 10.00, 144.00, 12.00, 16.67, '1761627108_10 Delicious Desserts Featuring Marie Biscuits – Easy and Quick!.png'),
(53, 'Coca cola can 1 l', '1301', '1', '1', 0, 13, 1, 28, '2025-10-28 05:52:45', 12, 1500.00, 125.00, 1560.00, 130.00, 3.85, '1761627165_954e5c5b-f716-407a-8502-51b66f03032b.png'),
(54, 'Red bull  energy drik', '1302', '1', '1', 0, 13, 9, 29, '2025-10-28 05:53:31', 12, 120.00, 10.00, 1560.00, 130.00, 92.31, '1761627211_bd8f5055-1b32-4c6b-9c79-fbe933a75ac3.png'),
(55, 'Coca cola 1.5 l bottle', '1303', '1.5 l', '0', 0, 13, 1, 30, '2025-10-28 05:54:09', 1, 130.00, 130.00, 135.00, 135.00, 3.70, '1761627249_Coca cola.png'),
(56, 'Jolly rancher candy ', '1401', '180 gram', '1', 0, 13, 8, 31, '2025-10-28 05:55:19', 12, 150.00, 12.50, 180.00, 15.00, 16.67, '1761627319_Jolly Rancher Original 198gr - die Klassiker unter den Bonbons.png'),
(57, 'RANI Soya chucnck', '1305', '31OZ', '0', 0, 13, 10, 32, '2025-10-28 05:56:10', 1, 80.00, 80.00, 85.00, 85.00, 5.88, '1761627370_Soya Chunks (High Protien) - 31oz (1_9lbs) 900g - Rani Brand Authentic Indian Products.png'),
(58, 'Lays classic chips', '1403', '80 gram', '1', 0, 13, 10, 33, '2025-10-28 05:57:05', 1, 115.00, 115.00, 120.00, 120.00, 4.17, '1761627425_With the bag cut when it gets empty hahaha.png'),
(59, 'one', '65465454654654', '33', '12', 0, 27, 2, 34, '2025-10-28 16:17:25', 32, 234.00, 7.31, 10976.00, 343.00, 97.87, '1761664645_10 Delicious Desserts Featuring Marie Biscuits – Easy and Quick!.png'),
(60, 'Tshirt', '45866', '1', '1', 0, 5, 13, 35, '2025-11-13 11:21:51', 1, 1.00, 1.00, 12.00, 12.00, 91.67, '1763029311_logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `returned_products`
--

CREATE TABLE `returned_products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `upc` varchar(20) DEFAULT NULL,
  `value_size` varchar(20) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `units_in_case` int(11) NOT NULL DEFAULT 1,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `case_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_retail` decimal(10,2) NOT NULL DEFAULT 0.00,
  `case_retail` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gpm` decimal(5,2) NOT NULL DEFAULT 0.00,
  `categorie_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `photo` varchar(255) DEFAULT '',
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returned_products`
--

INSERT INTO `returned_products` (`id`, `name`, `upc`, `value_size`, `quantity`, `units_in_case`, `unit_cost`, `case_cost`, `unit_retail`, `case_retail`, `gpm`, `categorie_id`, `vendor_id`, `media_id`, `photo`, `date`, `return_date`) VALUES
(1, 'Shoe', NULL, NULL, 10, 5, 200.00, 1000.00, 300.00, 1500.00, 33.33, 1, 1, 0, '', '2025-10-26 13:22:58', '2025-10-28 11:26:06'),
(2, 'Small Bubble Cushioning Wrap', '45222', '1', 100, 1, 100.00, 100.00, 150.00, 150.00, 33.33, 3, 3, 0, '', '2025-10-27 05:06:50', '2025-10-28 11:26:06'),
(3, 'Bulb light', '45666', '1', 1, 1, 1.00, 1.00, 2.00, 2.00, 50.00, 27, 1, 0, '', '2025-10-28 06:34:14', '2025-10-28 11:26:06'),
(4, 'DELL CPU 16GB', '7441', '1', 1, 1, 100.00, 100.00, 120.00, 120.00, 16.67, 25, 15, 0, '', '2025-11-13 11:20:59', '2025-11-13 16:05:59'),
(5, 'Shin Ramyeon', '152888', '1', 1, 4, 0.25, 1.00, 0.50, 2.00, 50.00, 19, 13, 36, '1763029353_169503693_811c9396-eb89-429e-975c-6da47d22a71a.jpg', '2025-11-13 11:22:33', '2025-11-13 16:07:33');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `upc` varchar(20) DEFAULT NULL,
  `value_size` varchar(50) DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `unit_retail` decimal(10,2) DEFAULT NULL,
  `gpm` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `total`, `date`, `product_name`, `upc`, `value_size`, `unit_cost`, `unit_retail`, `gpm`, `created_at`) VALUES
(3, 47, 1, 150.00, 150.00, '2025-11-03', 'Dettol hand wash ', '1106', '1.2l', 120.00, 150.00, 20.00, '2025-11-03 07:27:36'),
(4, 41, 1, 120.00, 120.00, '2025-11-03', 'potato ', '1101', '10kg', 100.00, 120.00, 16.67, '2025-11-03 07:39:27'),
(5, 42, 1, 150.00, 150.00, '2025-11-03', 'Vim Dishwasher Liquid', '1102', '0.5l', 120.00, 150.00, 20.00, '2025-11-03 07:39:36'),
(6, 45, 1, 270.00, 270.00, '2025-11-03', 'Shin Ramyeon', '1104', '5', 250.00, 270.00, 7.41, '2025-11-03 07:39:50'),
(7, 48, 1, 60.00, 60.00, '2025-11-03', 'Ben and jery ice cream', '1107', '20gram', 55.00, 60.00, 8.33, '2025-11-03 07:39:55'),
(8, 50, 1, 85.00, 85.00, '2025-11-03', 'Dove body soap', '1109', '1', 800.00, 85.00, -841.18, '2025-11-03 07:40:07'),
(9, 55, 1, 135.00, 135.00, '2025-11-03', 'Coca cola 1.5 l bottle', '1303', '1.5 l', 130.00, 135.00, 3.70, '2025-11-03 07:40:11'),
(10, 41, 5, 120.00, 600.00, '2025-11-03', 'potato ', '1101', '10kg', 100.00, 120.00, 16.67, '2025-11-03 07:40:37'),
(11, 57, 1, 85.00, 85.00, '2025-11-03', 'RANI Soya chucnck', '1305', '31OZ', 80.00, 85.00, 5.88, '2025-11-03 07:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Arunima', 'Admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.png', 1, '2025-11-16 12:55:37'),
(6, 'Rima ', 'Rima ', '352cfe9017e00637d6687fa39f1bd8e47c470859', 3, '2dv0lmj6.jpg', 1, '2025-10-06 06:49:54'),
(7, 'Burno', 'Burno', '7822c813a6708e42bdf82f4e4b19568d6a1aaf94', 2, '2zgbqhn67.jpg', 1, '2025-10-06 06:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'special', 2, 1),
(3, 'User', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `contact`, `address`) VALUES
(1, 'Coca cola', '9818043504', 'boudha'),
(2, 'United Distributors (Nepal) Pvt. Ltd. (UDN)', '9800000000', 'kathmandu newroad'),
(3, 'pepsi', '9800000000', 'kathamandu , pepsicola'),
(4, 'CG Foods / Chaudhary Group distribution arms', '98000000000', 'biratnagar '),
(5, 'KWALITY / Related FMCG distributors (local manufacturing/distribution partners)', '98000000000', 'lalaitpu'),
(6, 'Vishal Group', '98000000000', 'bhaktapur'),
(7, 'Nepal Overseas Marketing Co. Pvt. Ltd.', '98000000000', 'dhading'),
(8, 'Asia Trade International Pvt Ltd (“ATI Nepal”)', '98000000000', 'nuwakot'),
(9, 'Shanker Group', '98000000000', 'sindhupalchowk'),
(10, 'Bhatbhatine supermarket', '98000000000', 'Kathmandu'),
(11, 'Himalayan Natural Food Product &amp; Export Pvt. Ltd', '98000000000', 'kathamandu'),
(12, 'Sherpa Foods', '98000000000', 'solokhumbhu'),
(13, 'Krishna Pauroti Bhandar', '98000000000', 'lalitpur'),
(14, 'Goldstar shoe ', '98000000000', 'ktm, baluwatar'),
(15, 'Good Life Food Product', '98000000000', 'kathmandu'),
(16, 'Vijay Distillery / Jawalakhel Group of Industries (JGI)', '98000000000', 'dolakha'),
(17, 'Nepal Dairy Pvt. Ltd.', '98000000000', 'bhaktapur'),
(18, 'Shree Pashupati Biscuit Industries', '98000000000', ''),
(19, 'Sujal Foods (Pvt.) Ltd', '98000000000', ''),
(20, 'Gandaki Noodles (P.) Ltd', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `unique_upc` (`upc`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `FK_products_vendor` (`vendor_id`);

--
-- Indexes for table `returned_products`
--
ALTER TABLE `returned_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_upc` (`upc`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `returned_products`
--
ALTER TABLE `returned_products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_products_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
