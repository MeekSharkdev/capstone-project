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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `event_location` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_archived` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `event_time`, `event_location`, `event_description`, `event_image`, `created_at`, `is_archived`) VALUES
(116, 'test 3 ', '2025-02-08', '01:00:00', 'test', 'test', '679478b78d33e.png', '2025-01-25 05:37:59', 1),
(117, 'test 4 ', '2025-02-05', '02:00:00', 'test', 'test', '679478d2ae021.png', '2025-01-25 05:38:26', 1),
(118, 'test 5 ', '2025-03-26', '01:00:00', 'fsdh', 'test', '679478e8776e0.png', '2025-01-25 05:38:48', 1),
(119, 'foundation day of bgry silangan', '2025-03-19', '06:00:00', 'quezon city hall', 'Tayo ay Magsama - sama Para sa Ating Pag - Gunita ng Araw ng Brgy. Silangan.', '67948e09c4f0e.png', '2025-01-25 07:08:57', 1),
(120, 'Dimsum Making', '2025-01-31', '09:00:00', 'Barangay Hall', 'test', '67973a0eacfe3.png', '2025-01-27 07:47:26', 1),
(122, 'testing again', '2025-01-31', '15:53:00', 'testing ', 'test', '67973b9c8f14e.png', '2025-01-27 07:54:04', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
