-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2026 at 08:48 AM
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
-- Database: `ticketf1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `bookingid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `totalprice` decimal(10,2) NOT NULL,
  `paymentstatus` varchar(20) DEFAULT 'pending',
  `payment_proof` longblob DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookingid`, `userid`, `ticketid`, `quantity`, `totalprice`, `paymentstatus`, `payment_proof`, `payment_method`, `payment_date`) VALUES
(1, 1, 1, 2, 8000.00, 'pending', NULL, NULL, NULL),
(2, 2, 2, 1, 10000.00, 'paid', NULL, NULL, NULL),
(3, 3, 3, 3, 13500.00, 'pending', NULL, NULL, NULL),
(4, 4, 4, 2, 26000.00, 'paid', NULL, NULL, NULL),
(5, 5, 5, 4, 40000.00, 'pending', NULL, NULL, NULL),
(6, 6, 6, 1, 10000.00, 'cancelled', NULL, NULL, NULL),
(7, 7, 7, 2, 24000.00, 'paid', NULL, NULL, NULL),
(8, 8, 8, 3, 39000.00, 'pending', NULL, NULL, NULL),
(9, 9, 9, 1, 100000.00, 'paid', NULL, NULL, NULL),
(10, 10, 10, 2, 200000.00, 'pending', NULL, NULL, NULL),
(11, 20, 4, 1, 100000.00, 'paid', 0x313734313737383139325f6172692e6a7067, 'promptpay', '2025-03-12 18:16:32'),
(12, 20, 8, 1, 4500.00, 'cancelled', NULL, NULL, NULL),
(13, 20, 5, 1, 5000.00, 'paid', NULL, 'credit_card', '2025-03-12 20:11:14'),
(14, 20, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL),
(15, 20, 1, 1, 4000.00, 'paid', 0x313734313738373531365f4173746f6e204d617274696e2e6a7067, 'promptpay', '2025-03-12 20:51:56'),
(16, 20, 3, 1, 12000.00, 'paid', 0x313734313738373736375f4173746f6e204d617274696e312e6a7067, 'promptpay', '2025-03-12 20:56:07'),
(17, 21, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL),
(18, 21, 1, 1, 4000.00, 'paid', 0x313734313738383531325f3130312e6a7067, 'promptpay', '2025-03-12 21:08:32'),
(19, 21, 6, 1, 11000.00, 'paid', 0x313734313738393930385f6172692e6a7067, 'promptpay', '2025-03-12 21:31:48'),
(20, 21, 5, 1, 5000.00, 'cancelled', NULL, NULL, NULL),
(21, 21, 5, 1, 5000.00, 'cancelled', NULL, NULL, NULL),
(22, 21, 5, 1, 5000.00, 'cancelled', NULL, NULL, NULL),
(24, 21, 4, 1, 100000.00, 'paid', 0x313734313739313838345f3130312e6a7067, 'bank_transfer', '2025-03-12 22:04:44'),
(25, 21, 6, 1, 11000.00, 'paid', 0x313734313739313931325f737a612e6a7067, 'promptpay', '2025-03-12 22:05:12'),
(26, 21, 3, 1, 12000.00, 'paid', 0x313734313739323139315f466572726172692e6a7067, 'bank_transfer', '2025-03-12 22:09:51'),
(27, 20, 2, 1, 10000.00, 'paid', 0x313734313739323233305fe0b981e0b8a1e0b8a72e6a7067, 'promptpay', '2025-03-12 22:10:30'),
(28, 20, 9, 1, 13000.00, 'paid', NULL, 'credit_card', '2025-03-12 22:10:57'),
(30, 22, 1, 1, 4000.00, 'canceled', NULL, NULL, NULL),
(31, 22, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL),
(32, 20, 6, 1, 11000.00, 'cancelled', NULL, NULL, NULL),
(33, 20, 8, 1, 4500.00, 'cancelled', NULL, NULL, NULL),
(34, 22, 8, 1, 4500.00, 'paid', NULL, 'credit_card', '2025-03-14 22:28:17'),
(36, 20, 4, 1, 100000.00, 'paid', 0x313734313937333631305f4c616d626f726768696e692e6a7067, 'promptpay', '2025-03-15 00:33:30'),
(37, 20, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL),
(38, 20, 4, 1, 100000.00, 'cancelled', NULL, NULL, NULL),
(39, 20, 1, 1, 4000.00, 'paid', 0x313734363235393038325fe0b981e0b8a1e0b8a72e6a7067, 'promptpay', '2025-05-03 14:58:02'),
(40, 22, 2, 1, 10000.00, 'cancelled', NULL, NULL, NULL),
(41, 20, 1, 1, 4000.00, 'paid', NULL, 'credit_card', '2025-07-07 13:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `circuits`
--

CREATE TABLE `circuits` (
  `circuitid` int(11) NOT NULL,
  `circuitname` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `length_km` decimal(5,2) NOT NULL,
  `turns` int(11) NOT NULL,
  `laprecord` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `circuits`
--

INSERT INTO `circuits` (`circuitid`, `circuitname`, `location`, `country`, `length_km`, `turns`, `laprecord`) VALUES
(1, 'Bahrain International Circuit', 'Sakhir', 'Bahrain', 5.41, 15, '1:31.447'),
(2, 'Jeddah Corniche Circuit', 'Jeddah', 'Saudi Arabia', 6.17, 27, '1:27.511'),
(3, 'Albert Park Circuit', 'Melbourne', 'Australia', 5.28, 16, '1:20.260'),
(4, 'Suzuka Circuit', 'Suzuka', 'Japan', 5.81, 18, '1:30.983'),
(5, 'Shanghai International Circuit', 'Shanghai', 'China', 5.45, 16, '1:32.238'),
(6, 'Miami International Autodrome', 'Miami', 'United States', 5.74, 19, '1:29.708'),
(7, 'Autodromo Enzo e Dino Ferrari (Imola)', 'Imola', 'Italy', 4.91, 19, '1:15.484'),
(8, 'Circuit de Monaco', 'Monte Carlo', 'Monaco', 3.34, 19, '1:12.909'),
(9, 'Circuit Gilles Villeneuve', 'Montreal', 'Canada', 4.36, 14, '1:13.078'),
(10, 'Circuit de Barcelona-Catalunya', 'Barcelona', 'Spain', 4.66, 16, '1:16.741'),
(11, 'Red Bull Ring', 'Spielberg', 'Austria', 4.32, 10, '1:05.619'),
(12, 'Silverstone Circuit', 'Silverstone', 'United Kingdom', 5.89, 18, '1:27.097'),
(13, 'Hungaroring', 'Mogyoród', 'Hungary', 4.38, 14, '1:16.627'),
(14, 'Circuit de Spa-Francorchamps', 'Stavelot', 'Belgium', 7.00, 19, '1:46.286'),
(15, 'Circuit Zandvoort', 'Zandvoort', 'Netherlands', 4.26, 14, '1:08.885'),
(16, 'Autodromo Nazionale Monza', 'Monza', 'Italy', 5.79, 11, '1:21.046'),
(17, 'Baku City Circuit', 'Baku', 'Azerbaijan', 6.00, 20, '1:43.009'),
(18, 'Marina Bay Street Circuit', 'Singapore', 'Singapore', 5.06, 23, '1:41.905'),
(19, 'Circuit of the Americas', 'Austin', 'United States', 5.51, 20, '1:36.169'),
(20, 'Autódromo Hermanos Rodríguez', 'Mexico City', 'Mexico', 4.30, 17, '1:17.774'),
(21, 'Autódromo José Carlos Pace (Interlagos)', 'São Paulo', 'Brazil', 4.31, 15, '1:10.540'),
(22, 'Las Vegas Street Circuit', 'Las Vegas', 'United States', 6.13, 17, '1:35.912'),
(23, 'Lusail International Circuit', 'Lusail', 'Qatar', 5.42, 16, '1:24.319'),
(24, 'Yas Marina Circuit', 'Abu Dhabi', 'United Arab Emirates', 5.28, 16, '1:26.103');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentid` int(11) NOT NULL,
  `bookingid` int(11) NOT NULL,
  `paymentmethod` varchar(50) NOT NULL,
  `paymentstatus` varchar(20) DEFAULT 'pending',
  `paymentdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentid`, `bookingid`, `paymentmethod`, `paymentstatus`, `paymentdate`) VALUES
(1, 1, 'Credit Card', 'pending', '2025-02-19 15:39:45'),
(2, 2, 'Bank Transfer', 'paid', '2025-02-19 15:39:45'),
(3, 3, 'PayPal', 'pending', '2025-02-19 15:39:45'),
(4, 4, 'Credit Card', 'paid', '2025-02-19 15:39:45'),
(5, 5, 'Cash', 'pending', '2025-02-19 15:39:45'),
(6, 6, 'Credit Card', 'cancelled', '2025-02-19 15:39:45'),
(7, 7, 'Bank Transfer', 'paid', '2025-02-19 15:39:45'),
(8, 8, 'PayPal', 'pending', '2025-02-19 15:39:45'),
(9, 9, 'Credit Card', 'paid', '2025-02-19 15:39:45'),
(10, 10, 'Bank Transfer', 'pending', '2025-02-19 15:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `races`
--

CREATE TABLE `races` (
  `raceid` int(11) NOT NULL,
  `racename` varchar(255) NOT NULL,
  `circuitid` int(11) NOT NULL,
  `date` date NOT NULL,
  `starttime` time NOT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `races`
--

INSERT INTO `races` (`raceid`, `racename`, `circuitid`, `date`, `starttime`, `status`) VALUES
(1, 'Bahrain Grand Prix', 1, '2025-03-10', '15:00:00', 0),
(2, 'Saudi Arabian Grand Prix', 2, '2025-03-17', '18:30:00', 0),
(3, 'Australian Grand Prix', 3, '2025-03-24', '16:00:00', 0),
(4, 'Miami Grand Prix', 4, '2025-05-07', '14:00:00', 0),
(5, 'Monaco Grand Prix', 5, '2025-05-21', '15:00:00', 0),
(6, 'British Grand Prix', 6, '2025-07-07', '14:00:00', 0),
(7, 'Hungarian Grand Prix', 7, '2025-07-21', '15:30:00', 0),
(8, 'Belgian Grand Prix', 8, '2025-08-01', '16:00:00', 0),
(9, 'Singapore Grand Prix', 9, '2025-09-15', '19:00:00', 0),
(10, 'Abu Dhabi Grand Prix', 10, '2025-11-25', '18:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `race_schedule`
--

CREATE TABLE `race_schedule` (
  `scheduleid` int(11) NOT NULL,
  `raceid` int(11) NOT NULL,
  `day` enum('Friday','Saturday','Sunday') NOT NULL,
  `event` varchar(50) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `race_schedule`
--

INSERT INTO `race_schedule` (`scheduleid`, `raceid`, `day`, `event`, `starttime`, `endtime`) VALUES
(1, 1, 'Friday', 'Free Practice 1', '10:00:00', '11:30:00'),
(2, 1, 'Friday', 'Free Practice 2', '14:00:00', '15:30:00'),
(3, 1, 'Saturday', 'Free Practice 3', '12:00:00', '13:00:00'),
(4, 1, 'Saturday', 'Qualifying', '16:00:00', '17:00:00'),
(5, 1, 'Sunday', 'Race', '15:00:00', '17:00:00'),
(6, 2, 'Friday', 'Free Practice 1', '10:30:00', '12:00:00'),
(7, 2, 'Friday', 'Free Practice 2', '14:30:00', '16:00:00'),
(8, 2, 'Saturday', 'Free Practice 3', '12:30:00', '13:30:00'),
(9, 2, 'Saturday', 'Qualifying', '16:30:00', '17:30:00'),
(10, 2, 'Sunday', 'Race', '18:30:00', '20:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `seating`
--

CREATE TABLE `seating` (
  `seatid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `rownumber` varchar(10) NOT NULL,
  `seatnumber` varchar(10) NOT NULL,
  `status` varchar(20) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seating`
--

INSERT INTO `seating` (`seatid`, `ticketid`, `section`, `rownumber`, `seatnumber`, `status`) VALUES
(1, 1, 'General Admission', 'A', '001', 'booked'),
(2, 1, 'General Admission', 'A', '002', 'booked'),
(3, 2, 'Main Grandstand', 'B', '015', 'booked'),
(4, 3, 'Paddock Club', 'VIP', '101', 'booked'),
(5, 4, 'General Admission', 'C', '050', 'booked'),
(6, 5, 'Main Grandstand', 'D', '200', 'booked'),
(7, 8, 'General Admission', 'A', '001', 'booked'),
(8, 8, 'General Admission', 'A', '002', 'booked'),
(9, 8, 'General Admission', 'A', '003', 'available'),
(10, 8, 'General Admission', 'A', '004', 'available'),
(11, 8, 'General Admission', 'A', '005', 'available'),
(12, 8, 'General Admission', 'A', '006', 'available'),
(13, 8, 'General Admission', 'A', '007', 'available'),
(14, 8, 'General Admission', 'A', '008', 'available'),
(15, 8, 'General Admission', 'A', '009', 'available'),
(16, 8, 'General Admission', 'A', '010', 'available'),
(17, 8, 'General Admission', 'A', '011', 'available'),
(18, 8, 'General Admission', 'A', '012', 'available'),
(19, 8, 'General Admission', 'A', '013', 'available'),
(20, 8, 'General Admission', 'A', '014', 'available'),
(21, 8, 'General Admission', 'A', '015', 'available'),
(22, 8, 'General Admission', 'A', '016', 'available'),
(23, 8, 'General Admission', 'A', '017', 'available'),
(24, 8, 'General Admission', 'A', '018', 'available'),
(25, 8, 'General Admission', 'A', '019', 'available'),
(26, 8, 'General Admission', 'A', '020', 'available'),
(27, 8, 'General Admission', 'A', '021', 'available'),
(28, 8, 'General Admission', 'A', '022', 'available'),
(29, 8, 'General Admission', 'A', '023', 'available'),
(30, 8, 'General Admission', 'A', '024', 'available'),
(31, 8, 'General Admission', 'A', '025', 'available'),
(32, 8, 'General Admission', 'A', '026', 'available'),
(33, 8, 'General Admission', 'A', '027', 'available'),
(34, 8, 'General Admission', 'A', '028', 'available'),
(35, 8, 'General Admission', 'A', '029', 'available'),
(36, 8, 'General Admission', 'A', '030', 'available'),
(37, 8, 'General Admission', 'A', '031', 'available'),
(38, 8, 'General Admission', 'A', '032', 'available'),
(39, 8, 'General Admission', 'A', '033', 'available'),
(40, 8, 'General Admission', 'A', '034', 'available'),
(41, 8, 'General Admission', 'A', '035', 'available'),
(42, 8, 'General Admission', 'A', '036', 'available'),
(43, 8, 'General Admission', 'A', '037', 'available'),
(44, 8, 'General Admission', 'A', '038', 'available'),
(45, 8, 'General Admission', 'A', '039', 'available'),
(46, 8, 'General Admission', 'A', '040', 'available'),
(47, 8, 'General Admission', 'A', '041', 'available'),
(48, 8, 'General Admission', 'A', '042', 'available'),
(49, 8, 'General Admission', 'A', '043', 'available'),
(50, 8, 'General Admission', 'A', '044', 'available'),
(51, 8, 'General Admission', 'A', '045', 'available'),
(52, 8, 'General Admission', 'A', '046', 'available'),
(53, 8, 'General Admission', 'A', '047', 'available'),
(54, 8, 'General Admission', 'A', '048', 'available'),
(55, 8, 'General Admission', 'A', '049', 'available'),
(56, 8, 'General Admission', 'A', '050', 'available'),
(57, 8, 'General Admission', 'A', '051', 'available'),
(58, 8, 'General Admission', 'A', '052', 'available'),
(59, 8, 'General Admission', 'A', '053', 'available'),
(60, 8, 'General Admission', 'A', '054', 'available'),
(61, 8, 'General Admission', 'A', '055', 'available'),
(62, 8, 'General Admission', 'A', '056', 'available'),
(63, 8, 'General Admission', 'A', '057', 'available'),
(64, 8, 'General Admission', 'A', '058', 'available'),
(65, 8, 'General Admission', 'A', '059', 'available'),
(66, 8, 'General Admission', 'A', '060', 'available'),
(67, 8, 'General Admission', 'A', '061', 'available'),
(68, 8, 'General Admission', 'A', '062', 'available'),
(69, 8, 'General Admission', 'A', '063', 'available'),
(70, 8, 'General Admission', 'A', '064', 'available'),
(71, 8, 'General Admission', 'A', '065', 'available'),
(72, 8, 'General Admission', 'A', '066', 'available'),
(73, 8, 'General Admission', 'A', '067', 'available'),
(74, 8, 'General Admission', 'A', '068', 'available'),
(75, 8, 'General Admission', 'A', '069', 'available'),
(76, 8, 'General Admission', 'A', '070', 'available'),
(77, 8, 'General Admission', 'A', '071', 'available'),
(78, 8, 'General Admission', 'A', '072', 'available'),
(79, 8, 'General Admission', 'A', '073', 'available'),
(80, 8, 'General Admission', 'A', '074', 'available'),
(81, 8, 'General Admission', 'A', '075', 'available'),
(82, 8, 'General Admission', 'A', '076', 'available'),
(83, 8, 'General Admission', 'A', '077', 'available'),
(84, 8, 'General Admission', 'A', '078', 'available'),
(85, 8, 'General Admission', 'A', '079', 'available'),
(86, 8, 'General Admission', 'A', '080', 'available'),
(87, 8, 'General Admission', 'A', '081', 'available'),
(88, 8, 'General Admission', 'A', '082', 'available'),
(89, 8, 'General Admission', 'A', '083', 'available'),
(90, 8, 'General Admission', 'A', '084', 'available'),
(91, 8, 'General Admission', 'A', '085', 'available'),
(92, 8, 'General Admission', 'A', '086', 'available'),
(93, 8, 'General Admission', 'A', '087', 'available'),
(94, 8, 'General Admission', 'A', '088', 'available'),
(95, 8, 'General Admission', 'A', '089', 'available'),
(96, 8, 'General Admission', 'A', '090', 'available'),
(97, 8, 'General Admission', 'A', '091', 'available'),
(98, 8, 'General Admission', 'A', '092', 'available'),
(99, 8, 'General Admission', 'A', '093', 'available'),
(100, 8, 'General Admission', 'A', '094', 'available'),
(101, 5, 'Main Grandstand', 'A', '001', 'available'),
(102, 5, 'Main Grandstand', 'A', '002', 'available'),
(103, 5, 'Main Grandstand', 'A', '003', 'available'),
(104, 5, 'Main Grandstand', 'A', '004', 'available'),
(105, 5, 'Main Grandstand', 'A', '005', 'available'),
(106, 1, 'Main Grandstand', 'A', '001', 'booked'),
(107, 1, 'Main Grandstand', 'A', '002', 'booked'),
(108, 1, 'Main Grandstand', 'A', '003', 'booked'),
(109, 1, 'Main Grandstand', 'A', '004', 'available'),
(110, 1, 'Main Grandstand', 'A', '005', 'available'),
(111, 1, 'Main Grandstand', 'A', '006', 'available'),
(112, 1, 'Main Grandstand', 'A', '007', 'available'),
(113, 1, 'Main Grandstand', 'A', '008', 'available'),
(114, 1, 'Main Grandstand', 'A', '009', 'available'),
(115, 1, 'Main Grandstand', 'A', '010', 'available'),
(116, 2, 'General Admission', 'A', '001', 'booked'),
(117, 2, 'General Admission', 'A', '002', 'available'),
(118, 2, 'General Admission', 'A', '003', 'available'),
(119, 2, 'General Admission', 'A', '004', 'available'),
(120, 2, 'General Admission', 'A', '005', 'available'),
(121, 2, 'General Admission', 'A', '006', 'available'),
(122, 2, 'General Admission', 'A', '007', 'available'),
(123, 2, 'General Admission', 'A', '008', 'available'),
(124, 2, 'General Admission', 'A', '009', 'available'),
(125, 2, 'General Admission', 'A', '010', 'available'),
(126, 3, 'Turn 1 Grandstand', 'A', '001', 'available'),
(127, 3, 'Turn 1 Grandstand', 'A', '002', 'available'),
(128, 3, 'Turn 1 Grandstand', 'A', '003', 'available'),
(129, 3, 'Turn 1 Grandstand', 'A', '004', 'available'),
(130, 3, 'Turn 1 Grandstand', 'A', '005', 'available'),
(131, 3, 'Turn 1 Grandstand', 'A', '006', 'available'),
(132, 3, 'Turn 1 Grandstand', 'A', '007', 'available'),
(133, 3, 'Turn 1 Grandstand', 'A', '008', 'available'),
(134, 3, 'Turn 1 Grandstand', 'A', '009', 'available'),
(135, 3, 'Turn 1 Grandstand', 'A', '010', 'available'),
(136, 4, 'Paddock Club', 'A', '001', 'booked'),
(137, 4, 'Paddock Club', 'A', '002', 'booked'),
(138, 4, 'Paddock Club', 'A', '003', 'available'),
(139, 4, 'Paddock Club', 'A', '004', 'available'),
(140, 4, 'Paddock Club', 'A', '005', 'available'),
(141, 4, 'Paddock Club', 'A', '006', 'available'),
(142, 4, 'Paddock Club', 'A', '007', 'available'),
(143, 4, 'Paddock Club', 'A', '008', 'available'),
(144, 4, 'Paddock Club', 'A', '009', 'available'),
(145, 4, 'Paddock Club', 'A', '010', 'available'),
(146, 5, 'General Admission', 'A', '001', 'available'),
(147, 5, 'General Admission', 'A', '002', 'available'),
(148, 5, 'General Admission', 'A', '003', 'available'),
(149, 5, 'General Admission', 'A', '004', 'available'),
(150, 5, 'General Admission', 'A', '005', 'available'),
(151, 5, 'General Admission', 'A', '006', 'available'),
(152, 5, 'General Admission', 'A', '007', 'available'),
(153, 5, 'General Admission', 'A', '008', 'available'),
(154, 5, 'General Admission', 'A', '009', 'available'),
(155, 5, 'General Admission', 'A', '010', 'available'),
(156, 6, 'Main Grandstand', 'A', '001', 'booked'),
(157, 6, 'Main Grandstand', 'A', '002', 'available'),
(158, 6, 'Main Grandstand', 'A', '003', 'available'),
(159, 6, 'Main Grandstand', 'A', '004', 'available'),
(160, 6, 'Main Grandstand', 'A', '005', 'available'),
(161, 6, 'Main Grandstand', 'A', '006', 'available'),
(162, 6, 'Main Grandstand', 'A', '007', 'available'),
(163, 6, 'Main Grandstand', 'A', '008', 'available'),
(164, 6, 'Main Grandstand', 'A', '009', 'available'),
(165, 6, 'Main Grandstand', 'A', '010', 'available'),
(166, 7, 'Paddock Club', 'A', '001', 'available'),
(167, 7, 'Paddock Club', 'A', '002', 'available'),
(168, 7, 'Paddock Club', 'A', '003', 'available'),
(169, 7, 'Paddock Club', 'A', '004', 'available'),
(170, 7, 'Paddock Club', 'A', '005', 'available'),
(171, 7, 'Paddock Club', 'A', '006', 'available'),
(172, 7, 'Paddock Club', 'A', '007', 'available'),
(173, 7, 'Paddock Club', 'A', '008', 'available'),
(174, 7, 'Paddock Club', 'A', '009', 'available'),
(175, 7, 'Paddock Club', 'A', '010', 'available'),
(176, 8, 'Turn 1 Grandstand', 'A', '001', 'booked'),
(177, 8, 'Turn 1 Grandstand', 'A', '002', 'available'),
(178, 8, 'Turn 1 Grandstand', 'A', '003', 'available'),
(179, 8, 'Turn 1 Grandstand', 'A', '004', 'available'),
(180, 8, 'Turn 1 Grandstand', 'A', '005', 'available'),
(181, 8, 'Turn 1 Grandstand', 'A', '006', 'available'),
(182, 8, 'Turn 1 Grandstand', 'A', '007', 'available'),
(183, 8, 'Turn 1 Grandstand', 'A', '008', 'available'),
(184, 8, 'Turn 1 Grandstand', 'A', '009', 'available'),
(185, 8, 'Turn 1 Grandstand', 'A', '010', 'available'),
(186, 9, 'Paddock Club', 'A', '001', 'available'),
(187, 9, 'Paddock Club', 'A', '002', 'available'),
(188, 9, 'Paddock Club', 'A', '003', 'available'),
(189, 9, 'Paddock Club', 'A', '004', 'available'),
(190, 9, 'Paddock Club', 'A', '005', 'available'),
(191, 9, 'Paddock Club', 'A', '006', 'available'),
(192, 9, 'Paddock Club', 'A', '007', 'available'),
(193, 9, 'Paddock Club', 'A', '008', 'available'),
(194, 9, 'Paddock Club', 'A', '009', 'available'),
(195, 9, 'Paddock Club', 'A', '010', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticketid` int(11) NOT NULL,
  `raceid` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `section` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `totalseats` int(11) NOT NULL,
  `availableseats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticketid`, `raceid`, `category`, `section`, `price`, `totalseats`, `availableseats`) VALUES
(1, 1, 'Walkabout', 'General Admission', 4000.00, 5000, 4993),
(2, 1, 'Grandstand', 'Main Grandstand', 10000.00, 3000, 2998),
(3, 1, 'Grandstand', 'Turn 1 Grandstand', 12000.00, 2500, 2495),
(4, 1, 'Hospitality', 'Paddock Club', 100000.00, 500, 496),
(5, 2, 'Walkabout', 'General Admission', 5000.00, 5500, 5495),
(6, 2, 'Grandstand', 'Main Grandstand', 11000.00, 3200, 3198),
(7, 2, 'Hospitality', 'Paddock Club', 120000.00, 600, 598),
(8, 3, 'Walkabout', 'General Admission', 4500.00, 5300, 5296),
(9, 3, 'Grandstand', 'Turn 1 Grandstand', 13000.00, 2700, 2698),
(10, 3, 'Hospitality', 'VIP Lounge', 150000.00, 400, 398),
(23, 1, 'll', '3', 2000.00, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `user_type` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `user_type`) VALUES
(1, 'Anucha', 'Wongchai', 'anucha.w@gmail.com', '12345678', '0812345678', 'user'),
(2, 'Sudarat', 'Pimthong', 'sudarat.p@gmail.com', '23456789', '0823456789', 'user'),
(3, 'Thanakorn', 'Chaiyaphum', 'thanakorn.c@gmail.com', '34567890', '0834567890', 'user'),
(4, 'Siriporn', 'Methasiri', 'siriporn.m@gmail.com', '45678901', '0845678901', 'user'),
(5, 'Jirawat', 'Narongsak', 'jirawat.n@gmail.com', '56789012', '0856789012', 'user'),
(6, 'Chalisa', 'Dechachai', 'chalisa.d@gmail.com', '67890123', '0867890123', 'user'),
(7, 'Pongsakorn', 'Rattanawong', 'pongsakorn.r@gmail.com', '78901234', '0878901234', 'user'),
(8, 'Nattaporn', 'Sukprasert', 'nattaporn.s@gmail.com', '89012345', '0889012345', 'user'),
(9, 'Patcharaporn', 'Limsakul', 'patcharaporn.l@gmail.com', '90123456', '0890123456', 'user'),
(10, 'minnie', 'Superidol', 'minnie@gmail.com', '02351234', '0801234567', 'user'),
(12, 'Admin', 'User', 'admin@example.com', '10032566', '0800000000', 'admin'),
(20, 'neem', 'T', 'tn@example.com', '12042005', '0867928978', 'user'),
(21, 'jj', 'ff', 'sutasinee8978@gmail.com', '12041003', '0814316962', 'user'),
(22, 'jj', 'ff', 'jj@example.com', '10031204', '0867928978', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookingid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `ticketid` (`ticketid`);

--
-- Indexes for table `circuits`
--
ALTER TABLE `circuits`
  ADD PRIMARY KEY (`circuitid`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentid`),
  ADD KEY `bookingid` (`bookingid`);

--
-- Indexes for table `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`raceid`),
  ADD KEY `fk_circuit` (`circuitid`);

--
-- Indexes for table `race_schedule`
--
ALTER TABLE `race_schedule`
  ADD PRIMARY KEY (`scheduleid`),
  ADD KEY `raceid` (`raceid`);

--
-- Indexes for table `seating`
--
ALTER TABLE `seating`
  ADD PRIMARY KEY (`seatid`),
  ADD KEY `fk_ticketid` (`ticketid`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticketid`),
  ADD KEY `raceid` (`raceid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookingid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `circuits`
--
ALTER TABLE `circuits`
  MODIFY `circuitid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `races`
--
ALTER TABLE `races`
  MODIFY `raceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `race_schedule`
--
ALTER TABLE `race_schedule`
  MODIFY `scheduleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `seating`
--
ALTER TABLE `seating`
  MODIFY `seatid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticketid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`ticketid`) REFERENCES `tickets` (`ticketid`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`bookingid`) REFERENCES `bookings` (`bookingid`) ON DELETE CASCADE;

--
-- Constraints for table `races`
--
ALTER TABLE `races`
  ADD CONSTRAINT `fk_circuit` FOREIGN KEY (`circuitid`) REFERENCES `circuits` (`circuitid`) ON DELETE CASCADE;

--
-- Constraints for table `race_schedule`
--
ALTER TABLE `race_schedule`
  ADD CONSTRAINT `race_schedule_ibfk_1` FOREIGN KEY (`raceid`) REFERENCES `races` (`raceid`) ON DELETE CASCADE;

--
-- Constraints for table `seating`
--
ALTER TABLE `seating`
  ADD CONSTRAINT `fk_ticketid` FOREIGN KEY (`ticketid`) REFERENCES `tickets` (`ticketid`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`raceid`) REFERENCES `races` (`raceid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
