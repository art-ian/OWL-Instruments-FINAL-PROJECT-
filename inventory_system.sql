-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 03:26 PM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `role`, `password`) VALUES
(1, 'superadmin', 'Super Admin', '$2y$10$kdo1Am0CXCeYS10FyZfJde1w0WkWmCMeXedIVy8OCJt6shZw0yQoa'),
(3, 'ADMIN1', 'Admin', '$2y$10$63mQt.FuCcfYhlH1ez62meZxma6BLuTWiWY0DYcV79.Xu2fSEpl3q');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `username`, `activity`, `created_at`) VALUES
(1, 'superadmin', 'Logged In', '2026-07-15 14:56:54'),
(2, 'test_admin', 'Added Admin User', '2026-07-15 14:56:54'),
(3, 'buyer', 'Checkout Completed', '2026-07-15 14:56:54'),
(4, 'Test User', 'Logged In', '2026-07-15 16:40:41'),
(5, 'TESTER', 'Logged In', '2026-07-16 14:50:05'),
(6, 'TESTER', 'Logged In', '2026-07-16 15:21:50'),
(7, 'LAST', 'Logged In', '2026-07-16 16:24:14'),
(8, 'LAST', 'Logged In', '2026-07-16 16:30:44'),
(9, 'test two', 'Logged In', '2026-07-17 12:08:41'),
(10, 'test two', 'Logged In', '2026-07-17 12:27:33'),
(11, 'test two', 'Logged In', '2026-07-17 12:47:26'),
(12, 'test two', 'Logged In', '2026-07-17 13:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `stock`, `image`, `status`) VALUES
(1, 'CT-S300', 'Keyboard', 9500.00, 26, 'casio.jpeg', 'active'),
(2, 'Roland FP-10', 'Piano', 35000.00, 66, 'rolandfp10.jpeg', 'active'),
(3, 'Yamaha F310', 'Guitar', 6500.00, 92, 'yamaha.jpg', 'active'),
(5, 'Marching Drum Red', 'Drums', 14599.00, 45, 'Marching-Drum-Red.jpeg', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `role` enum('buyer','admin') DEFAULT 'buyer',
  `verified` tinyint(1) DEFAULT 0,
  `verify_token` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `address`, `contact`, `role`, `verified`, `verify_token`, `created_at`) VALUES
(2, 'Christine Boja', 'andreaboja25@gmail.com', '$2y$10$f667V9MWj5p4FYWYmpB20OtgSAtilrzhDI4ka9DRiZnhCYFJlLJuK', 'Sampaloc, Manila', '09124383247', 'buyer', 0, NULL, '2026-07-14 21:51:53'),
(3, 'Test User', 'testuser123@gmail.com', '$2y$10$YTRXgHIcHiit7mFyUJZiWu0saKKo6.Z14Hq6xevBs9/NDVMxRvZNS', 'Manila City', '09123456789', 'buyer', 0, NULL, '2026-07-15 15:59:06'),
(4, 'Test User', 'testuser999@gmail.com', '$2y$10$rsb9rJE0iiJ/DhUNmXMex.gK85q.gCxSYMCVQnBDpovdhsb68EEJ6', 'Manila', '09123456789', 'buyer', 0, NULL, '2026-07-15 16:00:00'),
(5, 'TESTER', 'TESTER@gmail.com', '$2y$10$8Ci.Dmi2U6AFdOz.74E5IeXPgX9XpOMPNHjNt/iS7pcCnoFLfgyQe', 'Manila City', '09123456789', 'buyer', 0, NULL, '2026-07-16 14:49:14'),
(6, 'BETA', 'BETA@gmail.com', '$2y$10$QX89mVjRE8L6q7WrFP.Ahud4jW9IrsyyPW9uEyGlng3tQINAdUzyu', 'Japan, Tokyo City', '09123456789', 'buyer', 0, NULL, '2026-07-16 16:12:09'),
(7, 'PAUSE', 'PAUSE@gmail.com', '$2y$10$I/pn.I3kAJaP2bBU5FMvp.wwfz8E/kH6x3dFCaGvCVt3WTa3gX2N2', 'New York City', '0915555555', 'buyer', 0, 'c4a452df008935cc34ca8565e23f0002aec42c482ab005862c7cc9884ce971be', '2026-07-16 16:14:56'),
(8, 'LAST', 'LAST@gmail.com', '$2y$10$zga6jmr7hZWl8kcz7CPNq.Ifi7GANDHsfPXfLrzm./uwf7RNz0bRy', 'Bonifacio Global City', '09121212121212', 'buyer', 1, NULL, '2026-07-16 16:23:00'),
(9, 'test two', 'test2@gmail.com', '$2y$10$J83AZw7uDPeKm6rCqTm3NOrhhaMev2CDZEBsFZgodQTjfrDqCL7Ui', 'Pasay city', '094545454545', 'buyer', 1, NULL, '2026-07-17 12:08:05'),
(10, 'SCREEN', 'SCREEN@gmail.com', '$2y$10$sHDOhWMT8amOegLqXdc1PepGXHvBGU/SDn3ngHQJ.98KOJ8FTufzq', 'SCREEN', '0934347878', 'buyer', 1, NULL, '2026-07-17 13:08:48');

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
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
