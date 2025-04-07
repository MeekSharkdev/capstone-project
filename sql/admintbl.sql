-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2025 at 09:38 AM
-- Server version: 8.0.32
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbusers`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintbl`
--

CREATE TABLE `admintbl` (
  `id` int NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `phonenumber` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int NOT NULL,
  `sex` varchar(10) NOT NULL,
  `role` varchar(50) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `admintbl`
--

INSERT INTO `admintbl` (`id`, `firstname`, `middlename`, `lastname`, `phonenumber`, `email`, `birthdate`, `age`, `sex`, `role`, `username`, `password`, `created_at`) VALUES
(2, 'ihlon gyve', '', 'nogot', '09106603019', 'ihlongyve@gmail.com', '2001-12-24', 23, 'Male', 'developer', 'meekdev', '$2y$10$duGSpnhRdPUGfEL5iu7JpeT9ha6XnKv.KCH8CNV2nFNNvbR1paQpW', '2024-12-18 06:35:24'),
(12, 'hanz', 'm', 'malaza', '09291145382', 'hanz@gmail.com', '2001-02-12', 23, 'Male', 'Developer', 'hanz', '$2y$10$pM5.kD5r5vE3IgCOPvXZjuzLL2nAqtsVSNOYldBmrV9XaSG59KclW', '2025-01-22 03:20:43'),
(13, 'sharlene', 'amaro', 'senar', '09772308609', 'sharlenesenar.1999@gmail.com', '1999-01-19', 26, 'Female', 'Developer', 'shar1999', '$2y$10$.LtUQwKNS47XmSU5Q7NLO.PccooL.2JE8QzwFgLEtN7TcG8gBiHFe', '2025-01-22 14:08:58'),
(14, 'whim', 'm', 'dancil', '09291143792', 'whim@gmail.com', '2002-01-01', 23, 'Female', 'Developer', 'whim', '$2y$10$s7PFJ4QXW9b.Yi0uacPpX.JdHGqSAmO9hZpOVd2fnuEKZyXkCfnI6', '2025-01-23 17:51:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admintbl`
--
ALTER TABLE `admintbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admintbl`
--
ALTER TABLE `admintbl`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
