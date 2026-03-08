-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 08, 2026 at 04:14 PM
-- Server version: 11.8.6-MariaDB-ubu2404
-- PHP Version: 8.3.30

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
  `payment_date` datetime DEFAULT NULL,
  `bookingdate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookingid`, `userid`, `ticketid`, `quantity`, `totalprice`, `paymentstatus`, `payment_proof`, `payment_method`, `payment_date`, `bookingdate`) VALUES
(1, 1, 1, 2, 8000.00, 'pending', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(2, 2, 2, 1, 10000.00, 'paid', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(3, 3, 3, 3, 13500.00, 'pending', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(4, 4, 4, 2, 26000.00, 'paid', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(5, 5, 5, 4, 40000.00, 'pending', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(6, 6, 6, 1, 10000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(7, 7, 7, 2, 24000.00, 'paid', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(8, 8, 8, 3, 39000.00, 'pending', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(9, 9, 9, 1, 100000.00, 'paid', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(10, 10, 10, 2, 200000.00, 'pending', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(11, 20, 4, 1, 100000.00, 'paid', 0x313734313737383139325f6172692e6a7067, 'promptpay', '2025-03-12 18:16:32', '2026-03-08 09:11:09'),
(12, 20, 8, 1, 4500.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(13, 20, 5, 1, 5000.00, 'paid', NULL, 'credit_card', '2025-03-12 20:11:14', '2026-03-08 09:11:09'),
(14, 20, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(15, 20, 1, 1, 4000.00, 'paid', 0x313734313738373531365f4173746f6e204d617274696e2e6a7067, 'promptpay', '2025-03-12 20:51:56', '2026-03-08 09:11:09'),
(16, 20, 3, 1, 12000.00, 'paid', 0x313734313738373736375f4173746f6e204d617274696e312e6a7067, 'promptpay', '2025-03-12 20:56:07', '2026-03-08 09:11:09'),
(17, 21, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(18, 21, 1, 1, 4000.00, 'paid', 0x313734313738383531325f3130312e6a7067, 'promptpay', '2025-03-12 21:08:32', '2026-03-08 09:11:09'),
(19, 21, 6, 1, 11000.00, 'paid', 0x313734313738393930385f6172692e6a7067, 'promptpay', '2025-03-12 21:31:48', '2026-03-08 09:11:09'),
(20, 21, 5, 1, 5000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(21, 21, 5, 1, 5000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(22, 21, 5, 1, 5000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(24, 21, 4, 1, 100000.00, 'paid', 0x313734313739313838345f3130312e6a7067, 'bank_transfer', '2025-03-12 22:04:44', '2026-03-08 09:11:09'),
(25, 21, 6, 1, 11000.00, 'paid', 0x313734313739313931325f737a612e6a7067, 'promptpay', '2025-03-12 22:05:12', '2026-03-08 09:11:09'),
(26, 21, 3, 1, 12000.00, 'paid', 0x313734313739323139315f466572726172692e6a7067, 'bank_transfer', '2025-03-12 22:09:51', '2026-03-08 09:11:09'),
(27, 20, 2, 1, 10000.00, 'paid', 0x313734313739323233305fe0b981e0b8a1e0b8a72e6a7067, 'promptpay', '2025-03-12 22:10:30', '2026-03-08 09:11:09'),
(28, 20, 9, 1, 13000.00, 'paid', NULL, 'credit_card', '2025-03-12 22:10:57', '2026-03-08 09:11:09'),
(30, 22, 1, 1, 4000.00, 'canceled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(31, 22, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(32, 20, 6, 1, 11000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(33, 20, 8, 1, 4500.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(34, 22, 8, 1, 4500.00, 'paid', NULL, 'credit_card', '2025-03-14 22:28:17', '2026-03-08 09:11:09'),
(36, 20, 4, 1, 100000.00, 'paid', 0x313734313937333631305f4c616d626f726768696e692e6a7067, 'promptpay', '2025-03-15 00:33:30', '2026-03-08 09:11:09'),
(37, 20, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(38, 20, 4, 1, 100000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(39, 20, 1, 1, 4000.00, 'paid', 0x313734363235393038325fe0b981e0b8a1e0b8a72e6a7067, 'promptpay', '2025-05-03 14:58:02', '2026-03-08 09:11:09'),
(40, 22, 2, 1, 10000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:11:09'),
(41, 20, 1, 1, 4000.00, 'paid', NULL, 'credit_card', '2025-07-07 13:54:56', '2026-03-08 09:11:09'),
(42, 21, 24, 1, 4500.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 09:20:29'),
(43, 21, 1, 1, 4000.00, 'Paid', NULL, NULL, NULL, '2026-03-08 10:06:51'),
(44, 21, 2, 1, 10000.00, 'Paid', NULL, NULL, NULL, '2026-03-08 10:41:14'),
(45, 21, 8, 1, 4500.00, 'Paid', NULL, NULL, NULL, '2026-03-08 11:04:33'),
(46, 21, 42, 1, 7000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 14:27:49'),
(47, 21, 4, 1, 100000.00, 'Paid', NULL, NULL, NULL, '2026-03-08 14:30:15'),
(48, 21, 1, 1, 4000.00, 'cancelled', NULL, NULL, NULL, '2026-03-08 15:31:25'),
(49, 21, 2, 1, 10000.00, 'Paid', NULL, NULL, NULL, '2026-03-08 15:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `booking_seats`
--

CREATE TABLE `booking_seats` (
  `id` int(11) NOT NULL,
  `bookingid` int(11) NOT NULL,
  `seatid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_seats`
--

INSERT INTO `booking_seats` (`id`, `bookingid`, `seatid`) VALUES
(1, 47, 138),
(2, 49, 117);

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
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `highlight_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `category` enum('Qualifying','Race','Pit','Overtake') DEFAULT 'Race',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `highlights`
--

INSERT INTO `highlights` (`highlight_id`, `title`, `youtube_url`, `category`, `created_at`) VALUES
(1, '“รัสเซลล์” คว้าโพลเปิดฤดูกาล F1 ออสเตรเลียน กรังด์ปรีซ์ – อัลบอนควอลิฟายอันดับ 15', 'https://youtu.be/Rbll4MnQuec?si=zt_8OtYyCupTtSqA', 'Qualifying', '2026-03-07 15:19:42');

-- --------------------------------------------------------

--
-- Table structure for table `live_timing`
--

CREATE TABLE `live_timing` (
  `live_id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `team_name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `lap_time` varchar(30) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `status` enum('Running','Pit','Finished') DEFAULT 'Running',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `live_timing`
--

INSERT INTO `live_timing` (`live_id`, `race_id`, `driver_name`, `team_name`, `position`, `lap_time`, `points`, `status`, `updated_at`) VALUES
(1, 3, 'George Russell', 'Mercedes', 1, '1:18.518', 0, 'Finished', '2026-03-07 16:04:21');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `news_type` enum('general','ticket_sale') DEFAULT 'general',
  `status` enum('draft','publish') DEFAULT 'draft',
  `ticket_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `title`, `summary`, `image_url`, `content`, `news_type`, `status`, `ticket_id`, `created_at`) VALUES
(1, '“รัสเซลล์” คว้าโพลเปิดฤดูกาล F1 ออสเตรเลียน กรังด์ปรีซ์ – อัลบอนควอลิฟายอันดับ 15', 'จอร์จ รัสเซลล์ โชว์ฟอร์มร้อนแรง คว้าโพลโพซิชันสนามเปิดฤดูกาลศึก Formula 1 รายการ Australian Grand Prix 2026 ขณะที่ อเล็กซานเดอร์ อัลบอน นักขับไทยจากทีม Williams ทำผลงานผ่าน Q2 ก่อนจบควอลิฟายในอันดับที่ 15 ลุ้นสร้างผลงานในเรซหลักวันอาทิตย์นี้', 'https://media.formula1.com/image/upload/t_16by9North/c_lfill,w_3392/q_auto/v1740000000/trackside-images/2026/F1_Grand_Prix_Of_Australia___Qualifying/2265206427.webp', 'การแข่งขันรถสูตรหนึ่งชิงแชมป์โลก รายการ Formula One World Championship 2026 สนามเปิดฤดูกาลในศึก Australian Grand Prix ที่สนาม Albert Park Circuit ประเทศออสเตรเลีย เสร็จสิ้นการแข่งขันรอบควอลิฟายเพื่อจัดอันดับสตาร์ทเป็นที่เรียบร้อยแล้ว\r\n\r\nผลการแข่งขันปรากฏว่า George Russell นักขับจากทีม Mercedes-AMG Petronas Formula One Team ทำเวลาต่อรอบดีที่สุด 1 นาที 18.518 วินาที คว้า โพลโพซิชัน ไปครอง และจะได้ออกสตาร์ทในตำแหน่งหัวแถวของการแข่งขันในวันอาทิตย์นี้\r\n\r\nอันดับที่ 2 เป็นของดาวรุ่งเพื่อนร่วมทีม Kimi Antonelli ตามหลัง +0.293 วินาที ขณะที่อันดับที่ 3 ตกเป็นของ Isack Hadjar จากทีม Red Bull Racing\r\n\r\nส่วนอันดับที่ 4 และ 5 เป็นของ Charles Leclerc จากทีม Scuderia Ferrari และ Oscar Piastri จากทีม McLaren ตามลำดับ\r\n\r\nด้านนักขับไทย Alexander Albon จากทีม Williams Racing ทำเวลา 1 นาที 20.941 วินาที สามารถผ่านเข้าสู่รอบ Q2 ก่อนจะจบการควอลิฟายในอันดับ 15 ได้ออกสตาร์ทจากกลางแถวในเรซหลัก\r\n\r\nสำหรับการแข่งขันรอบชิงชัยของศึก Australian Grand Prix 2026 จะมีขึ้นในวันอาทิตย์ที่ 8 มีนาคม 2569 เวลา 11.00 น. (ตามเวลาประเทศไทย) เพื่อชิงชัยแชมป์สนามแรกของฤดูกาล', 'general', 'publish', NULL, '2026-03-07 07:55:25'),
(3, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'คาร์ลอส ไซน์ซ เผยสาเหตุที่ไม่สามารถลงแข่งรอบควอลิฟายของศึก Australian Grand Prix ได้ หลังรถมีปัญหาระบบ ERS ส่งผลให้ต้องออกสตาร์ทจากท้ายกริด ขณะที่ทีม Williams เผชิญสุดสัปดาห์ที่ยากลำบาก', 'https://media.formula1.com/image/upload/t_16by9Centre/c_lfill,w_3392/q_auto/v1740000000/trackside-images/2026/F1_Grand_Prix_Of_Australia___Qualifying/2265189493.webp', 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย\r\n\r\nทีม Williams Racing ต้องเจอกับสุดสัปดาห์ที่น่าผิดหวังในศึก Formula One Australian Grand Prix 2026 หลังจาก Carlos Sainz ไม่สามารถลงแข่งขันในรอบ Qualifying ได้ ส่งผลให้ต้องออกสตาร์ทจากท้ายกริดในการแข่งขันวันอาทิตย์\r\n\r\nนักขับชาวสเปนมีโอกาสลงสนามเพียงหนึ่งเซสชันเต็มตลอดสุดสัปดาห์ โดยจบ FP1 ในอันดับที่ 12 ก่อนจะต้องกลับเข้าพิทในช่วงกลางของ FP2 เพื่อให้ทีมตรวจสอบปัญหาที่เกิดขึ้นกับรถ\r\n\r\nแม้จะเริ่มต้นการซ้อมครั้งสุดท้าย (FP3) ได้ แต่ไซน์ซก็ต้องหยุดรถตั้งแต่รอบแรกหลังเกิด ปัญหากำลังเครื่องยนต์หาย (Power Loss) บริเวณทางเข้าพิท ทำให้ต้องมีการประกาศ Virtual Safety Car เพื่อกู้รถ FW48 ของเขากลับเข้าพิท\r\n\r\nปัญหาดังกล่าวยังส่งผลต่อเนื่องจนทีมไม่สามารถแก้ไขได้ทันก่อนเริ่มรอบ Q1 ทำให้ไซน์ซพลาดการลงควอลิฟายทั้งหมด และจะต้องออกสตาร์ทจาก อันดับที่ 21 บนกริดร่วมกับ Lance Stroll จากทีม Aston Martin ที่ไม่สามารถลงเวลาได้เช่นกัน\r\n\r\nไซน์ซกล่าวถึงสถานการณ์ดังกล่าวว่า\r\n“เรามีปัญหาเกี่ยวกับระบบ ERS (Energy Recovery System) และไม่สามารถแก้ไขได้ทันก่อนควอลิฟาย ทำให้สุดสัปดาห์แรกภายใต้กฎใหม่เป็นอะไรที่น่าผิดหวังมาก”\r\n\r\n“ผมไม่ได้วิ่งใน FP2 เลย ไม่มีลองรันระยะยาว ไม่ได้ลองยางซอฟต์ และไม่ได้วิ่ง FP3 หรือ Q1 ด้วย ดังนั้นการเริ่มฤดูกาลแบบนี้ไม่ใช่สิ่งที่เหมาะเลย โดยเฉพาะก่อนเข้าสู่การแข่งขันที่จีนในสัปดาห์หน้า”\r\n\r\nขณะที่เพื่อนร่วมทีม Alexander Albon สามารถเก็บข้อมูลจากการซ้อมได้มากกว่า รวมถึงการจำลองการแข่งขันใน FP2 แม้ว่าสุดสัปดาห์ของเขาจะไม่ได้ราบรื่นเช่นกัน\r\n\r\nอัลบอนทำผลงานดีที่สุดใน Q1 ด้วยอันดับ 13 ก่อนจะจบรอบ Q2 ในอันดับ 15 หลังจากมีจังหวะล้อหลุดออกไปบนพื้นหญ้าระหว่างรอบสุดท้าย ทำให้พลาดโอกาสลุ้นเข้าสู่ Q3\r\n\r\nอย่างไรก็ตาม นักขับไทยมองว่าผลงานดังกล่าวยังถือว่าอยู่ในระดับที่ดีกว่าที่ทีมคาดไว้ก่อนเข้าสู่สุดสัปดาห์\r\n\r\n“เราช้ากว่ารถคันหน้าอยู่ประมาณครึ่งวินาที ดังนั้นเราต้องหาทางลดเวลาตรงนั้นให้ได้ แต่โดยรวมถือว่าผลงานโอเค และดีกว่าที่เราคาดไว้ก่อนเริ่มการแข่งขัน” อัลบอนกล่าว\r\n\r\nเขายังเสริมว่าทีมต้องเผชิญกับปัญหาทางเทคนิคหลายอย่างตลอดสุดสัปดาห์ และยังต้องแก้ไขปัญหาการสึกหรอของยางที่ค่อนข้างรุนแรง\r\n\r\n“มันเป็นสุดสัปดาห์ที่ยากมาก เรามีปัญหาทางเทคนิคหลายอย่างและต้องคอยแก้ปัญหาตลอด เรารู้ว่ามีจุดที่สามารถทำเวลาได้ดีขึ้น”\r\n\r\nอัลบอนยังกล่าวติดตลกว่า\r\n“ตอนนี้ผมเพิ่งลองซ้อมออกสตาร์ทไปแค่สองครั้งในฤดูกาล 2026 เท่านั้น ผมอยากให้มีฝนตกในเรซ แต่ดูเหมือนว่าจะไม่เกิดขึ้น!”', 'general', 'publish', NULL, '2026-03-07 15:02:40'),
(4, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'เฟอร์นานโด อลอนโซ มองเห็นสัญญาณเชิงบวกของ Aston Martin หลังเกือบผ่านเข้าสู่รอบ Q2 ในศึก Australian Grand Prix แม้สุดท้ายจะได้ออกสตาร์ทอันดับ 17 ขณะที่เพื่อนร่วมทีม แลนซ์ สโตรลล์ พลาดลงควอลิฟายจากปัญหาเครื่องยนต์', 'https://media.formula1.com/image/upload/t_16by9Centre/c_lfill,w_3392/q_auto/v1740000000/trackside-images/2026/F1_Grand_Prix_Of_Australia___Qualifying/2265210207.webp', 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย\r\n\r\nFernando Alonso นักขับมากประสบการณ์ของทีม Aston Martin แสดงความเชื่อมั่นว่าทีมกำลังมีพัฒนาการที่ดี หลังจากทำผลงานในรอบ Qualifying ของศึก Australian Grand Prix 2026 ได้ใกล้เคียงกับการผ่านเข้าสู่ Q2\r\n\r\nแม้สุดท้ายเขาจะได้ออกสตาร์ทจาก อันดับที่ 17 แต่อลอนโซมองว่าการแข่งขันครั้งนี้แสดงให้เห็นถึงศักยภาพของรถที่ยังสามารถพัฒนาได้อีกมาก\r\n\r\nทีม Aston Martin ซึ่งเริ่มต้นความร่วมมือกับ Honda ในฐานะพาร์ตเนอร์เครื่องยนต์เป็นครั้งแรกในฤดูกาลนี้ ต้องเผชิญกับความท้าทายตั้งแต่ช่วง pre-season testing รวมถึงการซ้อมที่สนาม Albert Park Circuit\r\n\r\nอลอนโซไม่สามารถลงแข่งขันใน FP1 ได้ เนื่องจากทีมสงสัยว่ามีปัญหาเกี่ยวกับ Power Unit ทำให้เขาต้องพยายามชดเชยเวลาการทดสอบที่เสียไปใน FP2 และ FP3\r\n\r\nอย่างไรก็ตาม ปริมาณการวิ่งของทีมยังน้อยกว่าคู่แข่งอย่างเห็นได้ชัด\r\n\r\nสถานการณ์เริ่มดีขึ้นในรอบควอลิฟาย เมื่ออลอนโซทำเวลา 1 นาที 21.969 วินาที และเคยอยู่ในตำแหน่งผ่านเข้าสู่ Q2 ชั่วคราว หลังธงตราหมากรุก ก่อนจะถูก Franco Colapinto จากทีม Alpine แซงขึ้นไป ทำให้เขาจบที่อันดับ 17\r\n\r\nอลอนโซกล่าวหลังจบเซสชันว่า\r\n\r\n“ถ้าให้คาดการณ์เมื่อวานนี้ หลายคนคงคิดว่าเราจะไม่สามารถผ่าน Q1 ได้ แต่วันนี้เราเกือบทำได้ ซึ่งถือว่าเป็นความก้าวหน้าจากเมื่อวาน”\r\n\r\n“เราแทบไม่ได้ปรับอะไรกับรถเลย แต่ระยะห่างจากผู้นำลดลงอย่างมาก จากประมาณ 4.5 วินาที เหลือราว 2.5 วินาที นั่นแสดงว่าศักยภาพของรถยังมีอีกมาก”\r\n\r\nเขายังชี้ว่าปัญหาหลักของทีมคือ ความน่าเชื่อถือของรถ (Reliability) ซึ่งส่งผลต่อการพัฒนาเซ็ตอัพ\r\n\r\n“ถ้าคุณไม่สามารถวิ่งได้ต่อเนื่อง มันก็ยากที่จะทำให้เซ็ตอัพของรถทำงานได้เต็มที่ แต่ทันทีที่เรามี FP2 และ FP3 แบบปกติ เราก็สามารถพัฒนาได้อย่างก้าวกระโดด”\r\n\r\nขณะเดียวกัน เพื่อนร่วมทีม Lance Stroll ต้องพลาดลงควอลิฟาย หลังจากพบปัญหาเกี่ยวกับ Internal Combustion Engine ในช่วง FP3 และทีมไม่สามารถซ่อมรถได้ทันเวลา\r\n\r\nสำหรับการแข่งขันในวันอาทิตย์ อลอนโซยอมรับว่าเป้าหมายสำคัญคือการเก็บข้อมูลและพยายามวิ่งให้ได้มากที่สุด\r\n\r\n“ยิ่งเราวิ่งมากเท่าไหร่ เราก็ยิ่งเรียนรู้มากขึ้น แต่เราต้องระมัดระวังด้วย เพราะตอนนี้เรามีอะไหล่จำกัด และสัปดาห์หน้าก็ต้องไปแข่งที่จีน”\r\n\r\n“หากมีสัญญาณว่ามีบางอย่างผิดปกติ เราอาจต้องหยุดรถ เพราะเราจำเป็นต้องรักษารถให้พร้อมสำหรับการแข่งขันสนามต่อไป”', 'general', 'publish', NULL, '2026-03-07 15:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(2, 2, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(3, 3, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(4, 4, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(5, 5, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(6, 6, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(7, 7, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(8, 8, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(9, 9, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(10, 10, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(11, 20, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(12, 21, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 1, '2026-03-07 15:02:58'),
(13, 22, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(14, 24, 'ไซน์ซเผยเหตุพลาดควอลิฟาย หลังรถมีปัญหา ERS – วิลเลียมส์เผชิญสุดสัปดาห์สุดยากในออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=3', 0, '2026-03-07 15:02:58'),
(15, 1, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(16, 2, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(17, 3, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(18, 4, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(19, 5, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(20, 6, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(21, 7, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(22, 8, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(23, 9, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(24, 10, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(25, 20, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(26, 21, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 1, '2026-03-07 16:22:42'),
(27, 22, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42'),
(28, 24, 'อลอนโซเชื่อ Aston Martin ยังมีศักยภาพสูง แม้ควอลิฟายได้เพียงอันดับ 17 ที่ออสเตรเลีย', 'มีข่าวใหม่ในระบบ', 'news_detail.php?id=4', 0, '2026-03-07 16:22:42');

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
(10, 10, 'Bank Transfer', 'pending', '2025-02-19 15:39:45'),
(28, 43, 'Credit Card', 'paid', '2026-03-08 10:40:37'),
(29, 44, 'Bank Transfer', 'pending', '2026-03-08 10:41:19'),
(30, 44, 'PromptPay', 'pending', '2026-03-08 10:41:27'),
(31, 44, 'PromptPay', 'pending', '2026-03-08 10:57:37'),
(32, 44, 'PayPal', 'paid', '2026-03-08 10:57:52'),
(33, 45, 'PromptPay', 'paid', '2026-03-08 11:04:40'),
(34, 47, 'PromptPay', 'paid', '2026-03-08 14:30:20'),
(35, 49, 'PromptPay', 'paid', '2026-03-08 15:33:49');

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
(3, 2, 'Main Grandstand', 'B', '015', 'booked'),
(4, 3, 'Paddock Club', 'VIP', '101', 'booked'),
(5, 4, 'General Admission', 'C', '050', 'booked'),
(116, 2, 'General Admission', 'A', '001', 'booked'),
(117, 2, 'General Admission', 'A', '002', 'booked'),
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
(138, 4, 'Paddock Club', 'A', '003', 'booked'),
(139, 4, 'Paddock Club', 'A', '004', 'available'),
(140, 4, 'Paddock Club', 'A', '005', 'available'),
(141, 4, 'Paddock Club', 'A', '006', 'available'),
(142, 4, 'Paddock Club', 'A', '007', 'available'),
(143, 4, 'Paddock Club', 'A', '008', 'available'),
(144, 4, 'Paddock Club', 'A', '009', 'available'),
(145, 4, 'Paddock Club', 'A', '010', 'available'),
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
  `availableseats` int(11) NOT NULL,
  `seatmode` varchar(30) NOT NULL DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticketid`, `raceid`, `category`, `section`, `price`, `totalseats`, `availableseats`, `seatmode`) VALUES
(1, 1, 'Walkabout', 'General Admission', 4000.00, 5000, 4992, 'general'),
(2, 1, 'Grandstand', 'Main Grandstand', 10000.00, 11, 8, 'zoned'),
(3, 1, 'Grandstand', 'Turn 1 Grandstand', 12000.00, 11, 10, 'zoned'),
(4, 1, 'Hospitality', 'Paddock Club', 100000.00, 11, 7, 'premium'),
(5, 2, 'Walkabout', 'General Admission', 5000.00, 5500, 5495, 'general'),
(6, 2, 'Grandstand', 'Main Grandstand', 11000.00, 10, 9, 'zoned'),
(7, 2, 'Hospitality', 'Paddock Club', 120000.00, 10, 10, 'premium'),
(8, 3, 'Walkabout', 'General Admission', 4500.00, 5300, 5295, 'general'),
(9, 3, 'Grandstand', 'Turn 1 Grandstand', 13000.00, 10, 10, 'zoned'),
(10, 3, 'Hospitality', 'VIP Lounge', 150000.00, 0, 0, 'premium'),
(24, 4, 'Walkabout', 'General Admission', 4500.00, 5200, 5200, 'general'),
(25, 4, 'Grandstand', 'Main Grandstand', 12000.00, 0, 0, 'zoned'),
(26, 4, 'Hospitality', 'Paddock Club', 150000.00, 0, 0, 'premium'),
(27, 5, 'Walkabout', 'General Admission', 8000.00, 4000, 4000, 'general'),
(28, 5, 'Grandstand', 'Casino Square', 25000.00, 0, 0, 'zoned'),
(29, 5, 'Hospitality', 'Paddock Club', 200000.00, 0, 0, 'premium'),
(30, 6, 'Walkabout', 'General Admission', 6000.00, 6000, 6000, 'general'),
(31, 6, 'Grandstand', 'Silverstone Main', 15000.00, 0, 0, 'zoned'),
(32, 6, 'Hospitality', 'Paddock Club', 180000.00, 0, 0, 'premium'),
(33, 7, 'Walkabout', 'General Admission', 4000.00, 5500, 5500, 'general'),
(34, 7, 'Grandstand', 'Turn 1 Grandstand', 11000.00, 0, 0, 'zoned'),
(35, 7, 'Hospitality', 'Paddock Club', 130000.00, 0, 0, 'premium'),
(36, 8, 'Walkabout', 'General Admission', 5000.00, 5300, 5300, 'general'),
(37, 8, 'Grandstand', 'Eau Rouge Grandstand', 16000.00, 0, 0, 'zoned'),
(38, 8, 'Hospitality', 'Paddock Club', 170000.00, 0, 0, 'premium'),
(39, 9, 'Walkabout', 'General Admission', 9000.00, 4800, 4800, 'general'),
(40, 9, 'Grandstand', 'Marina Bay Grandstand', 22000.00, 0, 0, 'zoned'),
(41, 9, 'Hospitality', 'VIP Lounge', 190000.00, 0, 0, 'premium'),
(42, 10, 'Walkabout', 'General Admission', 7000.00, 5000, 5000, 'general'),
(43, 10, 'Grandstand', 'Yas Marina Main', 18000.00, 0, 0, 'zoned'),
(44, 10, 'Hospitality', 'VIP Lounge', 200000.00, 0, 0, 'premium');

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
(12, 'Admin', 'User', 'admin@example.com', '$2y$10$VuxQXBKfiATAtMqx83IKP.Jh6WW22g83rWaVrIPcNGFv9EhcphENW', '0800000000', 'admin'),
(20, 'neem', 'T', 'tn@example.com', '12042005', '0867928978', 'user'),
(21, 'jj', 'ff', 'sutasinee8978@gmail.com', '$2y$10$PxAk5n8mVOkhoaWnWrWShOtCg1z6A1vJA5kOke7kx0QHAXTFvuipG', '0814316962', 'user'),
(22, 'jj', 'ff', 'jj@example.com', '10031204', '0867928978', 'user'),
(24, 'tan', 'tan', 'neem8978@gmail.com', '$2y$10$DrhOJW0oXH2f4puYeyv0QeX3mDBC8NfeLXq6tFqoJEZ5IxJBlKaEq', '0867928989', 'user');

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
-- Indexes for table `booking_seats`
--
ALTER TABLE `booking_seats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking_seat` (`bookingid`,`seatid`),
  ADD KEY `idx_bookingid` (`bookingid`),
  ADD KEY `idx_seatid` (`seatid`);

--
-- Indexes for table `circuits`
--
ALTER TABLE `circuits`
  ADD PRIMARY KEY (`circuitid`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`highlight_id`);

--
-- Indexes for table `live_timing`
--
ALTER TABLE `live_timing`
  ADD PRIMARY KEY (`live_id`),
  ADD KEY `fk_live_race` (`race_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

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
  MODIFY `bookingid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `booking_seats`
--
ALTER TABLE `booking_seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `circuits`
--
ALTER TABLE `circuits`
  MODIFY `circuitid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `highlights`
--
ALTER TABLE `highlights`
  MODIFY `highlight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `live_timing`
--
ALTER TABLE `live_timing`
  MODIFY `live_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
  MODIFY `seatid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticketid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Constraints for table `live_timing`
--
ALTER TABLE `live_timing`
  ADD CONSTRAINT `fk_live_race` FOREIGN KEY (`race_id`) REFERENCES `races` (`raceid`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticketid`) ON DELETE SET NULL;

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
