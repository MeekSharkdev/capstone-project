-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2025 at 09:39 AM
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
-- Table structure for table `request_reschedule`
--

CREATE TABLE `request_reschedule` (
  `id` int NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `request_reschedule`
--

INSERT INTO `request_reschedule` (`id`, `firstname`, `middlename`, `lastname`, `email`, `date`, `purpose`, `reason`) VALUES
(6, 'darwin', 'hans', 'abudanza', 'abudanza@gmail.com', '2025-01-27', 'brgy_permit', 'nagkamali ng pindot ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_reschedule`
--
ALTER TABLE `request_reschedule`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_reschedule`
--
ALTER TABLE `request_reschedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
