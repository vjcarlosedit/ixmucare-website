-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 28, 2024 at 11:40 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `res_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `role`) VALUES
(1, 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `id` int(11) NOT NULL,
  `res_id` int(11) DEFAULT NULL,
  `item_name` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `madeby` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `food_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cuisine_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`id`, `res_id`, `item_name`, `madeby`, `food_type`, `cuisine_type`, `price`, `image`) VALUES
(48, 1, 'Rice', 'Cheff Don', 'Main Cuisine', 'Srilankan', 1200, '11.jpeg'),
(49, 1, 'Ice Cream Vanila', 'Mathew', 'Dessert', '', 500, '9.jpeg'),
(50, 1, 'Noodles', 'Cheff Chan', 'Main Cuisine', 'Chinese', 700, '7.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `send_to` varchar(255) DEFAULT NULL,
  `user_del_status` int(11) NOT NULL DEFAULT 0,
  `is_read` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `admin_id`, `user_name`, `user_email`, `message`, `created_at`, `send_to`, `user_del_status`, `is_read`) VALUES
(26, 22, NULL, 'amara', 'a@gmail.com', 'test', '2024-10-27 02:39:17', 'admin@gmail.com', 9, 0),
(27, NULL, 1, 'admin', 'admin@gmail.com', 'ooosnjdnj sjdjsnd', '2024-10-27 02:39:37', 'a@gmail.com', 9, 1),
(28, NULL, 1, 'admin', 'admin@gmail.com', 'not show', '2024-10-27 02:49:20', 'a@gmail.com', 9, 0),
(29, 22, NULL, 'amara', 'a@gmail.com', 'yt', '2024-10-27 02:55:10', NULL, 0, 0),
(30, NULL, 1, 'admin', 'admin@gmail.com', 'iiuu', '2024-10-27 02:55:39', 'a@gmail.com', 9, 0),
(31, 23, NULL, 'user1', 'user1@gmail.com', 'Hi change my phone number as 011223344.', '2024-10-28 09:41:54', NULL, 0, 0),
(32, NULL, 1, 'admin', 'admin@gmail.com', 'ok', '2024-10-28 09:42:17', 'user1@gmail.com', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `username`, `email`, `total_price`, `order_date`, `status`) VALUES
(12, 23, 'user1', 'user1@gmail.com', 2400.00, '2024-10-28 08:56:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `price`, `quantity`) VALUES
(16, 12, 'Rice', 1200.00, 1),
(17, 12, 'Noodles', 700.00, 1),
(18, 12, 'Ice Cream Vanila', 500.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `guests` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` tinyint(4) DEFAULT 0,
  `approvedBy` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `table_id`, `name`, `email`, `phone`, `guests`, `date`, `time`, `status`, `approvedBy`) VALUES
(7, 25, 'user1', 'user1@gmail.com', '01123344556', 5, '2024-10-30', '01:30:00', 1, 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tables`
--

CREATE TABLE `restaurant_tables` (
  `id` int(11) NOT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `chair_count` int(11) NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `restaurant_tables`
--

INSERT INTO `restaurant_tables` (`id`, `table_name`, `chair_count`, `status`) VALUES
(25, 'TBL-01', 5, 1),
(26, 'TBL-02', 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `empId` int(11) NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jobTitle` varchar(50) DEFAULT NULL,
  `mobileNo` varchar(15) DEFAULT NULL,
  `addr` varchar(255) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`empId`, `firstName`, `lastName`, `email`, `jobTitle`, `mobileNo`, `addr`, `nic`, `dob`, `password`, `photo`, `status`) VALUES
(8, 'amal', 'per', 'a@gmail.com', 'cashier', '09999', 'col', '22222v', '1998-12-02', '+ZsjNuUCsVy5d4EV8ZJRFrb9467Jk2UVYAT+SpPNOCQUoXezu3cdhcoNEhpOc7sEjZvEiEetmPGeTjnxqjfZqQ==', 'staff-image/671da2cf732f6.jpg', 1),
(9, 'ss', 'ss', 'ss@gmail.com', 'cashier', '0111', 'colombo', '12345678V', '1990-10-21', 'gqFy4qtzNJ5VyDEqCtuYncs63ApN0Kx9dlzQQRnXQjgD8ZUK9Cwwk4zyWGIdwrMkt0I6JRLgexF56S9Ra8x/tg==', 'staff-image/671f45eb088e2.jpeg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `address`, `gender`, `password`, `created_at`, `status`, `role`) VALUES
(22, 'amara', 'a@gmail.com', '0112929234', 'colombo', 'Male', '2+mWTzIIyuVlhh/fRrtXY1tTIBhf2dgq8HQqt15FbXy+J5A85zDdg7nzRA1fQeR7GyNCa+//UjWuwOq95/YRvA==', '2024-10-27 02:38:37', 1, 'user'),
(23, 'user1', 'user1@gmail.com', '0112233441', 'Colombo', 'Male', 'j5vMPxpdJXcRAjhUttUyswxt5FFq/CIlfoT4GqZsMq+kqM6FHWYHu34jdA8tUvWpbZADcGO+U5d+rNOeGax5wQ==', '2024-10-28 07:48:11', 1, 'user'),
(24, 'test', 'test@gmail.com', '0113833333', 'Colombo', 'Male', 'KkmeipWPY+ll7ID6klMkj5c3VEYHn/ukWWUJ5zZSRvnCQ3HuAEip//iQAj2UgVb/hqHIr5JM8VNo9fXLmgnB4A==', '2024-10-28 08:05:17', 0, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_admin` (`admin_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`empId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `empId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
