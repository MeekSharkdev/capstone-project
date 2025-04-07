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
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `bookings_id` int NOT NULL,
  `booking_date` date NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phonenumber` varchar(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `purpose` enum('Barangay Clearance','Barangay Indigency','Barangay Permit') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookings_id`, `booking_date`, `firstname`, `lastname`, `phonenumber`, `email`, `purpose`) VALUES
(1, '2025-04-05', 'ihlon', 'nogot', '09291123792', 'ihlongyve@gmail.com', 'Barangay Indigency'),
(2, '2025-02-06', 'meek', 'gyve', '09195504010', 'ihlongyve@gmail.com', 'Barangay Permit'),
(3, '2024-12-28', 'whim', 'dancil', '09195503019', 'whim@gmail.com', 'Barangay Clearance'),
(4, '2024-12-13', 'shar', 'lene', '09104403017', 'shar@gmail.com', 'Barangay Clearance'),
(5, '2024-12-11', 'hanz', 'malaza', '09291124992', 'hanz@gmail.com', 'Barangay Indigency'),
(6, '2025-01-31', 'ihlon', 'nogot', '09106603019', 'ihlon@gmail.com', 'Barangay Clearance'),
(7, '2024-12-31', 'ihlon', 'nogot', '09106603019', 'ihlon@gmail.com', 'Barangay Clearance'),
(8, '2024-12-16', 'whim', 'dancil', '09291153792', 'whim@gmail.com', 'Barangay Permit'),
(9, '2025-01-22', 'jerich', 'nogot', '09105503018', 'jerich@gmail.com', 'Barangay Clearance'),
(10, '2025-01-30', 'darwin', 'abudanza', '09291137492', 'abudanza@gmail.com', 'Barangay Indigency'),
(11, '2025-01-17', 'hans', 'malaza', '09291154792', 'hanz@gmail.com', 'Barangay Clearance');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookings_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookings_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
