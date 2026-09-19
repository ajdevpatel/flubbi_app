-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 14, 2026 at 05:04 AM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u865517099_flubbi`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_history`
--

CREATE TABLE `application_history` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL DEFAULT 0,
  `status_id` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_history`
--

INSERT INTO `application_history` (`id`, `application_id`, `status_id`, `remarks`, `created_at`) VALUES
(1, 72, 2, '', '2026-04-15 07:57:42'),
(2, 72, 3, 'rrrr', '2026-04-15 08:00:51'),
(3, 72, 4, 'xxx', '2026-04-16 07:56:28'),
(4, 25, 4, 'xxx', '2026-05-28 06:32:28'),
(5, 30, 5, 'your file is approved', '2026-05-28 06:35:22');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `pl_rate` decimal(5,2) NOT NULL COMMENT 'Personal Loan Rate',
  `bl_rate` decimal(5,2) NOT NULL COMMENT 'Business Loan Rate',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `logo`, `pl_rate`, `bl_rate`, `status`) VALUES
(1, 'HDFC Bank', 'banks/hdfc.png', 10.50, 12.00, 1),
(2, 'ICICI Bank', 'banks/icici.png', 10.75, 12.25, 1),
(3, 'Axis Bank', 'banks/axis.png', 11.00, 12.50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cibil_scores`
--

CREATE TABLE `cibil_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cibil_scores`
--

INSERT INTO `cibil_scores` (`id`, `label`, `status`) VALUES
(1, '550 - 650', 1),
(2, '651 - 750', 1),
(3, '751 - 850', 1),
(4, 'Above 850', 1);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `state_id`, `name`, `status`) VALUES
(1, 7, 'Ahmedabad', 0),
(2, 7, 'Amreli', 0),
(3, 7, 'Anand', 0),
(4, 7, 'Aravalli', 0),
(5, 7, 'Banaskantha', 0),
(6, 7, 'Bharuch', 0),
(7, 7, 'Bhavnagar', 0),
(8, 7, 'Botad', 0),
(9, 7, 'Chhota Udaipur', 0),
(10, 7, 'Dahod', 0),
(11, 7, 'Dang', 0),
(12, 7, 'Devbhoomi Dwarka', 0),
(13, 7, 'Gandhinagar', 0),
(14, 7, 'Gir Somnath', 0),
(15, 7, 'Jamnagar', 0),
(16, 7, 'Junagadh', 0),
(17, 7, 'Kheda', 0),
(18, 7, 'Kutch', 0),
(19, 7, 'Mahisagar', 0),
(20, 7, 'Mehsana', 0),
(21, 7, 'Morbi', 0),
(22, 7, 'Narmada', 0),
(23, 7, 'Navsari', 0),
(24, 7, 'Panchmahal', 0),
(25, 7, 'Patan', 0),
(26, 7, 'Porbandar', 0),
(27, 7, 'Rajkot', 0),
(28, 7, 'Sabarkantha', 0),
(29, 7, 'Surat', 0),
(30, 7, 'Surendranagar', 0),
(31, 7, 'Tapi', 0),
(32, 7, 'Vadodara', 0),
(33, 7, 'Valsad', 0),
(34, 14, 'Ahmednagar', 0),
(35, 14, 'Akola', 0),
(36, 14, 'Amravati', 0),
(37, 14, 'Aurangabad', 0),
(38, 14, 'Beed', 0),
(39, 14, 'Bhandara', 0),
(40, 14, 'Buldhana', 0),
(41, 14, 'Chandrapur', 0),
(42, 14, 'Dhule', 0),
(43, 14, 'Gadchiroli', 0),
(44, 14, 'Gondia', 0),
(45, 14, 'Hingoli', 0),
(46, 14, 'Jalgaon', 0),
(47, 14, 'Jalna', 0),
(48, 14, 'Kolhapur', 0),
(49, 14, 'Latur', 0),
(50, 14, 'Mumbai City', 0),
(51, 14, 'Mumbai Suburban', 0),
(52, 14, 'Nagpur', 0),
(53, 14, 'Nanded', 0),
(54, 14, 'Nandurbar', 0),
(55, 14, 'Nashik', 0),
(56, 14, 'Osmanabad', 0),
(57, 14, 'Palghar', 0),
(58, 14, 'Parbhani', 0),
(59, 14, 'Pune', 0),
(60, 14, 'Raigad', 0),
(61, 14, 'Ratnagiri', 0),
(62, 14, 'Sangli', 0),
(63, 14, 'Satara', 0),
(64, 14, 'Sindhudurg', 0),
(65, 14, 'Solapur', 0),
(66, 14, 'Thane', 0),
(67, 14, 'Wardha', 0),
(68, 14, 'Washim', 0),
(69, 14, 'Yavatmal', 0),
(70, 1, 'Alluri Sitharama Raju', 0),
(71, 1, 'Anakapalli', 0),
(72, 1, 'Anantapur', 0),
(73, 1, 'Annamayya', 0),
(74, 1, 'Bapatla', 0),
(75, 1, 'Chittoor', 0),
(76, 1, 'Dr. B.R. Ambedkar Konaseema', 0),
(77, 1, 'East Godavari', 0),
(78, 1, 'Eluru', 0),
(79, 1, 'Guntur', 0),
(80, 1, 'Kakinada', 0),
(81, 1, 'Krishna', 0),
(82, 1, 'Kurnool', 0),
(83, 1, 'Nandyal', 0),
(84, 1, 'NTR', 0),
(85, 1, 'Palnadu', 0),
(86, 1, 'Parvathipuram Manyam', 0),
(87, 1, 'Prakasam', 0),
(88, 1, 'SPSR Nellore', 0),
(89, 1, 'Sri Sathya Sai', 0),
(90, 1, 'Srikakulam', 0),
(91, 1, 'Tirupati', 0),
(92, 1, 'Visakhapatnam', 0),
(93, 1, 'Vizianagaram', 0),
(94, 1, 'West Godavari', 0),
(95, 1, 'YSR Kadapa', 0),
(96, 2, 'Anjaw', 0),
(97, 2, 'Capital Complex Itanagar', 0),
(98, 2, 'Changlang', 0),
(99, 2, 'Dibang Valley', 0),
(100, 2, 'East Kameng', 0),
(101, 2, 'East Siang', 0),
(102, 2, 'Kamle', 0),
(103, 2, 'Kra Daadi', 0),
(104, 2, 'Kurung Kumey', 0),
(105, 2, 'Lepa Rada', 0),
(106, 2, 'Lohit', 0),
(107, 2, 'Longding', 0),
(108, 2, 'Lower Dibang Valley', 0),
(109, 2, 'Lower Siang', 0),
(110, 2, 'Lower Subansiri', 0),
(111, 2, 'Namsai', 0),
(112, 2, 'Pakke Kessang', 0),
(113, 2, 'Papum Pare', 0),
(114, 2, 'Shi Yomi', 0),
(115, 2, 'Siang', 0),
(116, 2, 'Tawang', 0),
(117, 2, 'Tirap', 0),
(118, 2, 'Upper Dibang Valley', 0),
(119, 2, 'Upper Siang', 0),
(120, 2, 'Upper Subansiri', 0),
(121, 2, 'West Kameng', 0),
(122, 2, 'West Siang', 0),
(123, 3, 'Baksa', 0),
(124, 3, 'Barpeta', 0),
(125, 3, 'Biswanath', 0),
(126, 3, 'Bongaigaon', 0),
(127, 3, 'Cachar', 0),
(128, 3, 'Charaideo', 0),
(129, 3, 'Chirang', 0),
(130, 3, 'Darrang', 0),
(131, 3, 'Dhemaji', 0),
(132, 3, 'Dhubri', 0),
(133, 3, 'Dibrugarh', 0),
(134, 3, 'Dima Hasao', 0),
(135, 3, 'Goalpara', 0),
(136, 3, 'Golaghat', 0),
(137, 3, 'Hailakandi', 0),
(138, 3, 'Hojai', 0),
(139, 3, 'Jorhat', 0),
(140, 3, 'Kamrup', 0),
(141, 3, 'Kamrup Metropolitan', 0),
(142, 3, 'Karbi Anglong', 0),
(143, 3, 'West Karbi Anglong', 0),
(144, 3, 'Kokrajhar', 0),
(145, 3, 'Lakhimpur', 0),
(146, 3, 'Majuli', 0),
(147, 3, 'Morigaon', 0),
(148, 3, 'Nagaon', 0),
(149, 3, 'Nalbari', 0),
(150, 3, 'Sivasagar', 0),
(151, 3, 'Sonitpur', 0),
(152, 3, 'South Salmara-Mankachar', 0),
(153, 3, 'Sribhumi', 0),
(154, 3, 'Tinsukia', 0),
(155, 3, 'Udalguri', 0),
(156, 4, 'Araria', 0),
(157, 4, 'Arwal', 0),
(158, 4, 'Aurangabad', 0),
(159, 4, 'Banka', 0),
(160, 4, 'Begusarai', 0),
(161, 4, 'Bhagalpur', 0),
(162, 4, 'Bhojpur', 0),
(163, 4, 'Buxar', 0),
(164, 4, 'Darbhanga', 0),
(165, 4, 'East Champaran', 0),
(166, 4, 'Gaya', 0),
(167, 4, 'Gopalganj', 0),
(168, 4, 'Jamui', 0),
(169, 4, 'Jehanabad', 0),
(170, 4, 'Kaimur (Bhabua)', 0),
(171, 4, 'Katihar', 0),
(172, 4, 'Khagaria', 0),
(173, 4, 'Kishanganj', 0),
(174, 4, 'Lakhisarai', 0),
(175, 4, 'Madhepura', 0),
(176, 4, 'Madhubani', 0),
(177, 4, 'Munger', 0),
(178, 4, 'Muzaffarpur', 0),
(179, 4, 'Nalanda', 0),
(180, 4, 'Nawada', 0),
(181, 4, 'Patna', 0),
(182, 4, 'Purnia', 0),
(183, 4, 'Rohtas', 0),
(184, 4, 'Saharsa', 0),
(185, 4, 'Samastipur', 0),
(186, 4, 'Saran', 0),
(187, 4, 'Sheikhpura', 0),
(188, 4, 'Sheohar', 0),
(189, 4, 'Sitamarhi', 0),
(190, 4, 'Siwan', 0),
(191, 4, 'Supaul', 0),
(192, 4, 'Vaishali', 0),
(193, 4, 'West Champaran', 0),
(194, 5, 'Balod', 0),
(195, 5, 'Baloda Bazar', 0),
(196, 5, 'Balrampur', 0),
(197, 5, 'Bastar', 0),
(198, 5, 'Bemetara', 0),
(199, 5, 'Bijapur', 0),
(200, 5, 'Bilaspur', 0),
(201, 5, 'Dantewada', 0),
(202, 5, 'Dhamtari', 0),
(203, 5, 'Durg', 0),
(204, 5, 'Gariaband', 0),
(205, 5, 'Gaurela-Pendra-Marwahi', 0),
(206, 5, 'Janjgir-Champa', 0),
(207, 5, 'Jashpur', 0),
(208, 5, 'Kabirdham (Kawardha)', 0),
(209, 5, 'Kanker', 0),
(210, 5, 'Khairagarh-Chhuikhadan-Gandai', 0),
(211, 5, 'Kondagaon', 0),
(212, 5, 'Korba', 0),
(213, 5, 'Koriya', 0),
(214, 5, 'Mahasamund', 0),
(215, 5, 'Manendragarh-Chirmiri-Bharatpur', 0),
(216, 5, 'Mohla-Manpur-Ambagarh Chowki', 0),
(217, 5, 'Mungeli', 0),
(218, 5, 'Narayanpur', 0),
(219, 5, 'Raigarh', 0),
(220, 5, 'Raipur', 0),
(221, 5, 'Rajnandgaon', 0),
(222, 5, 'Sarangarh-Bilaigarh', 0),
(223, 5, 'Shakti', 0),
(224, 5, 'Sukma', 0),
(225, 5, 'Surajpur', 0),
(226, 5, 'Surguja', 0),
(227, 6, 'North Goa', 0),
(228, 6, 'South Goa', 0),
(229, 8, 'Ambala', 0),
(230, 8, 'Bhiwani', 0),
(231, 8, 'Charkhi Dadri', 0),
(232, 8, 'Faridabad', 0),
(233, 8, 'Fatehabad', 0),
(234, 8, 'Gurugram', 0),
(235, 8, 'Hisar', 0),
(236, 8, 'Jhajjar', 0),
(237, 8, 'Jind', 0),
(238, 8, 'Kaithal', 0),
(239, 8, 'Karnal', 0),
(240, 8, 'Kurukshetra', 0),
(241, 8, 'Mahendragarh', 0),
(242, 8, 'Nuh', 0),
(243, 8, 'Palwal', 0),
(244, 8, 'Panchkula', 0),
(245, 8, 'Panipat', 0),
(246, 8, 'Rewari', 0),
(247, 8, 'Rohtak', 0),
(248, 8, 'Sirsa', 0),
(249, 8, 'Sonipat', 0),
(250, 8, 'Yamunanagar', 0),
(251, 9, 'Bilaspur', 0),
(252, 9, 'Chamba', 0),
(253, 9, 'Hamirpur', 0),
(254, 9, 'Kangra', 0),
(255, 9, 'Kinnaur', 0),
(256, 9, 'Kullu', 0),
(257, 9, 'Lahaul and Spiti', 0),
(258, 9, 'Mandi', 0),
(259, 9, 'Shimla', 0),
(260, 9, 'Sirmaur', 0),
(261, 9, 'Solan', 0),
(262, 9, 'Una', 0),
(263, 10, 'Bokaro', 0),
(264, 10, 'Chatra', 0),
(265, 10, 'Deoghar', 0),
(266, 10, 'Dhanbad', 0),
(267, 10, 'Dumka', 0),
(268, 10, 'East Singhbhum', 0),
(269, 10, 'Garhwa', 0),
(270, 10, 'Giridih', 0),
(271, 10, 'Godda', 0),
(272, 10, 'Gumla', 0),
(273, 10, 'Hazaribagh', 0),
(274, 10, 'Jamtara', 0),
(275, 10, 'Khunti', 0),
(276, 10, 'Koderma', 0),
(277, 10, 'Latehar', 0),
(278, 10, 'Lohardaga', 0),
(279, 10, 'Pakur', 0),
(280, 10, 'Palamu', 0),
(281, 10, 'Ramgarh', 0),
(282, 10, 'Ranchi', 0),
(283, 10, 'Sahebganj', 0),
(284, 10, 'Seraikela Kharsawan', 0),
(285, 10, 'Simdega', 0),
(286, 10, 'West Singhbhum', 0),
(287, 11, 'Bagalkot', 0),
(288, 11, 'Ballari', 0),
(289, 11, 'Belagavi', 0),
(290, 11, 'Bengaluru Rural', 0),
(291, 11, 'Bengaluru Urban', 0),
(292, 11, 'Bidar', 0),
(293, 11, 'Chamarajanagar', 0),
(294, 11, 'Chikkaballapur', 0),
(295, 11, 'Chikkamagaluru', 0),
(296, 11, 'Chitradurga', 0),
(297, 11, 'Dakshina Kannada', 0),
(298, 11, 'Davanagere', 0),
(299, 11, 'Dharwad', 0),
(300, 11, 'Gadag', 0),
(301, 11, 'Hassan', 0),
(302, 11, 'Haveri', 0),
(303, 11, 'Kalaburagi', 0),
(304, 11, 'Kodagu', 0),
(305, 11, 'Kolar', 0),
(306, 11, 'Koppal', 0),
(307, 11, 'Mandya', 0),
(308, 11, 'Mysuru', 0),
(309, 11, 'Raichur', 0),
(310, 11, 'Ramanagara', 0),
(311, 11, 'Shivamogga', 0),
(312, 11, 'Tumakuru', 0),
(313, 11, 'Udupi', 0),
(314, 11, 'Uttara Kannada', 0),
(315, 11, 'Vijayanagara', 0),
(316, 11, 'Vijayapura', 0),
(317, 11, 'Yadgir', 0),
(318, 12, 'Alappuzha', 0),
(319, 12, 'Ernakulam', 0),
(320, 12, 'Idukki', 0),
(321, 12, 'Kannur', 0),
(322, 12, 'Kasaragod', 0),
(323, 12, 'Kollam', 0),
(324, 12, 'Kottayam', 0),
(325, 12, 'Kozhikode', 0),
(326, 12, 'Malappuram', 0),
(327, 12, 'Palakkad', 0),
(328, 12, 'Pathanamthitta', 0),
(329, 12, 'Thiruvananthapuram', 0),
(330, 12, 'Thrissur', 0),
(331, 12, 'Wayanad', 0),
(332, 13, 'Agar Malwa', 0),
(333, 13, 'Alirajpur', 0),
(334, 13, 'Anuppur', 0),
(335, 13, 'Ashoknagar', 0),
(336, 13, 'Balaghat', 0),
(337, 13, 'Barwani', 0),
(338, 13, 'Betul', 0),
(339, 13, 'Bhind', 0),
(340, 13, 'Bhopal', 0),
(341, 13, 'Burhanpur', 0),
(342, 13, 'Chhatarpur', 0),
(343, 13, 'Chhindwara', 0),
(344, 13, 'Damoh', 0),
(345, 13, 'Datia', 0),
(346, 13, 'Dewas', 0),
(347, 13, 'Dhar', 0),
(348, 13, 'Dindori', 0),
(349, 13, 'Guna', 0),
(350, 13, 'Gwalior', 0),
(351, 13, 'Harda', 0),
(352, 13, 'Narmadapuram', 0),
(353, 13, 'Indore', 0),
(354, 13, 'Jabalpur', 0),
(355, 13, 'Jhabua', 0),
(356, 13, 'Katni', 0),
(357, 13, 'Khandwa', 0),
(358, 13, 'Khargone', 0),
(359, 13, 'Maihar', 0),
(360, 13, 'Mandla', 0),
(361, 13, 'Mandsaur', 0),
(362, 13, 'Morena', 0),
(363, 13, 'Narsinghpur', 0),
(364, 13, 'Neemuch', 0),
(365, 13, 'Niwari', 0),
(366, 13, 'Panna', 0),
(367, 13, 'Raisen', 0),
(368, 13, 'Rajgarh', 0),
(369, 13, 'Ratlam', 0),
(370, 13, 'Rewa', 0),
(371, 13, 'Sagar', 0),
(372, 13, 'Satna', 0),
(373, 13, 'Sehore', 0),
(374, 13, 'Seoni', 0),
(375, 13, 'Shahdol', 0),
(376, 13, 'Shajapur', 0),
(377, 13, 'Sheopur', 0),
(378, 13, 'Shivpuri', 0),
(379, 13, 'Sidhi', 0),
(380, 13, 'Singrauli', 0),
(381, 13, 'Tikamgarh', 0),
(382, 13, 'Ujjain', 0),
(383, 13, 'Umaria', 0),
(384, 13, 'Vidisha', 0),
(385, 15, 'Bishnupur', 0),
(386, 15, 'Chandel', 0),
(387, 15, 'Churachandpur', 0),
(388, 15, 'Imphal East', 0),
(389, 15, 'Imphal West', 0),
(390, 15, 'Jiribam', 0),
(391, 15, 'Kakching', 0),
(392, 15, 'Kamjong', 0),
(393, 15, 'Kangpokpi', 0),
(394, 15, 'Noney', 0),
(395, 15, 'Pherzawl', 0),
(396, 15, 'Senapati', 0),
(397, 15, 'Tamenglong', 0),
(398, 15, 'Tengnoupal', 0),
(399, 15, 'Thoubal', 0),
(400, 15, 'Ukhrul', 0),
(401, 16, 'East Garo Hills', 0),
(402, 16, 'East Jaintia Hills', 0),
(403, 16, 'East Khasi Hills', 0),
(404, 16, 'North Garo Hills', 0),
(405, 16, 'Ri-Bhoi', 0),
(406, 16, 'South Garo Hills', 0),
(407, 16, 'South West Garo Hills', 0),
(408, 16, 'South West Khasi Hills', 0),
(409, 16, 'West Garo Hills', 0),
(410, 16, 'West Jaintia Hills', 0),
(411, 16, 'West Khasi Hills', 0),
(412, 16, 'Eastern West Khasi Hills', 0),
(413, 17, 'Aizawl', 0),
(414, 17, 'Champhai', 0),
(415, 17, 'Kolasib', 0),
(416, 17, 'Lawngtlai', 0),
(417, 17, 'Lunglei', 0),
(418, 17, 'Mamit', 0),
(419, 17, 'Saiha', 0),
(420, 17, 'Serchhip', 0),
(421, 17, 'Hnahthial', 0),
(422, 17, 'Saitual', 0),
(423, 17, 'Khawzawl', 0),
(424, 18, 'Dimapur', 0),
(425, 18, 'Kiphire', 0),
(426, 18, 'Kohima', 0),
(427, 18, 'Longleng', 0),
(428, 18, 'Mokokchung', 0),
(429, 18, 'Mon', 0),
(430, 18, 'Peren', 0),
(431, 18, 'Phek', 0),
(432, 18, 'Tuensang', 0),
(433, 18, 'Wokha', 0),
(434, 18, 'Zunheboto', 0),
(435, 18, 'Noklak', 0),
(436, 19, 'Angul', 0),
(437, 19, 'Balangir', 0),
(438, 19, 'Balasore', 0),
(439, 19, 'Bargarh', 0),
(440, 19, 'Bhadrak', 0),
(441, 19, 'Boudh', 0),
(442, 19, 'Cuttack', 0),
(443, 19, 'Deogarh', 0),
(444, 19, 'Dhenkanal', 0),
(445, 19, 'Gajapati', 0),
(446, 19, 'Ganjam', 0),
(447, 19, 'Jagatsinghpur', 0),
(448, 19, 'Jajpur', 0),
(449, 19, 'Jharsuguda', 0),
(450, 19, 'Kalahandi', 0),
(451, 19, 'Kandhamal', 0),
(452, 19, 'Kendrapara', 0),
(453, 19, 'Kendujhar', 0),
(454, 19, 'Khordha', 0),
(455, 19, 'Koraput', 0),
(456, 19, 'Malkangiri', 0),
(457, 19, 'Mayurbhanj', 0),
(458, 19, 'Nabarangpur', 0),
(459, 19, 'Nuapada', 0),
(460, 19, 'Puri', 0),
(461, 19, 'Rayagada', 0),
(462, 19, 'Sambalpur', 0),
(463, 19, 'Sonepur', 0),
(464, 19, 'Sundargarh', 0),
(465, 19, 'Deogarh (New)', 0),
(466, 20, 'Amritsar', 0),
(467, 20, 'Barnala', 0),
(468, 20, 'Bathinda', 0),
(469, 20, 'Faridkot', 0),
(470, 20, 'Fatehgarh Sahib', 0),
(471, 20, 'Fazilka', 0),
(472, 20, 'Firozpur', 0),
(473, 20, 'Gurdaspur', 0),
(474, 20, 'Hoshiarpur', 0),
(475, 20, 'Jalandhar', 0),
(476, 20, 'Kapurthala', 0),
(477, 20, 'Ludhiana', 0),
(478, 20, 'Mansa', 0),
(479, 20, 'Moga', 0),
(480, 20, 'Mohali (S.A.S. Nagar)', 0),
(481, 20, 'Muktsar', 0),
(482, 20, 'Pathankot', 0),
(483, 20, 'Patiala', 0),
(484, 20, 'Rupnagar', 0),
(485, 20, 'Sangrur', 0),
(486, 20, 'Nawanshahr', 0),
(487, 20, 'S.A.S. Nagar', 0),
(488, 20, 'Tarn Taran', 0),
(489, 21, 'Ajmer', 0),
(490, 21, 'Alwar', 0),
(491, 21, 'Banswara', 0),
(492, 21, 'Baran', 0),
(493, 21, 'Barmer', 0),
(494, 21, 'Bharatpur', 0),
(495, 21, 'Bhilwara', 0),
(496, 21, 'Bikaner', 0),
(497, 21, 'Bundi', 0),
(498, 21, 'Chittorgarh', 0),
(499, 21, 'Churu', 0),
(500, 21, 'Dausa', 0),
(501, 21, 'Dholpur', 0),
(502, 21, 'Dungarpur', 0),
(503, 21, 'Hanumangarh', 0),
(504, 21, 'Jaipur', 0),
(505, 21, 'Jaisalmer', 0),
(506, 21, 'Jalore', 0),
(507, 21, 'Jhalawar', 0),
(508, 21, 'Jhunjhunu', 0),
(509, 21, 'Jodhpur', 0),
(510, 21, 'Karauli', 0),
(511, 21, 'Kota', 0),
(512, 21, 'Nagaur', 0),
(513, 21, 'Pali', 0),
(514, 21, 'Pratapgarh', 0),
(515, 21, 'Rajsamand', 0),
(516, 21, 'Sawai Madhopur', 0),
(517, 21, 'Sikar', 0),
(518, 21, 'Sirohi', 0),
(519, 21, 'Sri Ganganagar', 0),
(520, 21, 'Tonk', 0),
(521, 21, 'Udaipur', 0),
(522, 22, 'East Sikkim', 0),
(523, 22, 'North Sikkim', 0),
(524, 22, 'South Sikkim', 0),
(525, 22, 'West Sikkim', 0),
(526, 22, 'Pakyong', 0),
(527, 22, 'Soreng', 0),
(528, 23, 'Ariyalur', 0),
(529, 23, 'Chengalpattu', 0),
(530, 23, 'Chennai', 0),
(531, 23, 'Coimbatore', 0),
(532, 23, 'Cuddalore', 0),
(533, 23, 'Dharmapuri', 0),
(534, 23, 'Dindigul', 0),
(535, 23, 'Erode', 0),
(536, 23, 'Kallakurichi', 0),
(537, 23, 'Kancheepuram', 0),
(538, 23, 'Karur', 0),
(539, 23, 'Krishnagiri', 0),
(540, 23, 'Madurai', 0),
(541, 23, 'Nagapattinam', 0),
(542, 23, 'Namakkal', 0),
(543, 23, 'Nilgiris', 0),
(544, 23, 'Perambalur', 0),
(545, 23, 'Pudukkottai', 0),
(546, 23, 'Ramanathapuram', 0),
(547, 23, 'Ranipet', 0),
(548, 23, 'Salem', 0),
(549, 23, 'Sivaganga', 0),
(550, 23, 'Tenkasi', 0),
(551, 23, 'Thanjavur', 0),
(552, 23, 'The Nilgiris', 0),
(553, 23, 'Theni', 0),
(554, 23, 'Thiruvallur', 0),
(555, 23, 'Thiruvarur', 0),
(556, 23, 'Thoothukudi', 0),
(557, 23, 'Tiruchirappalli', 0),
(558, 23, 'Tirunelveli', 0),
(559, 23, 'Tirupattur', 0),
(560, 23, 'Tiruppur', 0),
(561, 23, 'Tiruvannamalai', 0),
(562, 23, 'Vellore', 0),
(563, 23, 'Viluppuram', 0),
(564, 23, 'Virudhunagar', 0),
(565, 23, 'Kallakurichi', 0),
(566, 24, 'Adilabad', 0),
(567, 24, 'Bhadradri Kothagudem', 0),
(568, 24, 'Hyderabad', 0),
(569, 24, 'Jagtial', 0),
(570, 24, 'Jangaon', 0),
(571, 24, 'Jayashankar Bhupalpally', 0),
(572, 24, 'Jogulamba Gadwal', 0),
(573, 24, 'Kamareddy', 0),
(574, 24, 'Karimnagar', 0),
(575, 24, 'Khammam', 0),
(576, 24, 'Komaram Bheem Asifabad', 0),
(577, 24, 'Mahabubabad', 0),
(578, 24, 'Mahabubnagar', 0),
(579, 24, 'Mancherial', 0),
(580, 24, 'Medak', 0),
(581, 24, 'Medchal–Malkajgiri', 0),
(582, 24, 'Mulugu', 0),
(583, 24, 'Nagarkurnool', 0),
(584, 24, 'Nalgonda', 0),
(585, 24, 'Narayanpet', 0),
(586, 24, 'Nirmal', 0),
(587, 24, 'Nizamabad', 0),
(588, 24, 'Peddapalli', 0),
(589, 24, 'Rajanna Sircilla', 0),
(590, 24, 'Rangareddy', 0),
(591, 24, 'Sangareddy', 0),
(592, 24, 'Siddipet', 0),
(593, 24, 'Suryapet', 0),
(594, 24, 'Vikarabad', 0),
(595, 24, 'Wanaparthy', 0),
(596, 24, 'Warangal Rural', 0),
(597, 24, 'Warangal Urban', 0),
(598, 24, 'Yadadri Bhuvanagiri', 0),
(599, 25, 'Dhalai', 0),
(600, 25, 'Gomati', 0),
(601, 25, 'Khowai', 0),
(602, 25, 'North Tripura', 0),
(603, 25, 'Sepahijala', 0),
(604, 25, 'South Tripura', 0),
(605, 25, 'Unakoti', 0),
(606, 25, 'West Tripura', 0),
(607, 26, 'Agra', 0),
(608, 26, 'Aligarh', 0),
(609, 26, 'Allahabad (Prayagraj)', 0),
(610, 26, 'Ambedkar Nagar', 0),
(611, 26, 'Amethi', 0),
(612, 26, 'Amroha', 0),
(613, 26, 'Auraiya', 0),
(614, 26, 'Azamgarh', 0),
(615, 26, 'Baghpat', 0),
(616, 26, 'Bahraich', 0),
(617, 26, 'Ballia', 0),
(618, 26, 'Balrampur', 0),
(619, 26, 'Banda', 0),
(620, 26, 'Barabanki', 0),
(621, 26, 'Bareilly', 0),
(622, 26, 'Basti', 0),
(623, 26, 'Bhadohi', 0),
(624, 26, 'Bijnor', 0),
(625, 26, 'Budaun', 0),
(626, 26, 'Bulandshahr', 0),
(627, 26, 'Chandauli', 0),
(628, 26, 'Chitrakoot', 0),
(629, 26, 'Deoria', 0),
(630, 26, 'Etah', 0),
(631, 26, 'Etawah', 0),
(632, 26, 'Faizabad (Ayodhya)', 0),
(633, 26, 'Farrukhabad', 0),
(634, 26, 'Fatehpur', 0),
(635, 26, 'Firozabad', 0),
(636, 26, 'Gautam Buddha Nagar', 0),
(637, 26, 'Ghaziabad', 0),
(638, 26, 'Ghazipur', 0),
(639, 26, 'Gonda', 0),
(640, 26, 'Gorakhpur', 0),
(641, 26, 'Hamirpur', 0),
(642, 26, 'Hapur', 0),
(643, 26, 'Hardoi', 0),
(644, 26, 'Hathras', 0),
(645, 26, 'Jalaun', 0),
(646, 26, 'Jaunpur', 0),
(647, 26, 'Jhansi', 0),
(648, 26, 'Kannauj', 0),
(649, 26, 'Kanpur Dehat', 0),
(650, 26, 'Kanpur Nagar', 0),
(651, 26, 'Kasganj', 0),
(652, 26, 'Kaushambi', 0),
(653, 26, 'Kheri (Lakhimpur Kheri)', 0),
(654, 26, 'Kushinagar', 0),
(655, 26, 'Maharajganj', 0),
(656, 26, 'Mahoba', 0),
(657, 26, 'Mainpuri', 0),
(658, 26, 'Mathura', 0),
(659, 26, 'Mau', 0),
(660, 26, 'Meerut', 0),
(661, 26, 'Mirzapur', 0),
(662, 26, 'Moradabad', 0),
(663, 26, 'Muzaffarnagar', 0),
(664, 26, 'Pilibhit', 0),
(665, 26, 'Pratapgarh', 0),
(666, 26, 'Rae Bareli', 0),
(667, 26, 'Rampur', 0),
(668, 26, 'Saharanpur', 0),
(669, 26, 'Sambhal', 0),
(670, 26, 'Sant Kabir Nagar', 0),
(671, 26, 'Shahjahanpur', 0),
(672, 26, 'Shamli', 0),
(673, 26, 'Shravasti', 0),
(674, 26, 'Siddharthnagar', 0),
(675, 26, 'Sitapur', 0),
(676, 26, 'Sonbhadra', 0),
(677, 26, 'Sultanpur', 0),
(678, 26, 'Unnao', 0),
(679, 26, 'Varanasi', 0),
(680, 26, 'Amroha', 0),
(681, 26, 'Hapur', 0),
(682, 26, 'Chitrakoot', 0),
(683, 26, 'Kaushambi', 0),
(684, 26, 'Auraiya', 0),
(685, 26, 'Kasganj', 0),
(686, 26, 'Budaun', 0),
(687, 26, 'Farrukhabad', 0),
(688, 26, 'Mahoba', 0),
(689, 26, 'Shamli', 0),
(690, 26, 'Sambhal', 0),
(691, 26, 'Amethi', 0),
(692, 26, 'Hathras', 0),
(693, 26, 'Prayagraj', 0),
(694, 26, 'Kaushambi', 0),
(695, 26, 'Saharanpur', 0),
(696, 26, 'Ayodhya', 0),
(697, 26, 'Chandauli', 0),
(698, 26, 'Balrampur', 0),
(699, 26, 'Sant Kabir Nagar', 0),
(700, 26, 'Basti', 0),
(701, 26, 'Sant Ravidas Nagar', 0),
(702, 26, 'Mirzapur', 0),
(703, 26, 'Bhadohi', 0),
(704, 26, 'Fatehpur', 0),
(705, 26, 'Bijnor', 0),
(706, 26, 'Gautam Buddha Nagar', 0),
(707, 26, 'Shrawasti', 0),
(708, 26, 'Amroha', 0),
(709, 26, 'Hapur', 0),
(710, 26, 'Kasganj', 0),
(711, 26, 'Balrampur', 0),
(712, 26, 'Banda', 0),
(713, 26, 'Chitrakoot', 0),
(714, 26, 'Kaushambi', 0),
(715, 27, 'Almora', 0),
(716, 27, 'Bageshwar', 0),
(717, 27, 'Chamoli', 0),
(718, 27, 'Champawat', 0),
(719, 27, 'Dehradun', 0),
(720, 27, 'Haridwar', 0),
(721, 27, 'Nainital', 0),
(722, 27, 'Pauri Garhwal', 0),
(723, 27, 'Pithoragarh', 0),
(724, 27, 'Rudraprayag', 0),
(725, 27, 'Tehri Garhwal', 0),
(726, 27, 'Udham Singh Nagar', 0),
(727, 27, 'Uttarkashi', 0),
(728, 28, 'Alipurduar', 0),
(729, 28, 'Bankura', 0),
(730, 28, 'Birbhum', 0),
(731, 28, 'Cooch Behar', 0),
(732, 28, 'Dakshin Dinajpur', 0),
(733, 28, 'Darjeeling', 0),
(734, 28, 'Hooghly', 0),
(735, 28, 'Howrah', 0),
(736, 28, 'Jalpaiguri', 0),
(737, 28, 'Jhargram', 0),
(738, 28, 'Kalimpong', 0),
(739, 28, 'Kolkata', 0),
(740, 28, 'Malda', 0),
(741, 28, 'Murshidabad', 0),
(742, 28, 'Nadia', 0),
(743, 28, 'North 24 Parganas', 0),
(744, 28, 'Paschim Bardhaman', 0),
(745, 28, 'Paschim Medinipur', 0),
(746, 28, 'Purba Bardhaman', 0),
(747, 28, 'Purba Medinipur', 0),
(748, 28, 'Purulia', 0),
(749, 28, 'South 24 Parganas', 0),
(750, 28, 'Uttar Dinajpur', 0),
(751, 29, 'North and Middle Andaman', 0),
(752, 29, 'South Andaman', 0),
(753, 29, 'Nicobar', 0),
(754, 30, 'Chandigarh', 0),
(755, 31, 'Dadra and Nagar Haveli', 0),
(756, 31, 'Daman', 0),
(757, 31, 'Diu', 0),
(758, 32, 'Central Delhi', 0),
(759, 32, 'East Delhi', 0),
(760, 32, 'New Delhi', 0),
(761, 32, 'North Delhi', 0),
(762, 32, 'North East Delhi', 0),
(763, 32, 'North West Delhi', 0),
(764, 32, 'Shahdara', 0),
(765, 32, 'South Delhi', 0),
(766, 32, 'South East Delhi', 0),
(767, 32, 'South West Delhi', 0),
(768, 32, 'West Delhi', 0),
(769, 33, 'Anantnag', 0),
(770, 33, 'Bandipora', 0),
(771, 33, 'Baramulla', 0),
(772, 33, 'Budgam', 0),
(773, 33, 'Doda', 0),
(774, 33, 'Ganderbal', 0),
(775, 33, 'Jammu', 0),
(776, 33, 'Kathua', 0),
(777, 33, 'Kishtwar', 0),
(778, 33, 'Kulgam', 0),
(779, 33, 'Kupwara', 0),
(780, 33, 'Poonch', 0),
(781, 33, 'Pulwama', 0),
(782, 33, 'Rajouri', 0),
(783, 33, 'Ramban', 0),
(784, 33, 'Reasi', 0),
(785, 33, 'Samba', 0),
(786, 33, 'Shopian', 0),
(787, 33, 'Srinagar', 0),
(788, 33, 'Udhampur', 0),
(789, 34, 'Kargil', 0),
(790, 34, 'Leh', 0),
(791, 34, 'Nubra', 0),
(792, 34, 'Zanskar', 0),
(793, 34, 'Drass', 0),
(794, 34, 'Khalsi', 0),
(795, 34, 'Sankoo', 0),
(796, 35, 'Lakshadweep', 0),
(797, 36, 'Karaikal', 0),
(798, 36, 'Mahe', 0),
(799, 36, 'Puducherry', 0),
(800, 36, 'Yanam', 0);

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `step` int(11) NOT NULL DEFAULT 1,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `loan_type_id` int(11) NOT NULL DEFAULT 0,
  `loan_purpose_id` int(11) NOT NULL DEFAULT 0,
  `application_no` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT 0,
  `city` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `pan_card` varchar(255) DEFAULT NULL,
  `employment_type` enum('salaried','self_employed') DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `existing_emi` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cibil_score` int(11) DEFAULT NULL,
  `tenure_months` int(11) NOT NULL DEFAULT 0,
  `eligible_amount` decimal(12,2) DEFAULT NULL,
  `interest_rate` decimal(5,2) DEFAULT NULL,
  `emi_amount` decimal(12,2) DEFAULT NULL,
  `eligibility_status` enum('pending','eligible','rejected') NOT NULL DEFAULT 'pending',
  `status` int(11) NOT NULL DEFAULT 1,
  `rejection_reason` text DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `aadhar_number` varchar(255) DEFAULT NULL,
  `aadhar_front` varchar(255) DEFAULT NULL,
  `aadhar_back` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `pan_front` varchar(255) DEFAULT NULL,
  `selfie` varchar(255) DEFAULT NULL,
  `business_type` enum('manufacture','trader','service') DEFAULT NULL,
  `business_age` int(11) DEFAULT NULL,
  `business_identity_proof` enum('gst','msme','trade_license','other') DEFAULT NULL,
  `bank_account` enum('current_account','saving_account') DEFAULT NULL,
  `annual_business_turnover` int(11) DEFAULT NULL,
  `marital_status` enum('married','unmarried') DEFAULT NULL,
  `job_type` enum('government','private') DEFAULT NULL,
  `work_experience` int(11) DEFAULT NULL,
  `credit_card_usage` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `login_type` enum('self','consultant') DEFAULT NULL,
  `self_login_bank_id` int(11) DEFAULT NULL,
  `payment_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0-pending, 1-success, 2-faield',
  `is_converted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0-no, 1-yes, seft to hire consultant',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `step`, `user_id`, `loan_type_id`, `loan_purpose_id`, `application_no`, `name`, `email`, `dob`, `gender`, `address`, `state_id`, `city`, `pincode`, `pan_card`, `employment_type`, `monthly_income`, `existing_emi`, `cibil_score`, `tenure_months`, `eligible_amount`, `interest_rate`, `emi_amount`, `eligibility_status`, `status`, `rejection_reason`, `applied_at`, `approved_at`, `aadhar_number`, `aadhar_front`, `aadhar_back`, `pan_number`, `pan_front`, `selfie`, `business_type`, `business_age`, `business_identity_proof`, `bank_account`, `annual_business_turnover`, `marital_status`, `job_type`, `work_experience`, `credit_card_usage`, `bank_name`, `bank_branch`, `ifsc_code`, `account_number`, `login_type`, `self_login_bank_id`, `payment_status`, `is_converted`, `created_at`, `updated_at`) VALUES
(1, 8, 26, 1, 2, 'LN-20260428-I6GML2', NULL, NULL, '1995-01-01', NULL, 'dbjsjeje', 0, NULL, NULL, NULL, 'salaried', 29484848.00, 23000.00, 2, 36, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-04-28 08:06:04', NULL, '646464664646', '1_aadhar_front_518253.jpg', '1_aadhar_back_512878.jpg', 'AYOPV7789K', '1_pan_front_699463.jpg', '1_selfie_106952.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 2, 1, 'hdfc', 'jdjdjd', 'HFFC0002128', '4848484848484825818', 'self', 3, 1, 0, '2026-04-28 08:06:04', '2026-04-28 08:08:46'),
(2, 8, 27, 2, 4, 'LN-20260430-OTV1SF', NULL, NULL, '1990-01-28', NULL, 'nhdhj shshdj hahdj didjdj hdhdu', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 5000.00, 2, 36, 355785.00, 12.55, NULL, 'pending', 2, NULL, '2026-04-30 18:32:31', NULL, '965485639676', '2_aadhar_front_938541.jpg', '2_aadhar_back_358942.jpg', 'AMZPD3774P', '2_pan_front_232715.jpg', '2_selfie_120056.jpg', 'trader', 5, 'msme', 'current_account', 2, 'married', NULL, NULL, 0, 'HDFC BANK', 'thane west', 'HDFC0001830', '500036956658754', 'self', 2, 1, 0, '2026-04-30 18:32:31', '2026-05-28 18:43:26'),
(3, 8, 27, 1, 2, 'LN-20260430-VKMDP1', NULL, NULL, '1990-01-01', NULL, 'ushsh shsjdj dhshdj shshdj shsjdj', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 2, 24, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-04-30 18:32:49', NULL, '779685468754', '3_aadhar_front_381139.jpg', '3_aadhar_back_659212.jpg', 'CQOPD7277P', '3_pan_front_576258.jpg', '3_selfie_781510.jpg', 'trader', 3, 'msme', 'saving_account', 1, 'married', NULL, NULL, 1, 'icici bank', 'utran', 'ICIC0001032', '103201500115', 'self', 12, 1, 0, '2026-04-30 18:32:49', '2026-05-28 18:54:27'),
(4, 8, 28, 1, 2, 'LN-20260510-3XOTTJ', NULL, NULL, '2000-10-10', NULL, 'dsadsadsad dsad ad dsa dasd dsa dasd sdasds', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 1, 48, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-10 10:29:23', NULL, '885566332200', '4_aadhar_front_373060.jpg', '4_aadhar_back_353006.jpg', 'HLTPS7865J', '4_pan_front_708152.jpg', '4_selfie_528550.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 2, 1, 'HDFC Bank', 'Vastrapur', 'HDFC0001234', '123456789012', 'self', 3, 1, 0, '2026-05-10 10:29:23', '2026-05-24 09:22:39'),
(5, 8, 29, 1, 2, 'LN-20260521-SVAWWJ', NULL, NULL, '1995-01-01', NULL, 'hyfhj hfgj kjvv gcftj jjvbb hgv', 0, NULL, NULL, NULL, 'salaried', 35000.00, 5000.00, 2, 36, 303182.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-21 19:43:32', NULL, '365685889865', '5_aadhar_front_436116.jpg', '5_aadhar_back_559416.jpg', 'AGKPK8568H', '5_pan_front_998918.jpg', '5_selfie_837002.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 2, 1, 'ICICI bank', 'mumbai borivali east', 'ICIC0001032', '103201500115', 'self', 25, 1, 0, '2026-05-21 19:43:32', '2026-05-21 19:59:48'),
(6, 2, 28, 3, 0, 'LN-20260524-WGOJMF', NULL, NULL, '1995-01-01', NULL, 'bxbxhxbd', 0, NULL, NULL, NULL, 'self_employed', 60000.00, 0.00, 3, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-24 09:25:55', NULL, '986869587564', '6_aadhar_front_856512.png', '6_aadhar_back_674208.png', 'HJLUP5683K', '6_pan_front_588097.png', '6_selfie_684177.jpg', 'manufacture', 5, 'msme', 'saving_account', 1, 'unmarried', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-24 09:25:55', '2026-05-24 09:27:34'),
(7, 8, 28, 2, 2, 'LN-20260524-OPC115', NULL, NULL, '1995-01-01', NULL, 'bbzhxhdzh', 0, NULL, NULL, NULL, 'self_employed', 600000.00, 0.00, 3, 36, 792500.00, 12.55, NULL, 'pending', 2, NULL, '2026-05-24 09:27:40', NULL, '376767979947', '7_aadhar_front_125706.png', '7_aadhar_back_244760.png', 'GHUJP6754K', '7_pan_front_574396.png', '7_selfie_318888.jpg', 'trader', 3, 'msme', 'current_account', 1, 'unmarried', NULL, NULL, 0, 'nxjdnz', 'ndgshbsh', 'HDFC0000638', '686598664657689', 'consultant', NULL, 1, 0, '2026-05-24 09:27:40', '2026-05-24 09:29:44'),
(8, 8, 30, 1, 2, 'LN-20260524-LPYYRD', NULL, NULL, '1995-01-31', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 15000.00, 0.00, 1, 24, 264550.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-24 11:14:59', NULL, '134684910194', '8_aadhar_front_683952.jpg', '8_aadhar_back_704781.jpg', 'ABCDE1234F', '8_pan_front_291698.jpg', '8_selfie_485334.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'government', 5, 0, 'hdfc', 'surat', 'HDFC000012G', '16464848481919191', 'self', NULL, 1, 0, '2026-05-24 11:14:59', '2026-05-24 11:16:44'),
(9, 8, 30, 1, 2, 'LN-20260524-TEV0B8', NULL, NULL, '1995-01-24', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 15000.00, 0.00, 3, 24, 264550.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-24 11:19:32', NULL, '494619191919', '9_aadhar_front_746758.jpg', '9_aadhar_back_883248.jpg', 'ABCDE1736F', '9_pan_front_837630.jpg', '9_selfie_587812.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 2, 0, 'hdfc', 'surat', 'HDFC0000123', '94649181818', 'self', NULL, 1, 0, '2026-05-24 11:19:32', '2026-05-24 11:20:52'),
(10, 8, 30, 1, 3, 'LN-20260524-W58N2F', NULL, NULL, '1995-01-24', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 12000.00, 0.00, 2, 24, 211640.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-24 11:22:06', NULL, '457563429833', '10_aadhar_front_501254.png', '10_aadhar_back_932585.jpeg', 'ABCDE1234F', '10_pan_front_363507.png', '10_selfie_289732.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 2, 0, 'hdfc', 'surat', 'HDFC0001233', '93488343980', 'self', 3, 1, 0, '2026-05-24 11:22:06', '2026-05-24 11:27:56'),
(11, 8, 30, 1, 2, 'LN-20260524-C0GB4N', NULL, NULL, '1995-01-31', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 151010.00, 0.00, 4, 12, 520249.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-24 11:46:14', NULL, '194619181662', '11_aadhar_front_512901.jpg', '11_aadhar_back_758073.jpg', 'ACSDG1244D', '11_pan_front_371960.jpg', '11_selfie_848936.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'government', 5, 0, 'hdfc', 'surat', 'HDFC0009123', '49169101010191', 'self', 10, 1, 0, '2026-05-24 11:46:14', '2026-05-24 11:48:28'),
(12, 8, 31, 1, 3, 'LN-20260524-FICT4Z', NULL, NULL, '1995-01-01', NULL, 'jdjdjdjdjdjdj', 0, NULL, NULL, NULL, 'salaried', 64850.00, 646.00, 1, 24, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-24 12:21:56', NULL, '653449492819', '12_aadhar_front_617355.jpg', '12_aadhar_back_440610.jpg', 'ATOPV6680K', '12_pan_front_657471.jpg', '12_selfie_134311.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 9, 0, 'hskafv', 'dhhejek', 'HDFC0001234', '56658466461194964491', 'self', 20, 1, 0, '2026-05-24 12:21:56', '2026-05-24 12:24:13'),
(13, 8, 32, 2, 2, 'LN-20260525-WK0AJA', NULL, NULL, '1995-01-01', NULL, 'hdjzjxx', 0, NULL, NULL, NULL, 'self_employed', 80000.00, 0.00, 3, 48, 875000.00, 12.55, NULL, 'pending', 2, NULL, '2026-05-25 04:03:23', NULL, '356868586599', '13_aadhar_front_676432.jpg', '13_aadhar_back_983438.jpg', 'HTMPS6589J', '13_pan_front_345525.jpg', '13_selfie_406193.jpg', 'manufacture', 6, 'msme', 'current_account', 2, 'married', NULL, NULL, 0, 'hdfc', 'hdfc', 'HDFC0000023', '64659494994678494984', 'consultant', NULL, 1, 0, '2026-05-25 04:03:23', '2026-05-25 04:05:17'),
(14, 8, 32, 1, 3, 'LN-20260525-URH35B', NULL, NULL, '1995-01-03', NULL, 'bdjdj', 0, NULL, NULL, NULL, 'salaried', 65665.00, 0.00, 2, 12, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-25 04:06:27', NULL, '466595995656', '14_aadhar_front_897562.jpg', '14_aadhar_back_175022.jpg', 'HDFYD8790K', '14_pan_front_781418.jpg', '14_selfie_813368.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 8, 0, 'hdf', 'hdfg', 'HDFC0000023', '9898653599865986', 'self', 26, 1, 0, '2026-05-25 04:06:27', '2026-05-25 04:08:48'),
(15, 7, 33, 1, 1, 'LN-20260526-P6OFY6', NULL, NULL, '2000-01-21', NULL, 'kamarej', 0, NULL, NULL, NULL, 'self_employed', 80000.00, 0.00, 3, 36, 875000.00, 10.55, NULL, 'pending', 1, NULL, '2026-05-26 18:19:12', NULL, '863251478965', '15_aadhar_front_672875.jpg', '15_aadhar_back_423372.jpg', 'HKPTS1457K', '15_pan_front_272535.jpg', '15_selfie_165597.jpg', 'manufacture', 26, 'gst', 'current_account', 2, 'unmarried', 'private', 26, 0, 'hdfc bank', 'kamrej', 'HDFC0001234', '86325196731521384661', 'self', NULL, 0, 0, '2026-05-26 18:19:12', '2026-05-26 18:25:41'),
(16, 8, 34, 1, 1, 'LN-20260526-IDEDNE', NULL, NULL, '1995-01-10', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 2, 12, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-26 18:19:50', NULL, '123456789012', '16_aadhar_front_310122.png', '16_aadhar_back_127369.png', 'ABCDE1234F', '16_pan_front_129116.png', '16_selfie_958258.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 2, 0, 'hdfv', 'surat', 'HDFC0001234', '123456789012', 'self', 29, 1, 0, '2026-05-26 18:19:50', '2026-05-26 18:22:18'),
(17, 7, 35, 1, 1, 'LN-20260526-VEJT1X', NULL, NULL, '1996-01-06', NULL, 'maharaja', 0, NULL, NULL, NULL, 'self_employed', 9464646.00, 0.00, 3, 12, 875000.00, 10.55, NULL, 'pending', 1, NULL, '2026-05-26 18:20:13', NULL, '946565665566', '17_aadhar_front_234159.jpg', '17_aadhar_back_954005.jpg', 'ABCDE1234E', '17_pan_front_446424.jpg', '17_selfie_441462.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'government', 12, 0, 'hdfc', 'Mumbai', 'HDFC0000123', '9999999464664', 'self', NULL, 0, 0, '2026-05-26 18:20:13', '2026-05-26 18:24:12'),
(18, 7, 34, 1, 2, 'LN-20260526-OYBPLE', NULL, NULL, '1995-01-08', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 2, 24, 875000.00, 10.55, NULL, 'pending', 1, NULL, '2026-05-26 18:23:05', NULL, '123456789012', '18_aadhar_front_977329.png', '18_aadhar_back_274142.png', 'ABCDE1234F', '18_pan_front_357164.png', '18_selfie_816657.jpg', 'trader', 5, 'gst', 'current_account', 1, 'married', NULL, NULL, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'consultant', NULL, 0, 0, '2026-05-26 18:23:05', '2026-05-26 18:25:16'),
(19, 7, 35, 2, 2, 'LN-20260526-PEFIDR', NULL, NULL, '1995-01-14', NULL, 'Mota varchha8', 0, NULL, NULL, NULL, 'self_employed', 5000.00, 0.00, 3, 12, 195000.00, 12.55, NULL, 'pending', 1, NULL, '2026-05-26 18:24:20', NULL, '129495656566', '19_aadhar_front_918552.jpg', '19_aadhar_back_704544.jpg', 'ABCDE1234E', '19_pan_front_167747.jpg', '19_selfie_410845.jpg', 'trader', 5, 'msme', 'current_account', 2, 'married', NULL, NULL, 0, 'hdfc', 'Mubarak', 'HDFC0001323', '97656566565', 'consultant', NULL, 0, 0, '2026-05-26 18:24:20', '2026-05-26 18:25:57'),
(20, 8, 34, 2, 3, 'LN-20260526-LFNR76', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 2, 24, 821692.00, 12.55, NULL, 'pending', 2, NULL, '2026-05-26 18:25:26', NULL, '123456789012', '20_aadhar_front_576792.png', '20_aadhar_back_153284.png', 'ABCDE1234F', '20_pan_front_714252.png', '20_selfie_986176.jpg', 'manufacture', 5, 'msme', 'current_account', 1, 'unmarried', NULL, NULL, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'consultant', NULL, 1, 0, '2026-05-26 18:25:26', '2026-05-26 18:27:12'),
(21, 2, 33, 3, 0, 'LN-20260526-QOFPQN', NULL, NULL, '2002-01-17', NULL, 'kamarej', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 3, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-26 18:25:52', NULL, '632179955886', '21_aadhar_front_501100.jpg', '21_aadhar_back_722656.jpg', 'GKPTF5689J', '21_pan_front_902428.jpg', '21_selfie_114983.jpg', 'manufacture', 26, 'msme', 'current_account', 2, 'unmarried', 'private', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-26 18:25:52', '2026-05-26 18:28:38'),
(22, 2, 35, 3, 0, 'LN-20260526-HH4W8N', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'salaried', 656595.00, 0.00, 1, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-26 18:26:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-26 18:26:13', '2026-05-26 18:26:21'),
(23, 2, 34, 3, 0, 'LN-20260526-K1EIDZ', NULL, NULL, '1995-01-02', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 5000.00, 0.00, 2, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-26 18:28:45', NULL, '123456789012', '23_aadhar_front_186779.png', '23_aadhar_back_429561.png', 'ABCDE1234F', '23_pan_front_837617.png', '23_selfie_589520.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-26 18:28:45', '2026-05-26 18:30:41'),
(24, 8, 36, 1, 1, 'LN-20260526-OM6HJA', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 1, 36, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-26 18:37:25', NULL, '123456789012', '24_aadhar_front_692755.png', '24_aadhar_back_213343.png', 'ABCDE1234F', '24_pan_front_798650.png', '24_selfie_705187.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'private', 5, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'self', 3, 1, 0, '2026-05-26 18:37:25', '2026-05-26 18:41:28'),
(25, 8, 36, 1, 3, 'LN-20260526-PGF8ZI', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 4, 36, 875000.00, 10.55, NULL, 'pending', 4, NULL, '2026-05-26 18:41:51', NULL, '123456789012', '25_aadhar_front_188664.png', '25_aadhar_back_692824.png', 'ABCDE1234F', '25_pan_front_677734.png', '25_selfie_355683.jpg', 'service', 8, 'gst', 'current_account', 2, 'married', NULL, NULL, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'consultant', NULL, 1, 0, '2026-05-26 18:41:51', '2026-05-28 06:32:28'),
(26, 8, 36, 2, 2, 'LN-20260526-VEBXHM', NULL, NULL, '1995-01-22', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 3, 48, 821692.00, 12.55, NULL, 'pending', 2, NULL, '2026-05-26 18:44:30', NULL, '123456789012', '26_aadhar_front_867864.jpeg', '26_aadhar_back_187739.jpeg', 'ABCDE1234F', '26_pan_front_307761.jpeg', '26_selfie_475177.jpg', 'trader', 25, 'gst', 'current_account', 1, 'unmarried', NULL, NULL, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'consultant', NULL, 1, 0, '2026-05-26 18:44:30', '2026-05-26 18:46:20'),
(27, 7, 36, 2, 2, 'LN-20260526-CTXYZK', NULL, NULL, '1995-01-27', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 2, 12, 350959.00, 12.55, NULL, 'pending', 1, NULL, '2026-05-26 18:48:08', NULL, '123456789012', '27_aadhar_front_412415.jpeg', '27_aadhar_back_669556.jpeg', 'ABCDE1234F', '27_pan_front_824788.jpeg', '27_selfie_941713.jpg', 'service', 5, 'msme', 'current_account', 1, 'married', NULL, NULL, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'self', NULL, 0, 0, '2026-05-26 18:48:08', '2026-05-26 18:49:39'),
(28, 2, 36, 3, 0, 'LN-20260526-MFZJ0N', NULL, NULL, '1995-01-29', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 3, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-26 18:49:52', NULL, '123456789012', '28_aadhar_front_678375.jpeg', '28_aadhar_back_607427.jpeg', 'ABCDE0123F', '28_pan_front_704249.jpeg', '28_selfie_145952.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-26 18:49:52', '2026-05-26 18:50:55'),
(29, 8, 37, 1, 4, 'LN-20260526-YVH8YS', NULL, NULL, '1995-01-14', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 2, 36, 50000.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-26 19:01:26', NULL, '123456789012', '29_aadhar_front_962375.jpg', '29_aadhar_back_601509.jpg', 'ABCDE0123F', '29_pan_front_671116.jpg', '29_selfie_813766.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 15, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'self', 10, 1, 0, '2026-05-26 19:01:26', '2026-05-26 19:03:59'),
(30, 8, 37, 1, 1, 'LN-20260526-FSYFNQ', NULL, NULL, '1995-01-24', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 5000000.00, 0.00, 2, 12, 875000.00, 10.55, NULL, 'pending', 5, NULL, '2026-05-26 19:04:17', NULL, '123456789012', '30_aadhar_front_896307.jpg', '30_aadhar_back_294190.jpg', 'ABCDE1234F', '30_pan_front_123959.jpg', '30_selfie_309340.jpg', 'manufacture', 50, 'msme', 'current_account', 1, 'married', NULL, NULL, 1, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'consultant', NULL, 1, 0, '2026-05-26 19:04:17', '2026-05-28 06:35:22'),
(31, 8, 37, 2, 4, 'LN-20260526-TV8W6F', NULL, NULL, '1995-01-26', NULL, 'zurich', 0, NULL, NULL, NULL, 'self_employed', 5000000.00, 0.00, 4, 24, 875000.00, 12.55, NULL, 'pending', 2, NULL, '2026-05-26 19:06:53', NULL, '123456789012', '31_aadhar_front_113761.jpg', '31_aadhar_back_714742.jpg', 'ABCDE1234F', '31_pan_front_938729.jpg', '31_selfie_297570.jpg', 'trader', 8, 'msme', 'current_account', 2, 'married', NULL, NULL, 0, 'hdfc', 'surat', 'HDFC0001234', '123456789012', 'self', 4, 1, 0, '2026-05-26 19:06:53', '2026-05-26 19:08:49'),
(32, 2, 37, 3, 0, 'LN-20260526-H3CE0T', NULL, NULL, '1995-01-31', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 3, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-26 19:08:57', NULL, '123456789012', '32_aadhar_front_840720.jpg', '32_aadhar_back_953852.jpg', 'ABCDE0123F', '32_pan_front_230952.jpg', '32_selfie_526749.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-26 19:08:57', '2026-05-26 19:09:52'),
(33, 8, 27, 1, 1, 'LN-20260528-C0ZHME', NULL, NULL, '1990-01-01', NULL, 'ha sghs sjjshbs shshhsb sjshsj', 0, NULL, NULL, NULL, 'salaried', 50000.00, 10000.00, 2, 60, 440917.00, 10.55, NULL, 'pending', 2, NULL, '2026-05-28 18:57:18', NULL, '875649978846', '33_aadhar_front_856301.jpg', '33_aadhar_back_359337.jpg', 'AMZPD7277P', '33_pan_front_245454.jpg', '33_selfie_774212.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 2, 0, 'VARACHHA BANK', 'surat', 'VARA0001032', '368456469875546', 'self', 21, 1, 0, '2026-05-28 18:57:18', '2026-05-28 19:00:24'),
(34, 3, 27, 1, 4, 'LN-20260528-8UB0DQ', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 3, 60, 561500.00, 10.55, NULL, 'pending', 1, NULL, '2026-05-28 19:02:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-28 19:02:58', '2026-05-28 19:03:14'),
(35, 2, 31, 3, 0, 'LN-20260529-5WU8YK', NULL, NULL, '1995-01-17', NULL, '12 બેઝિઝ apr', 0, NULL, NULL, NULL, 'self_employed', 60000.00, 6000.00, 3, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-05-29 21:41:24', NULL, '926764928846', '35_aadhar_front_958387.jpg', '35_aadhar_back_145969.jpg', 'AVFOU6789K', '35_pan_front_333757.jpg', '35_selfie_564448.jpg', 'manufacture', 10, 'msme', 'saving_account', 3, 'unmarried', 'government', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-05-29 21:41:24', '2026-05-29 21:43:48'),
(36, 7, 31, 1, 2, 'LN-20260530-JKZXLL', NULL, NULL, '1995-01-11', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 4500.00, 0.00, 3, 12, 195000.00, 10.55, NULL, 'pending', 1, NULL, '2026-05-30 19:21:37', NULL, '237675675656', '36_aadhar_front_620052.jpg', '36_aadhar_back_132064.jpg', 'ABCDE1234D', '36_pan_front_119919.jpg', '36_selfie_274776.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'government', 2, 0, 'hdfc', 'syar', 'HSFC0091124', '16404181991', 'self', NULL, 0, 0, '2026-05-30 19:21:37', '2026-08-04 13:50:44'),
(37, 7, 30, 1, 3, 'LN-20260727-GVJOSJ', NULL, NULL, '1995-01-19', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 15000.00, 0.00, 1, 24, 264550.00, 10.55, NULL, 'pending', 1, NULL, '2026-07-27 20:17:54', NULL, '298424094204', '37_aadhar_front_797562.jpg', '37_aadhar_back_931612.png', 'ABCDE1234F', '37_pan_front_854151.jpg', '37_selfie_972131.jpeg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 2, 0, 'hdfc', 'surat', 'HDFC0001234', '3298343498348934', 'self', NULL, 0, 0, '2026-07-27 20:17:54', '2026-07-28 20:51:09'),
(38, 8, 28, 1, 2, 'LN-20260728-LVIKGO', NULL, NULL, '2000-10-10', NULL, 'dsadsadsad dsad ad dsa dasd dsa dasd sdasds', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 1, 120, 450000.00, 12.50, NULL, 'pending', 2, NULL, '2026-07-28 15:54:53', NULL, '885566332200', '38_aadhar_front_848884.jpg', '38_aadhar_back_357815.jpg', 'dsa1521', '38_pan_front_808611.jpg', '38_selfie_416603.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 2, 1, 'HDFC Bank', 'Vastrapur', 'HDFC0001234', '123456789012', 'self', 2, 1, 0, '2026-07-28 15:54:53', '2026-07-28 16:15:02'),
(39, 8, 28, 1, 1, 'LN-20260728-4ZDT4M', NULL, NULL, '1995-01-01', NULL, 'ghjghhnbv', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 2, 36, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-07-28 16:48:27', NULL, '556556889688', '39_aadhar_front_101708.jpg', '39_aadhar_back_706157.jpg', 'GJOUF6865K', '39_pan_front_447932.jpg', '39_selfie_885609.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 6, 0, 'ghghjhgh', 'ugjjhhjjg', 'HDFU0009025', '6995685698569855', 'self', 10, 1, 0, '2026-07-28 16:48:27', '2026-07-28 16:54:18'),
(40, 8, 40, 1, 2, 'LN-20260728-LWRQL5', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 45000.00, 0.00, 2, 24, 793650.00, 10.55, NULL, 'pending', 2, NULL, '2026-07-28 21:10:15', NULL, '349834983298', '40_aadhar_front_622647.jpg', '40_aadhar_back_290064.png', 'ABCDE1234F', '40_pan_front_284754.jpeg', '40_selfie_545927.png', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 2, 0, 'hdfc', 'surat', 'HDFC0000123', '348548949848', 'self', 3, 1, 0, '2026-07-28 21:10:15', '2026-07-28 21:12:05'),
(41, 8, 41, 1, 2, 'LN-20260729-ZXBCT7', NULL, NULL, '1995-01-01', NULL, 'udueueh', 0, NULL, NULL, NULL, 'salaried', 50000.00, 10000.00, 3, 36, 440917.00, 10.55, NULL, 'pending', 2, NULL, '2026-07-29 20:00:39', NULL, '494949494949', '41_aadhar_front_476587.jpg', '41_aadhar_back_826332.jpg', 'AYOGP8899E', '41_pan_front_820951.jpg', '41_selfie_628792.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'government', 5, 0, 'hdfc bank', 'mumbai', 'HDFC0000123', '92389495843161655461', 'self', 10, 1, 0, '2026-07-29 20:00:39', '2026-07-29 20:02:14'),
(42, 7, 41, 1, 1, 'LN-20260729-LC0CAE', NULL, NULL, '1995-01-27', NULL, 'hdhej', 0, NULL, NULL, NULL, 'self_employed', 40000.00, 20000.00, 3, 24, 195000.00, 10.55, NULL, 'pending', 1, NULL, '2026-07-29 20:02:33', NULL, '646549462626', '42_aadhar_front_713428.jpg', '42_aadhar_back_254353.jpg', 'AYOVP7894K', '42_pan_front_293696.jpeg', '42_selfie_965604.jpg', 'trader', 5, 'msme', 'current_account', 1, 'married', NULL, NULL, 1, 'hdglfc', 'jdjdjek', 'HDFC0000011', '65655656565656565', 'self', NULL, 0, 0, '2026-07-29 20:02:33', '2026-08-16 12:21:06'),
(43, 8, 42, 1, 4, 'LN-20260729-FOX7UY', NULL, NULL, '1987-06-28', NULL, 'SHIDBJSNN HSHJDB HJDIN DHSHH DHSHDJ', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 2, 72, 380000.00, 10.55, NULL, 'pending', 2, NULL, '2026-07-29 20:46:57', NULL, '546855486988', '43_aadhar_front_663301.jpg', '43_aadhar_back_312210.pdf', 'ACQPK2236J', '43_pan_front_167753.jpg', '43_selfie_192246.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'private', 3, 1, 'HDFC BANK', 'borivali', 'HDFC0002233', '50000468990', 'self', 30, 1, 0, '2026-07-29 20:46:57', '2026-07-29 20:51:32'),
(44, 6, 42, 1, 4, 'LN-20260729-QQVKNE', NULL, NULL, '1990-01-01', NULL, 'jdhjdn jxhdjdj bshdhdj hdhjjd hhdjjd', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 3, 60, 413000.00, 10.55, NULL, 'pending', 1, NULL, '2026-07-29 20:53:17', NULL, '694559788491', '44_aadhar_front_788731.jpg', '44_aadhar_back_917327.jpg', 'COQPD6315L', '44_pan_front_962931.jpg', '44_selfie_886914.jpg', 'trader', 6, 'trade_license', 'current_account', 1, 'married', 'government', 3, 0, 'hdfc', 'hhbvgj', 'HDFC0000213', '28834855849', NULL, NULL, 0, 0, '2026-07-29 20:53:17', '2026-08-12 12:43:07'),
(45, 8, 43, 1, 3, 'LN-20260729-DHNBPS', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 50000.00, 0.00, 2, 24, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-07-29 22:19:28', NULL, '123456789012', '45_aadhar_front_265848.jpg', '45_aadhar_back_989024.jpg', 'ABCDE1234F', '45_pan_front_493731.jpg', '45_selfie_518568.jpg', NULL, NULL, NULL, NULL, NULL, 'unmarried', 'private', 5, 0, 'HDFC Bank', 'surat', 'HDFC0001234', '123456789012', 'self', 18, 1, 0, '2026-07-29 22:19:28', '2026-07-29 22:21:58'),
(46, 8, 43, 1, 1, 'LN-20260729-QHXDLY', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 2, 24, 875000.00, 10.55, NULL, 'pending', 2, NULL, '2026-07-29 22:22:13', NULL, '123456789012', '46_aadhar_front_279001.jpg', '46_aadhar_back_787996.jpg', 'ABCDE1234F', '46_pan_front_611133.jpg', '46_selfie_787996.jpg', 'service', 25, 'gst', 'current_account', 2, 'married', NULL, NULL, 1, 'HDFC', 'surat', 'HDFC0001234', '123456789012', 'consultant', NULL, 1, 0, '2026-07-29 22:22:13', '2026-07-29 22:23:43'),
(47, 2, 43, 3, 0, 'LN-20260729-DIZICX', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'salaried', 500000.00, 0.00, 2, 0, NULL, NULL, NULL, 'pending', 1, NULL, '2026-07-29 22:25:13', NULL, '123456789012', '47_aadhar_front_110753.jpg', '47_aadhar_back_506239.jpg', 'ABCDE1234F', '47_pan_front_655454.jpg', '47_selfie_591310.jpg', NULL, NULL, NULL, NULL, NULL, 'married', 'government', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '2026-07-29 22:25:13', '2026-07-29 22:26:13'),
(48, 8, 43, 2, 4, 'LN-20260729-GOBCJB', NULL, NULL, '1995-01-01', NULL, 'surat', 0, NULL, NULL, NULL, 'self_employed', 50000.00, 0.00, 3, 48, 821692.00, 12.55, NULL, 'pending', 2, NULL, '2026-07-29 22:26:17', NULL, '123456789012', '48_aadhar_front_238173.jpg', '48_aadhar_back_221610.jpg', 'ABCDE1234F', '48_pan_front_791262.jpg', '48_selfie_488912.jpg', 'trader', 50, 'msme', 'current_account', 3, 'married', NULL, NULL, 0, 'HDFC', 'surat', 'HDFC0001234', '123456789012', 'self', NULL, 1, 0, '2026-07-29 22:26:17', '2026-07-29 22:28:15');

-- --------------------------------------------------------

--
-- Table structure for table `loan_documents`
--

CREATE TABLE `loan_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `salary_slip` varchar(255) DEFAULT NULL,
  `bank_statement` varchar(255) DEFAULT NULL,
  `business_proof` varchar(255) DEFAULT NULL COMMENT 'GST+MSME+Trade License+Any Other',
  `gst_return` varchar(255) DEFAULT NULL,
  `income_tax_eturn` varchar(255) DEFAULT NULL,
  `utility_bill` varchar(255) DEFAULT NULL,
  `cancel_cheque` varchar(255) DEFAULT NULL,
  `other_one` varchar(255) DEFAULT NULL,
  `other_two` varchar(255) DEFAULT NULL,
  `other_three` varchar(255) DEFAULT NULL,
  `other_four` varchar(255) DEFAULT NULL,
  `other_five` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_documents`
--

INSERT INTO `loan_documents` (`id`, `loan_application_id`, `user_id`, `status`, `rejection_reason`, `salary_slip`, `bank_statement`, `business_proof`, `gst_return`, `income_tax_eturn`, `utility_bill`, `cancel_cheque`, `other_one`, `other_two`, `other_three`, `other_four`, `other_five`, `created_at`, `updated_at`) VALUES
(1, 7, 28, 'pending', NULL, '7_salary_slip_895354.png', '7_bank_statement_973553.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-24 09:31:29', '2026-05-24 09:31:35'),
(2, 20, 34, 'pending', NULL, '20_salary_slip_285376.png', '20_bank_statement_759136.png', '20_business_proof_517647.png', '20_gst_return_779159.png', '20_income_tax_eturn_892218.png', '20_utility_bill_882813.png', '20_cancel_cheque_836284.png', '20_other_one_231150.png', '20_other_two_167090.png', '20_other_three_868721.png', '20_other_four_787724.png', '20_other_five_702922.png', '2026-05-26 18:27:50', '2026-05-26 18:28:39'),
(3, 25, 36, 'pending', NULL, '25_salary_slip_332802.png', '25_bank_statement_700112.png', '25_business_proof_838846.png', '25_gst_return_818335.png', '25_income_tax_eturn_131625.png', '25_utility_bill_317761.png', '25_cancel_cheque_282217.png', '25_other_one_785975.png', '25_other_two_480166.png', '25_other_three_302801.jpg', NULL, NULL, '2026-05-26 18:43:42', '2026-05-26 18:44:19'),
(4, 26, 36, 'pending', NULL, '26_salary_slip_787504.jpeg', '26_bank_statement_419026.jpeg', '26_business_proof_766210.jpeg', '26_gst_return_531343.jpeg', '26_income_tax_eturn_512862.jpeg', '26_utility_bill_286959.jpeg', '26_cancel_cheque_573773.jpeg', '26_other_one_971592.jpeg', '26_other_two_350413.jpeg', '26_other_three_143680.jpeg', NULL, NULL, '2026-05-26 18:47:39', '2026-05-26 18:47:56'),
(5, 30, 37, 'pending', NULL, '30_salary_slip_403343.jpg', '30_bank_statement_337072.jpg', '30_business_proof_117087.jpg', '30_gst_return_415779.jpg', '30_income_tax_eturn_431494.jpg', '30_utility_bill_846153.jpg', '30_cancel_cheque_935393.jpg', '30_other_one_669151.jpg', '30_other_two_710512.jpg', '30_other_three_971808.jpg', NULL, NULL, '2026-05-26 19:06:32', '2026-05-26 19:06:42'),
(6, 46, 43, 'pending', NULL, '46_salary_slip_328260.jpg', '46_bank_statement_474127.jpg', '46_business_proof_584172.jpg', '46_gst_return_617412.jpg', '46_income_tax_eturn_536975.jpg', '46_utility_bill_375798.jpg', '46_cancel_cheque_436840.jpg', NULL, NULL, NULL, NULL, NULL, '2026-07-29 22:24:48', '2026-07-29 22:25:06');

-- --------------------------------------------------------

--
-- Table structure for table `loan_purposes`
--

CREATE TABLE `loan_purposes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_purposes`
--

INSERT INTO `loan_purposes` (`id`, `label`, `status`) VALUES
(1, 'Personal Expenses', 1),
(2, 'Medical Emergency', 1),
(3, 'Education', 1),
(4, 'Business Expansion', 1),
(5, 'Home Renovation', 1),
(6, 'Wedding', 1),
(7, 'Travel', 1),
(8, 'Debt Consolidation', 1),
(9, 'Vehicle Purchase', 1),
(10, 'Other', 1);

-- --------------------------------------------------------

--
-- Table structure for table `loan_status`
--

CREATE TABLE `loan_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive',
  `priority` int(11) NOT NULL DEFAULT 0 COMMENT 'Used for loan workflow order'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_status`
--

INSERT INTO `loan_status` (`id`, `label`, `status`, `priority`) VALUES
(1, 'Pending', 1, 1),
(2, 'Under Review', 1, 2),
(3, 'Approved', 1, 3),
(4, 'Rejected', 1, 4),
(5, 'Disbursed', 1, 5),
(6, 'Closed', 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive',
  `payment` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1-payment enabled, 0-payment disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`id`, `label`, `rate`, `status`, `payment`) VALUES
(1, 'Personal Loan', '10.55', 1, 1),
(2, 'Business Loan', '12.55', 1, 1),
(3, 'Credit Card', '0', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_personal_access_tokens_table', 1),
(4, '0001_01_01_000003_create_otp_logs_table', 1),
(5, '2025_12_22_044035_add_profile_fields_to_users_table', 1),
(6, '2025_12_22_050247_add_softdelete_to_users_table', 1),
(7, '2025_12_22_113138_create_loan_types_table', 1),
(8, '2025_12_22_114256_create_loan_purposes_table', 1),
(9, '2025_12_22_115013_create_loan_applications_table', 1),
(10, '2025_12_22_120056_create_loan_status_table', 1),
(11, '2025_12_22_125403_create_loan_documents_table', 1),
(12, '2025_12_24_025526_create_support_tickets_table', 1),
(13, '2025_12_24_030608_create_banks_table', 1),
(14, '2025_12_24_032039_create_payment_transactions_table', 1),
(15, '2025_12_25_125256_create_cibil_scores_table', 1),
(16, '2025_12_25_130636_create_notifications_table', 1),
(17, '2025_12_26_074255_create_support_reasons_table', 1),
(18, '2026_01_12_102217_create_states_districts_table', 1),
(19, '2026_01_17_162955_create_our_partners_table', 1),
(20, '2026_01_18_101257_create_options_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL COMMENT 'loan_application, payment_transaction, support_ticket',
  `reference_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `action_url` varchar(255) DEFAULT NULL,
  `extra_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `reference_type`, `reference_id`, `is_read`, `action_url`, `extra_data`, `created_at`, `updated_at`) VALUES
(1, 2, 'Loan Approved', 'Your personal loan has been approved', NULL, NULL, 1, NULL, NULL, '2026-01-19 11:27:04', '2026-01-19 11:32:32'),
(2, 2, 'Loan Approved', 'UPDATE: Your loan application [ID] is approved. The net disbursement amount of $[Amount] will be credited to your A/c XX[Last 4 Digits] within 24 hours. Check status: [Bank App Link]', NULL, NULL, 0, NULL, NULL, '2026-01-19 11:29:00', '2026-01-19 11:29:00'),
(3, 2, 'Loan Approved', 'UPDATE: Your loan application [ID] is approved. The net disbursement amount of $[Amount] will be credited to your A/c XX[Last 4 Digits] within 24 hours. Check status: [Bank App Link]', NULL, NULL, 0, NULL, NULL, '2026-01-19 11:29:03', '2026-01-19 11:29:03'),
(4, 3, 'Loan Approved', 'Your personal loan has been approved', NULL, NULL, 1, NULL, NULL, '2026-03-14 10:31:07', '2026-03-14 10:31:58'),
(5, 3, 'Loan Approved', 'Your personal loan has been approved', NULL, NULL, 1, NULL, NULL, '2026-03-15 06:26:31', '2026-03-15 06:26:59'),
(6, 20, 'Loan Approved', 'Your personal loan has been approved', NULL, NULL, 1, NULL, NULL, '2026-03-30 11:31:12', '2026-03-30 11:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0-active, 1-inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `key`, `label`, `value`, `status`) VALUES
(1, 'self_video', 'Self Video', 'https://youtube.com/shorts/5D0pH-HsMAI?si=yg_OuQQRaHVHmakh', 0),
(2, 'consultant_video', 'Consultant Video', 'https://youtube.com/shorts/H_SUBU3_bBQ?si=EdDfbE_yOrztZT9H', 0),
(3, 'support_mail', 'Support Mail', 'support@flubbi.com', 0),
(4, 'support_phone', 'Support Phone', '9876543210', 0);

-- --------------------------------------------------------

--
-- Table structure for table `otp_logs`
--

CREATE TABLE `otp_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(15) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_logs`
--

INSERT INTO `otp_logs` (`id`, `phone`, `otp`, `is_used`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, '8866442200', '123456', 1, '2026-01-18 13:00:58', '2026-01-18 12:55:58', '2026-01-18 12:56:02'),
(2, '8160727081', '123456', 1, '2026-01-19 04:39:08', '2026-01-19 04:34:08', '2026-01-19 04:34:12'),
(3, '8866442200', '123456', 1, '2026-01-19 04:45:21', '2026-01-19 04:40:21', '2026-01-21 15:20:18'),
(4, '8160727081', '123456', 1, '2026-01-19 06:35:10', '2026-01-19 06:30:10', '2026-01-19 06:30:23'),
(5, '8160727081', '123456', 1, '2026-01-19 07:29:16', '2026-01-19 07:24:16', '2026-01-19 07:24:23'),
(6, '8160727081', '123456', 1, '2026-01-19 09:04:27', '2026-01-19 08:59:27', '2026-01-19 08:59:32'),
(7, '8160727081', '123456', 1, '2026-01-19 09:34:35', '2026-01-19 09:29:35', '2026-01-19 09:29:40'),
(8, '8160727081', '123456', 1, '2026-01-19 09:42:41', '2026-01-19 09:37:41', '2026-01-19 09:37:45'),
(9, '8160727081', '123456', 1, '2026-01-19 12:24:19', '2026-01-19 12:19:19', '2026-01-19 12:19:23'),
(10, '8160727081', '123456', 1, '2026-01-19 12:32:12', '2026-01-19 12:27:12', '2026-01-19 12:27:15'),
(11, '8160727081', '265756', 1, '2026-01-20 03:54:06', '2026-01-20 03:49:06', '2026-04-01 19:24:46'),
(12, '7623982394', '829844', 1, '2026-01-20 16:42:43', '2026-01-20 16:37:43', '2026-01-20 16:38:08'),
(13, '6529955666', '651971', 0, '2026-01-20 17:27:58', '2026-01-20 17:22:58', '2026-01-20 17:22:58'),
(14, '9687887028', '789475', 1, '2026-01-20 17:28:16', '2026-01-20 17:23:16', '2026-01-20 17:23:24'),
(15, '1234567890', '123456', 1, '2026-01-21 15:51:38', '2026-01-21 15:46:38', '2026-01-21 15:46:43'),
(16, '1234567890', '123456', 1, '2026-01-21 15:53:34', '2026-01-21 15:48:34', '2026-01-21 15:48:38'),
(17, '1234567890', '123456', 1, '2026-01-22 09:08:58', '2026-01-22 09:03:58', '2026-01-22 09:04:01'),
(18, '1234567890', '123456', 1, '2026-01-22 10:26:03', '2026-01-22 10:21:03', '2026-01-22 10:21:14'),
(19, '7623982390', '123456', 1, '2026-01-24 11:40:04', '2026-01-24 11:35:04', '2026-01-24 11:35:47'),
(20, '8160727082', '123456', 1, '2026-01-25 07:09:14', '2026-01-25 07:04:14', '2026-07-28 19:57:25'),
(21, '1234567890', '123456', 1, '2026-01-25 07:15:44', '2026-01-25 07:10:44', '2026-01-28 13:35:41'),
(22, '1234567899', '123456', 0, '2026-01-25 08:09:52', '2026-01-25 08:04:52', '2026-01-25 08:04:52'),
(23, '6458664949', '123456', 1, '2026-01-27 17:09:39', '2026-01-27 17:04:39', '2026-01-27 17:04:44'),
(24, '1234567891', '123456', 0, '2026-01-28 13:25:44', '2026-01-28 13:20:44', '2026-01-28 13:20:44'),
(25, '9726022099', '123456', 1, '2026-01-29 10:35:06', '2026-01-29 10:30:06', '2026-01-29 10:30:14'),
(26, '9998814760', '123456', 1, '2026-02-03 17:22:13', '2026-02-03 17:17:13', '2026-02-03 17:17:19'),
(27, '9998814760', '123456', 1, '2026-02-05 11:27:20', '2026-02-05 11:22:20', '2026-02-05 11:22:25'),
(28, '1234567890', '123456', 1, '2026-02-09 05:55:06', '2026-02-09 05:50:06', '2026-02-09 05:50:10'),
(29, '1234567890', '123456', 1, '2026-02-09 13:49:58', '2026-02-09 13:44:58', '2026-02-09 13:45:03'),
(30, '7623982394', '241746', 1, '2026-03-14 10:05:50', '2026-03-14 10:00:50', '2026-03-14 10:01:47'),
(31, '7623982394', '221135', 1, '2026-03-14 10:08:56', '2026-03-14 10:03:56', '2026-03-14 10:04:18'),
(32, '7623982394', '492799', 1, '2026-03-14 10:14:35', '2026-03-14 10:09:35', '2026-03-14 10:09:58'),
(33, '8160300254', '840967', 1, '2026-03-18 11:07:06', '2026-03-18 11:02:06', '2026-03-18 11:02:12'),
(34, '6357000230', '825484', 1, '2026-03-26 11:50:20', '2026-03-26 11:45:20', '2026-03-26 11:45:49'),
(35, '8866231236', '707507', 1, '2026-03-30 11:31:16', '2026-03-30 11:26:16', '2026-03-30 11:26:51'),
(36, '8866231236', '533177', 1, '2026-03-30 11:31:51', '2026-03-30 11:26:51', '2026-03-30 11:27:55'),
(37, '8866442200', '800155', 1, '2026-04-01 19:11:47', '2026-04-01 19:06:47', '2026-04-01 19:07:05'),
(38, '8866442200', '311609', 1, '2026-04-01 19:12:05', '2026-04-01 19:07:05', '2026-05-28 23:26:51'),
(39, '8160727081', '631823', 1, '2026-04-01 19:29:47', '2026-04-01 19:24:47', '2026-04-01 19:25:03'),
(40, '9769275689', '448048', 1, '2026-04-06 11:37:50', '2026-04-06 11:32:50', '2026-04-06 11:33:04'),
(41, '9998814760', '987726', 1, '2026-04-06 11:38:34', '2026-04-06 11:33:34', '2026-04-06 11:33:45'),
(42, '9998814760', '287095', 1, '2026-04-06 12:01:41', '2026-04-06 11:56:41', '2026-04-06 11:56:51'),
(43, '9316126885', '619575', 1, '2026-04-07 11:22:14', '2026-04-07 11:17:14', '2026-04-07 11:17:37'),
(44, '9316126885', '202560', 1, '2026-04-07 11:30:23', '2026-04-07 11:25:23', '2026-04-07 11:25:39'),
(45, '9727745017', '260344', 1, '2026-04-07 18:07:33', '2026-04-07 18:02:33', '2026-04-07 18:02:44'),
(46, '9712016416', '108249', 1, '2026-04-07 18:09:14', '2026-04-07 18:04:14', '2026-04-07 18:04:34'),
(47, '9106521721', '532435', 1, '2026-04-07 18:11:26', '2026-04-07 18:06:26', '2026-04-07 18:06:43'),
(48, '8160727081', '936940', 1, '2026-04-09 19:37:13', '2026-04-09 19:32:13', '2026-04-09 19:32:48'),
(49, '8160727081', '369997', 1, '2026-04-09 21:05:59', '2026-04-09 21:00:59', '2026-04-09 21:01:32'),
(50, '8160727081', '160874', 1, '2026-04-13 10:11:10', '2026-04-13 10:06:10', '2026-04-13 10:06:31'),
(51, '9726022099', '988410', 1, '2026-04-13 16:48:56', '2026-04-13 16:43:56', '2026-04-13 16:44:11'),
(52, '8160727081', '461183', 1, '2026-04-13 21:54:37', '2026-04-13 21:49:37', '2026-04-13 21:50:06'),
(53, '7623982394', '316259', 1, '2026-04-14 17:47:53', '2026-04-14 17:42:53', '2026-04-14 17:43:53'),
(54, '8866231236', '931771', 1, '2026-04-14 18:40:25', '2026-04-14 18:35:25', '2026-04-14 18:35:47'),
(55, '8160727081', '662467', 1, '2026-04-14 19:08:59', '2026-04-14 19:03:59', '2026-04-14 19:04:23'),
(56, '8160727081', '591793', 1, '2026-04-14 19:52:00', '2026-04-14 19:47:00', '2026-04-14 19:47:37'),
(57, '9726022099', '510007', 1, '2026-04-23 10:27:08', '2026-04-23 10:22:08', '2026-04-23 10:22:34'),
(58, '9769275689', '161110', 1, '2026-04-23 10:28:11', '2026-04-23 10:23:11', '2026-04-23 10:23:23'),
(59, '9998814760', '924550', 1, '2026-04-25 17:53:51', '2026-04-25 17:48:51', '2026-04-25 17:48:59'),
(60, '9727745017', '867836', 1, '2026-04-25 17:58:13', '2026-04-25 17:53:13', '2026-04-25 17:53:30'),
(61, '9687887028', '258009', 1, '2026-04-28 08:08:13', '2026-04-28 08:03:13', '2026-04-28 08:03:31'),
(62, '9726022099', '544469', 1, '2026-04-30 18:36:06', '2026-04-30 18:31:06', '2026-04-30 18:31:35'),
(63, '8866231236', '351005', 1, '2026-05-10 10:33:12', '2026-05-10 10:28:12', '2026-05-10 10:29:06'),
(64, '9769275689', '122504', 1, '2026-05-21 19:47:21', '2026-05-21 19:42:21', '2026-05-21 19:42:30'),
(65, '8866231236', '185383', 1, '2026-05-24 08:55:49', '2026-05-24 08:50:49', '2026-05-24 08:51:48'),
(66, '8160727081', '948565', 1, '2026-05-24 10:55:06', '2026-05-24 10:50:06', '2026-05-24 10:50:28'),
(67, '8160727081', '675549', 1, '2026-05-24 11:01:00', '2026-05-24 10:56:00', '2026-05-24 10:56:04'),
(68, '8160727081', '133330', 1, '2026-05-24 11:19:31', '2026-05-24 11:14:31', '2026-05-24 11:14:39'),
(69, '8160727081', '303892', 1, '2026-05-24 11:26:44', '2026-05-24 11:21:44', '2026-05-24 11:22:00'),
(70, '8160727081', '416808', 1, '2026-05-24 11:50:56', '2026-05-24 11:45:56', '2026-05-24 11:45:59'),
(71, '9913653618', '284994', 0, '2026-05-24 12:26:19', '2026-05-24 12:21:19', '2026-05-24 12:21:19'),
(72, '8160300254', '545763', 1, '2026-05-24 12:26:43', '2026-05-24 12:21:43', '2026-05-24 12:21:45'),
(73, '7623982395', '350079', 0, '2026-05-25 04:05:58', '2026-05-25 04:00:58', '2026-05-25 04:00:58'),
(74, '7623982394', '223441', 0, '2026-05-25 04:06:06', '2026-05-25 04:01:06', '2026-05-25 04:01:06'),
(75, '8530241214', '232508', 1, '2026-05-25 04:06:40', '2026-05-25 04:01:40', '2026-05-25 04:02:23'),
(76, '8758042142', '751018', 1, '2026-05-26 18:22:30', '2026-05-26 18:17:30', '2026-05-26 18:17:46'),
(77, '9974344036', '526365', 1, '2026-05-26 18:23:49', '2026-05-26 18:18:49', '2026-05-26 18:19:02'),
(78, '7698820142', '209476', 1, '2026-05-26 18:24:16', '2026-05-26 18:19:16', '2026-05-26 18:19:31'),
(79, '7600114425', '868642', 1, '2026-05-26 18:41:17', '2026-05-26 18:36:17', '2026-05-26 18:36:37'),
(80, '9574114392', '931455', 1, '2026-05-26 19:05:49', '2026-05-26 19:00:49', '2026-05-26 19:01:02'),
(81, '9726022099', '341257', 1, '2026-05-28 18:42:12', '2026-05-28 18:37:12', '2026-05-28 18:37:25'),
(82, '8160727081', '279279', 1, '2026-05-28 21:06:15', '2026-05-28 21:01:15', '2026-05-28 21:01:23'),
(83, '8160300254', '178313', 1, '2026-05-28 23:16:57', '2026-05-28 23:11:57', '2026-05-28 23:12:17'),
(84, '8866442200', '191352', 0, '2026-05-28 23:31:51', '2026-05-28 23:26:51', '2026-05-28 23:26:51'),
(85, '8160300254', '148787', 1, '2026-05-28 23:34:03', '2026-05-28 23:29:03', '2026-05-28 23:32:04'),
(86, '8160300254', '213487', 1, '2026-05-28 23:37:04', '2026-05-28 23:32:04', '2026-05-28 23:32:10'),
(87, '8160300354', '851842', 1, '2026-05-29 17:33:21', '2026-05-29 17:28:21', '2026-05-30 00:09:39'),
(88, '8160727081', '971448', 1, '2026-05-29 19:24:59', '2026-05-29 19:19:59', '2026-07-27 20:17:13'),
(89, '8866442211', '472959', 0, '2026-05-29 19:59:28', '2026-05-29 19:54:28', '2026-05-29 19:54:28'),
(90, '8160300254', '812336', 1, '2026-05-30 00:12:54', '2026-05-30 00:07:54', '2026-05-30 00:08:37'),
(91, '8160300354', '359997', 1, '2026-05-30 00:14:39', '2026-05-30 00:09:39', '2026-05-30 00:09:51'),
(92, '8160300254', '723246', 1, '2026-05-30 00:20:43', '2026-05-30 00:15:43', '2026-05-30 00:16:05'),
(93, '8160300354', '454376', 1, '2026-05-30 00:31:55', '2026-05-30 00:26:55', '2026-05-30 00:27:58'),
(94, '8160300254', '210944', 0, '2026-05-30 00:35:16', '2026-05-30 00:30:16', '2026-05-30 00:30:16'),
(95, '8160300251', '701070', 1, '2026-05-30 00:35:54', '2026-05-30 00:30:54', '2026-05-30 00:31:18'),
(96, '8866231236', '825226', 1, '2026-05-30 00:48:06', '2026-05-30 00:43:06', '2026-07-28 15:52:25'),
(97, '9687887028', '542768', 1, '2026-05-30 00:48:34', '2026-05-30 00:43:34', '2026-05-30 00:44:04'),
(98, '9188664422', '392255', 1, '2026-07-01 03:11:30', '2026-07-01 03:06:30', '2026-07-01 03:07:22'),
(99, '9188664422', '629662', 1, '2026-07-01 03:12:22', '2026-07-01 03:07:22', '2026-07-10 02:24:35'),
(100, '9769275689', '440431', 1, '2026-07-04 19:12:24', '2026-07-04 19:07:24', '2026-07-04 19:07:37'),
(101, '9726022099', '947373', 1, '2026-07-04 19:12:53', '2026-07-04 19:07:53', '2026-07-04 19:08:04'),
(102, '9188664422', '731504', 1, '2026-07-10 02:29:35', '2026-07-10 02:24:35', '2026-07-10 02:25:26'),
(103, '9188664422', '431024', 0, '2026-07-10 02:30:26', '2026-07-10 02:25:26', '2026-07-10 02:25:26'),
(104, '9925682067', '153884', 0, '2026-07-27 20:21:51', '2026-07-27 20:16:51', '2026-07-27 20:16:51'),
(105, '8160727081', '988067', 1, '2026-07-27 20:22:13', '2026-07-27 20:17:13', '2026-07-27 20:17:41'),
(106, '8866231236', '566805', 1, '2026-07-28 15:57:25', '2026-07-28 15:52:25', '2026-07-28 15:54:03'),
(107, '8866231236', '599091', 1, '2026-07-28 16:52:42', '2026-07-28 16:47:42', '2026-07-28 16:48:20'),
(108, '8160727082', '446037', 0, '2026-07-28 20:02:25', '2026-07-28 19:57:25', '2026-07-28 19:57:25'),
(109, '8160727081', '970317', 1, '2026-07-28 20:02:32', '2026-07-28 19:57:32', '2026-07-28 19:58:01'),
(110, '8866231438', '375886', 1, '2026-07-28 21:14:02', '2026-07-28 21:09:02', '2026-07-28 21:09:33'),
(111, '9687887020', '420780', 0, '2026-07-29 20:02:30', '2026-07-29 19:57:30', '2026-07-29 19:57:30'),
(112, '9687887028', '377089', 1, '2026-07-29 20:02:35', '2026-07-29 19:57:35', '2026-07-29 19:57:39'),
(113, '9769275689', '330297', 1, '2026-07-29 20:50:21', '2026-07-29 20:45:21', '2026-07-29 20:45:33'),
(114, '9998814760', '137642', 1, '2026-07-29 22:23:34', '2026-07-29 22:18:34', '2026-07-29 22:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `our_partners`
--

CREATE TABLE `our_partners` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `order_no` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=No, 1=Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `our_partners`
--

INSERT INTO `our_partners` (`id`, `name`, `logo`, `order_no`, `status`) VALUES
(1, 'Axis Bank', '001.png', 1, 0),
(2, 'Yes Bank', '002.png', 2, 0),
(3, 'ICICI Bank', '003.png', 3, 0),
(4, 'Kotak Mahindra Bank', '004.png', 4, 0),
(5, 'HDFC Bank', '005.png', 5, 0),
(6, 'TATA Capital', '006.png', 6, 0),
(7, 'Indusind Bank', '007.png', 7, 0),
(8, 'SBI Bank', '008.png', 8, 0),
(9, 'IDBI Bank', '009.png', 9, 0),
(10, 'Bandhan Bank', '010.png', 10, 0),
(11, 'Union Bank', '011.png', 11, 0),
(12, 'RBL Bank', '012.png', 12, 0),
(13, 'Aditya Birla Capital', '013.png', 13, 0),
(14, 'Indiabulls', '014.png', 14, 0),
(15, 'Faircent.com', '015.png', 15, 0),
(16, 'Fullertor India', '016.png', 16, 0),
(17, 'DCB Bank', '017.png', 17, 0),
(18, 'Grihashakti', '018.png', 18, 0),
(19, 'PaySense', '019.png', 19, 0),
(20, 'Lendingkart', '023.png', 20, 0),
(21, 'Indifi', '021.png', 21, 0),
(22, 'Money View', '022.png', 22, 0),
(23, 'Other Bank', '099.jpg', 32, 0),
(24, 'Moneytap', '024.png', 24, 0),
(25, 'IDFC First Bank', '025.png', 25, 0),
(26, 'Bajaj Finserv', '026.png', 26, 0),
(28, 'Ziploan', '028.png', 28, 0),
(29, 'Credit Enable', '029.png', 29, 0),
(30, 'Hero Fincorp', '030.png', 30, 0),
(31, 'Monexo', '031.png', 31, 0),
(32, 'NeoGrowth', '032.png', 32, 0),
(33, 'Werize', '045.png', 33, 0);

-- --------------------------------------------------------

--
-- Table structure for table `our_partnersxxx`
--

CREATE TABLE `our_partnersxxx` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0-active, 1-inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `our_partnersxxx`
--

INSERT INTO `our_partnersxxx` (`id`, `name`, `logo`, `status`) VALUES
(1, 'Money View', '01.png', 0),
(2, 'Ziploan', '02.png', 0),
(3, 'Faircent', '03.png', 0),
(4, 'Indifi', '04.png', 0),
(5, 'FinBox', '05.png', 0),
(6, 'PaySense', '06.png', 0),
(7, 'WeRize', '07.png', 0),
(8, 'Fullertor India', '08.png', 0),
(9, 'Lendingkart', '09.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_application_id` int(11) NOT NULL,
  `base_amount` decimal(10,2) NOT NULL,
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 18.00,
  `gst_amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_gateway` varchar(255) DEFAULT NULL COMMENT 'razorpay, phonepe, paytm, etc..',
  `gateway_payment_id` varchar(255) DEFAULT NULL,
  `gateway_transaction_id` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` enum('pending','success','failed','refunded') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `user_id`, `loan_application_id`, `base_amount`, `gst_percentage`, `gst_amount`, `total_amount`, `payment_gateway`, `gateway_payment_id`, `gateway_transaction_id`, `gateway_response`, `status`, `created_at`, `updated_at`) VALUES
(1, 26, 1, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_Sikbai050ihX3K', 'pay_Sikbai050ihX3K', '{\"data\":{\"razorpay_payment_id\":\"pay_Sikbai050ihX3K\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-04-28 08:08:41', '2026-04-28 08:08:41'),
(2, 29, 5, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_Ss2zdvt1XGvGfw', 'pay_Ss2zdvt1XGvGfw', '{\"data\":{\"razorpay_payment_id\":\"pay_Ss2zdvt1XGvGfw\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-21 19:59:09', '2026-05-21 19:59:09'),
(3, 28, 4, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_St3kNLu6zge5t2', 'pay_St3kNLu6zge5t2', '{\"data\":{\"razorpay_payment_id\":\"pay_St3kNLu6zge5t2\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 09:22:15', '2026-05-24 09:22:15'),
(4, 28, 7, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_St3sdIVW6rTQRo', 'pay_St3sdIVW6rTQRo', '{\"data\":{\"razorpay_payment_id\":\"pay_St3sdIVW6rTQRo\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 09:30:12', '2026-05-24 09:30:12'),
(5, 30, 8, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_St5hpb8KPOYDYq', 'pay_St5hpb8KPOYDYq', '{\"data\":{\"razorpay_payment_id\":\"pay_St5hpb8KPOYDYq\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 11:17:25', '2026-05-24 11:17:25'),
(6, 30, 9, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_St5lvkM1WcMHuK', 'pay_St5lvkM1WcMHuK', '{\"data\":{\"razorpay_payment_id\":\"pay_St5lvkM1WcMHuK\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 11:21:17', '2026-05-24 11:21:17'),
(7, 30, 10, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_St5rBWJ0VgqHRj', 'pay_St5rBWJ0VgqHRj', '{\"data\":{\"razorpay_payment_id\":\"pay_St5rBWJ0VgqHRj\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 11:26:12', '2026-05-24 11:26:12'),
(8, 30, 11, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_St6E3CwkpS2o4O', 'pay_St6E3CwkpS2o4O', '{\"data\":{\"razorpay_payment_id\":\"pay_St6E3CwkpS2o4O\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 11:48:20', '2026-05-24 11:48:20'),
(9, 31, 12, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_St6pyInETBptxw', 'pay_St6pyInETBptxw', '{\"data\":{\"razorpay_payment_id\":\"pay_St6pyInETBptxw\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-24 12:23:48', '2026-05-24 12:23:48'),
(10, 32, 13, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_StMtc9cAlqH5Qz', 'pay_StMtc9cAlqH5Qz', '{\"data\":{\"razorpay_payment_id\":\"pay_StMtc9cAlqH5Qz\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-25 04:06:18', '2026-05-25 04:06:18'),
(11, 32, 14, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_StMvzk9YihvWN6', 'pay_StMvzk9YihvWN6', '{\"data\":{\"razorpay_payment_id\":\"pay_StMvzk9YihvWN6\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-25 04:08:33', '2026-05-25 04:08:33'),
(12, 34, 16, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_Su00o0SofSLRGp', 'pay_Su00o0SofSLRGp', '{\"data\":{\"razorpay_payment_id\":\"pay_Su00o0SofSLRGp\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 18:22:12', '2026-05-26 18:22:12'),
(13, 34, 20, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_Su06VqVlSiZfBb', 'pay_Su06VqVlSiZfBb', '{\"data\":{\"razorpay_payment_id\":\"pay_Su06VqVlSiZfBb\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 18:27:42', '2026-05-26 18:27:42'),
(14, 36, 24, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_Su0LCiHvL9Gskd', 'pay_Su0LCiHvL9Gskd', '{\"data\":{\"razorpay_payment_id\":\"pay_Su0LCiHvL9Gskd\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 18:41:23', '2026-05-26 18:41:23'),
(15, 36, 25, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_Su0NbSpEW7WYdP', 'pay_Su0NbSpEW7WYdP', '{\"data\":{\"razorpay_payment_id\":\"pay_Su0NbSpEW7WYdP\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 18:43:35', '2026-05-26 18:43:35'),
(16, 36, 26, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_Su0RDUxj6cd5JO', 'pay_Su0RDUxj6cd5JO', '{\"data\":{\"razorpay_payment_id\":\"pay_Su0RDUxj6cd5JO\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 18:47:13', '2026-05-26 18:47:13'),
(17, 37, 29, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_Su0j5fIolQWayw', 'pay_Su0j5fIolQWayw', '{\"data\":{\"razorpay_payment_id\":\"pay_Su0j5fIolQWayw\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 19:03:53', '2026-05-26 19:03:53'),
(18, 37, 30, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_Su0lKmc0gWrWB7', 'pay_Su0lKmc0gWrWB7', '{\"data\":{\"razorpay_payment_id\":\"pay_Su0lKmc0gWrWB7\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 19:06:04', '2026-05-26 19:06:04'),
(19, 37, 31, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_Su0oEr5hpfZqnX', 'pay_Su0oEr5hpfZqnX', '{\"data\":{\"razorpay_payment_id\":\"pay_Su0oEr5hpfZqnX\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-26 19:08:46', '2026-05-26 19:08:46'),
(20, 27, 2, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_SunPoiXudeNTis', 'pay_SunPoiXudeNTis', '{\"data\":{\"razorpay_payment_id\":\"pay_SunPoiXudeNTis\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-28 18:41:49', '2026-05-28 18:41:49'),
(21, 27, 3, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_SunbvZWX7JLAhY', 'pay_SunbvZWX7JLAhY', '{\"data\":{\"razorpay_payment_id\":\"pay_SunbvZWX7JLAhY\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-28 18:53:18', '2026-05-28 18:53:18'),
(22, 27, 33, 299.00, 18.00, 53.82, 352.82, 'razorpay', 'pay_SunjCgru6QmsMv', 'pay_SunjCgru6QmsMv', '{\"data\":{\"razorpay_payment_id\":\"pay_SunjCgru6QmsMv\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-05-28 19:00:11', '2026-05-28 19:00:11'),
(23, 28, 38, 0.00, 0.00, 0.00, 0.00, 'razorpay', '112454xs54x54ss', 'saasa45454d5e1s', '{\"data\":{\"ss\":11}}', 'success', '2026-07-28 16:08:20', '2026-07-28 16:08:20'),
(24, 28, 39, 0.00, 0.00, 0.00, 0.00, 'razorpay', '112454xs54x54ss', 'saasa45454d5e1s', '{\"data\":{\"ss\":11}}', 'success', '2026-07-28 16:54:15', '2026-07-28 16:54:15'),
(25, 40, 40, 0.00, 0.00, 0.00, 0.00, 'razorpay', '1785253320326', '1785253320326', '{\"data\":{\"type\":\"self\"}}', 'success', '2026-07-28 21:12:01', '2026-07-28 21:12:01'),
(26, 41, 41, 0.00, 0.00, 0.00, 0.00, 'razorpay', '1785335531415', '1785335531415', '{\"data\":{\"type\":\"self\"}}', 'success', '2026-07-29 20:02:11', '2026-07-29 20:02:11'),
(27, 42, 43, 0.00, 0.00, 0.00, 0.00, 'razorpay', '1785338480521', '1785338480521', '{\"data\":{\"type\":\"self\"}}', 'success', '2026-07-29 20:51:21', '2026-07-29 20:51:21'),
(28, 43, 45, 0.00, 0.00, 0.00, 0.00, 'razorpay', '1785343909223', '1785343909223', '{\"data\":{\"type\":\"self\"}}', 'success', '2026-07-29 22:21:49', '2026-07-29 22:21:49'),
(29, 43, 46, 499.00, 18.00, 89.82, 588.82, 'razorpay', 'pay_TJOKYiLeLZ2cQ5', 'pay_TJOKYiLeLZ2cQ5', '{\"data\":{\"razorpay_payment_id\":\"pay_TJOKYiLeLZ2cQ5\",\"razorpay_order_id\":null,\"razorpay_signature\":null}}', 'success', '2026-07-29 22:24:38', '2026-07-29 22:24:38'),
(30, 43, 48, 0.00, 0.00, 0.00, 0.00, 'razorpay', '1785344297250', '1785344297250', '{\"data\":{\"type\":\"self\"}}', 'success', '2026-07-29 22:28:17', '2026-07-29 22:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'app', '77230a1a1cf4f1a89016c2c26a7c0944cf436f17e95325363a1685f456dc585b', '[\"*\"]', '2026-01-24 10:38:38', NULL, '2026-01-18 12:56:02', '2026-01-24 10:38:38'),
(5, 'App\\Models\\User', 2, 'app', '78d7b5c9ce3114875c4667141cc12b8ae41a33d051773ab1b0de84ebfd565dfc', '[\"*\"]', '2026-01-19 09:24:35', NULL, '2026-01-19 08:59:32', '2026-01-19 09:24:35'),
(6, 'App\\Models\\User', 2, 'app', '16fb6edceefb0cb19f907276c27da81daec8e6463d798c70e6640e7ca3b630d9', '[\"*\"]', '2026-01-19 12:23:58', NULL, '2026-01-19 09:29:40', '2026-01-19 12:23:58'),
(7, 'App\\Models\\User', 2, 'app', '080c8a1fdec0d96e4d01062692dd3089c0ea94dddb721d7d9892fc61dbb921bf', '[\"*\"]', '2026-01-19 09:37:52', NULL, '2026-01-19 09:37:45', '2026-01-19 09:37:52'),
(8, 'App\\Models\\User', 2, 'app', '2956745003aa0378d330b25d6d11f5689c0c6a91af1a43b6c6ca980a57e6e67f', '[\"*\"]', '2026-01-19 12:20:53', NULL, '2026-01-19 12:19:23', '2026-01-19 12:20:53'),
(9, 'App\\Models\\User', 2, 'app', 'bf64f960abb5da5380a842b05cb68b077aa34ddc3da7bccbabbad755a4ac32fa', '[\"*\"]', '2026-01-19 12:27:33', NULL, '2026-01-19 12:27:15', '2026-01-19 12:27:33'),
(11, 'App\\Models\\User', 4, 'app', 'a62ac8f6a6ecfa46efa9833056f1d0949ae7c4a8d7c0a9f84975e0adf54d9dd5', '[\"*\"]', '2026-01-20 17:25:02', NULL, '2026-01-20 17:23:24', '2026-01-20 17:25:02'),
(12, 'App\\Models\\User', 1, 'app', 'bc91d291627ff4fa48058c7d3298fe61d70ee6e9beebc464fb94f9e9b8614132', '[\"*\"]', NULL, NULL, '2026-01-21 15:20:19', '2026-01-21 15:20:19'),
(17, 'App\\Models\\User', 6, 'app', 'c96afb2978f7fa3e7886dd8393eff6c4b25a24c4d7cb76588176b2d6e59f0704', '[\"*\"]', '2026-01-29 14:33:21', NULL, '2026-01-24 11:35:47', '2026-01-29 14:33:21'),
(20, 'App\\Models\\User', 5, 'app', '4cde09bb6007d39edb892c3e8245cc9dab677227607dd5a5a7b47677ec88f3a3', '[\"*\"]', '2026-01-25 14:29:53', NULL, '2026-01-25 14:29:33', '2026-01-25 14:29:53'),
(21, 'App\\Models\\User', 5, 'app', '4340b00f5078e3dc27c321acc9e5cb82ec3ed2a428dd323984e23a598f34b853', '[\"*\"]', '2026-02-09 15:36:33', NULL, '2026-01-25 14:31:24', '2026-02-09 15:36:33'),
(22, 'App\\Models\\User', 7, 'app', '09213cfa420bedbc779218f2775d0c33ab73dab100995607995cabd01309a3d5', '[\"*\"]', '2026-01-30 20:50:58', NULL, '2026-01-27 17:04:44', '2026-01-30 20:50:58'),
(23, 'App\\Models\\User', 5, 'app', '230e20fffb03b88da1bcb49b8a78b7ad2678a23c16ea65d7676ff8752005b686', '[\"*\"]', '2026-01-28 13:46:15', NULL, '2026-01-28 13:35:41', '2026-01-28 13:46:15'),
(25, 'App\\Models\\User', 9, 'app', 'c11065a3e8a749793dd97a40ff6f11e6c5d03896f98b7e3ee23183193283abc0', '[\"*\"]', '2026-02-03 17:35:30', NULL, '2026-02-03 17:17:19', '2026-02-03 17:35:30'),
(26, 'App\\Models\\User', 9, 'app', '8f930d2b703d84f620197abd0e434f07e83fa0efe6131d6780b735d8452e70b4', '[\"*\"]', '2026-02-05 13:06:48', NULL, '2026-02-05 11:22:25', '2026-02-05 13:06:48'),
(27, 'App\\Models\\User', 5, 'app', '8f5bd6de271f1194a84594a5838dba8eef5fc42e2ed32bf6cb500d0536769d1f', '[\"*\"]', '2026-02-09 06:11:06', NULL, '2026-02-09 05:50:10', '2026-02-09 06:11:06'),
(28, 'App\\Models\\User', 5, 'app', '7b2be575d9328d77c8aecfd3dce231dd7dc2b2687e97c8adf2635a9f15cad9e1', '[\"*\"]', '2026-02-09 15:25:08', NULL, '2026-02-09 13:45:03', '2026-02-09 15:25:08'),
(30, 'App\\Models\\User', 18, 'app', '723b4a14391a2933541d5166dafd8d7cfbc8f54e39e0b99589ab7621be630b0d', '[\"*\"]', '2026-04-17 10:58:32', NULL, '2026-03-18 11:02:12', '2026-04-17 10:58:32'),
(32, 'App\\Models\\User', 20, 'app', 'e953658fe9ca5bd84d9ab4dcdfe09d9e84acb913090e41423a39b43c834c5d22', '[\"*\"]', '2026-04-14 18:01:31', NULL, '2026-03-30 11:27:55', '2026-04-14 18:01:31'),
(33, 'App\\Models\\User', 2, 'app', '2b80cb5242f61bc4996ac9df16c0e612959aaca8891d02f5e80a170eb7c0da0d', '[\"*\"]', '2026-04-14 19:05:56', NULL, '2026-04-01 19:25:03', '2026-04-14 19:05:56'),
(35, 'App\\Models\\User', 9, 'app', 'e34488d2a5bb23de067ddc2c2b83bcdbc3c41a582120f9e27e6c23a545296a88', '[\"*\"]', '2026-04-06 11:52:59', NULL, '2026-04-06 11:33:45', '2026-04-06 11:52:59'),
(36, 'App\\Models\\User', 9, 'app', 'e45b26cce7c8ed7c17ab1e2aec1a63ab8a4481a3c418fd4da94f0656429b1508', '[\"*\"]', '2026-04-06 12:15:33', NULL, '2026-04-06 11:56:51', '2026-04-06 12:15:33'),
(38, 'App\\Models\\User', 22, 'app', 'c88c7971934c04a1c543aa74e8b0a151c2a6eb3af39dd9650339451af9b172d9', '[\"*\"]', '2026-04-27 22:33:54', NULL, '2026-04-07 11:25:39', '2026-04-27 22:33:54'),
(39, 'App\\Models\\User', 23, 'app', '201450a36505fd1744092657497edd20248ce9098a5ed3fc34a2f56e569a86f8', '[\"*\"]', '2026-04-25 17:51:00', NULL, '2026-04-07 18:02:44', '2026-04-25 17:51:00'),
(40, 'App\\Models\\User', 24, 'app', 'bcd78d1976789687a5d26653156f7790e2b713f5ccc578b87ba6705536ca75a8', '[\"*\"]', '2026-04-08 11:55:59', NULL, '2026-04-07 18:04:34', '2026-04-08 11:55:59'),
(41, 'App\\Models\\User', 25, 'app', 'cb3a45f59cc3054adcdd4f5be8c2e2713b6e6c8de5423ae565fa1a6ed6528de1', '[\"*\"]', '2026-04-07 18:14:17', NULL, '2026-04-07 18:06:43', '2026-04-07 18:14:17'),
(42, 'App\\Models\\User', 2, 'app', '9d92a7661728c623ec5b1b6b7d278094993fe185dfb3256697da21fd45e8cdcf', '[\"*\"]', '2026-04-09 19:33:05', NULL, '2026-04-09 19:32:48', '2026-04-09 19:33:05'),
(43, 'App\\Models\\User', 2, 'app', 'f01543d222bf1b347d7fbf1a68e1223fa161f4f7d15639e2f9611683abe56576', '[\"*\"]', '2026-04-09 21:05:39', NULL, '2026-04-09 21:01:32', '2026-04-09 21:05:39'),
(44, 'App\\Models\\User', 2, 'app', '0e3e4b3f8c35ad964e617dd95b1272b06f59f813cf7ff73bd85072b3ceadff9b', '[\"*\"]', '2026-04-13 10:19:02', NULL, '2026-04-13 10:06:31', '2026-04-13 10:19:02'),
(46, 'App\\Models\\User', 2, 'app', 'eb3e6f8581b4163a4a57fad7129a483999b81d8857f892bad7bb1ca4e007c11b', '[\"*\"]', '2026-04-13 22:14:10', NULL, '2026-04-13 21:50:06', '2026-04-13 22:14:10'),
(47, 'App\\Models\\User', 3, 'app', '839f8c5d34bc8e00ceeeb67f2855367ccfd4019296cce4d25c8c3b379c657bc0', '[\"*\"]', '2026-04-14 19:41:21', NULL, '2026-04-14 17:43:53', '2026-04-14 19:41:21'),
(48, 'App\\Models\\User', 20, 'app', 'babf58c62f1446a87e4a71488ad435c8ece18776984774e7f0c4b0e36b4224b6', '[\"*\"]', '2026-04-14 19:24:26', NULL, '2026-04-14 18:35:47', '2026-04-14 19:24:26'),
(49, 'App\\Models\\User', 2, 'app', '60e75f0e2b7d186456a28fc1fd0eb12d589976c51c6d9aad9f3f0c9685f4de03', '[\"*\"]', '2026-04-14 19:31:21', NULL, '2026-04-14 19:04:23', '2026-04-14 19:31:21'),
(50, 'App\\Models\\User', 2, 'app', 'de0a7ecc58bae3466244f615d90f264810278f297b141cbf4abf6b07fa37f3e9', '[\"*\"]', '2026-04-14 19:47:42', NULL, '2026-04-14 19:47:37', '2026-04-14 19:47:42'),
(52, 'App\\Models\\User', 21, 'app', '5f5c4a9e2dafaf1b54f4debaf92165eb6ce4690efb737bbd28c94504ef3b567b', '[\"*\"]', '2026-04-23 11:10:41', NULL, '2026-04-23 10:23:23', '2026-04-23 11:10:41'),
(53, 'App\\Models\\User', 9, 'app', '05c9c432ef6db9bce32f938e047da860d899781dde6be0dc310998be88b562cb', '[\"*\"]', '2026-04-25 18:00:45', NULL, '2026-04-25 17:48:59', '2026-04-25 18:00:45'),
(54, 'App\\Models\\User', 23, 'app', 'a47d81be8546e910456ebe6a09f86df4a4e61aa52467c10954f968b243e368f2', '[\"*\"]', '2026-04-25 18:00:36', NULL, '2026-04-25 17:53:30', '2026-04-25 18:00:36'),
(57, 'App\\Models\\User', 28, 'app', 'ab3eb3a8f24a2ef3d40f82c63a76bcc781ecd0f2fcb266d9ac4f6ae0e96e9d8d', '[\"*\"]', '2026-05-10 23:59:19', NULL, '2026-05-10 10:29:06', '2026-05-10 23:59:19'),
(58, 'App\\Models\\User', 29, 'app', '949041749da35dffde0730de7aace6ba45408133b08fb1310eb476df12b6fd44', '[\"*\"]', '2026-05-21 20:09:28', NULL, '2026-05-21 19:42:30', '2026-05-21 20:09:28'),
(59, 'App\\Models\\User', 28, 'app', '8eb50683a9a07507ab5be94fb54121ba7a7db3269a8e120ca02689eabe23eb67', '[\"*\"]', '2026-05-24 09:49:16', NULL, '2026-05-24 08:51:48', '2026-05-24 09:49:16'),
(67, 'App\\Models\\User', 33, 'app', '5c5873f0bb6e7469a963376a3b8f45b1262d89ee5995e540231c8ead29393156', '[\"*\"]', '2026-06-18 15:31:18', NULL, '2026-05-26 18:17:46', '2026-06-18 15:31:18'),
(69, 'App\\Models\\User', 35, 'app', 'abbe383948ed5e6cf5251b5d94c6db4221ad1e7f83cb688884e3b2b4a31258cb', '[\"*\"]', '2026-05-26 18:27:35', NULL, '2026-05-26 18:19:31', '2026-05-26 18:27:35'),
(70, 'App\\Models\\User', 36, 'app', 'dc835d54305451613466a3250761ac7d06ff014bdea806288fd433936cf4937e', '[\"*\"]', '2026-05-27 10:00:07', NULL, '2026-05-26 18:36:37', '2026-05-27 10:00:07'),
(71, 'App\\Models\\User', 37, 'app', '0c9176905bc8dd8617208c9c0492b8ee874d502df829ba6cc5e3b5f24619ed42', '[\"*\"]', '2026-05-26 19:09:57', NULL, '2026-05-26 19:01:02', '2026-05-26 19:09:57'),
(72, 'App\\Models\\User', 27, 'app', '8b2ceab3a2cbb00bdab40f1467ef26262b2aa813f6b70164239c3f70305f7d55', '[\"*\"]', '2026-05-28 19:03:14', NULL, '2026-05-28 18:37:25', '2026-05-28 19:03:14'),
(91, 'App\\Models\\User', 39, 'app', 'c6a75b9574c2a2b7df635193f566c388423b703d6ff54940862d6c64c2bfb8cc', '[\"*\"]', '2026-05-30 00:09:59', NULL, '2026-05-30 00:09:51', '2026-05-30 00:09:59'),
(93, 'App\\Models\\User', 28, 'app', 'ce3c589acbf01a65c88b9ec3576982444616d2fa50c54929d218c5f399f8ed65', '[\"*\"]', '2026-07-28 16:54:15', NULL, '2026-07-28 15:54:03', '2026-07-28 16:54:15'),
(94, 'App\\Models\\User', 28, 'app', '1f4d3c98996ffc7d165211cd2bd5d85b9907b6c313bf00121a56c1ac606ad278', '[\"*\"]', '2026-07-28 16:54:20', NULL, '2026-07-28 16:48:20', '2026-07-28 16:54:20'),
(96, 'App\\Models\\User', 40, 'app', '4c84ba45b5d439231226d6911657fd96ec569f4aa456ddda1fc0729ebf27bed2', '[\"*\"]', '2026-07-28 21:12:26', NULL, '2026-07-28 21:09:33', '2026-07-28 21:12:26'),
(97, 'App\\Models\\User', 41, 'app', '8e7580dca4af8222c6255c3312bf33d6f1900d4a7e6c227d088dfd8aa3828916', '[\"*\"]', '2026-08-16 12:21:12', NULL, '2026-07-29 19:57:39', '2026-08-16 12:21:12'),
(98, 'App\\Models\\User', 42, 'app', '6395678a947c88997f93269d66d16793f8252001d9de82d0114159a579325328', '[\"*\"]', '2026-08-12 12:43:07', NULL, '2026-07-29 20:45:33', '2026-08-12 12:43:07'),
(99, 'App\\Models\\User', 43, 'app', '2a9a3bb472957eac37792a58c30dc1e3ca91959281ac4b53b07e44e25589baab', '[\"*\"]', '2026-07-29 22:29:43', NULL, '2026-07-29 22:18:45', '2026-07-29 22:29:43');

-- --------------------------------------------------------

--
-- Table structure for table `remarketing_log`
--

CREATE TABLE `remarketing_log` (
  `id` int(11) NOT NULL,
  `rec_date` datetime NOT NULL DEFAULT current_timestamp(),
  `cron_type` varchar(50) NOT NULL DEFAULT 'company' COMMENT 'sms, whatsapp',
  `cronname` varchar(256) NOT NULL,
  `msgcount` int(11) NOT NULL,
  `msgresponse` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `remarketing_log`
--

INSERT INTO `remarketing_log` (`id`, `rec_date`, `cron_type`, `cronname`, `msgcount`, `msgresponse`) VALUES
(1, '2026-03-15 22:50:58', 'company', 'whatsapp-1', 10, '6300840508-{\"name\":\"ERR402\",\"errorCode\":402,\"errorMessage\":\"Insufficient WhatsApp Conversation Credits (WCC)!\"}|9408881214-{\"name\":\"ERR402\",\"errorCode\":402,\"errorMessage\":\"Insufficient WhatsApp Conversation Credits (WCC)!\"}|7984310891-{\"name\":\"ERR402\",\"errorCode\":402,\"errorMessage\":\"Insufficient WhatsApp Conversation Credits (WCC)!\"}|');

-- --------------------------------------------------------

--
-- Table structure for table `self_login_banks`
--

CREATE TABLE `self_login_banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `pl_link` text NOT NULL,
  `bl_link` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `self_login_banks`
--

INSERT INTO `self_login_banks` (`id`, `label`, `logo`, `pl_link`, `bl_link`, `status`) VALUES
(1, 'Faircent', '043.png', 'https://in.faircentpro.com/?utm_source=wl&utm_medium=Mailer&campaign_name=Borrower_Partner&agf=WLA116816', 'https://in.faircentpro.com/?utm_source=wl&utm_medium=Mailer&campaign_name=Borrower_Partner&agf=WLA116816', 1),
(2, 'Werize', '044.png', 'https://application.werize.com/home?partnerId=74f20c9f-23c6-48da-a71f-1c3627300973 ', 'https://application.werize.com/unsecuredBusinessLoanViral?partnerId=74f20c9f-23c6-48da-a71f-1c3627300973 ', 1),
(3, 'Aditya Birla Capital', '045.png', 'https://abcd.adityabirlacapital.com/PersonalLoan/?utm_source=referral_partner&utm_medium=referral_partner&utm_campaign=referral_partner&utm_term=referralpartner_personalloan&utm_id=referralpartner_2025&utm_content=referralpartner_banner&utm_adid=MU03257377&utm_adgroup=referralpartner_acquisition', 'https://creditlink.finbox.in/?partnerCode=AB_QXBWWO&agentCode=ss494198&productType=business_loan', 1),
(4, 'DMI Finance', '046.png', 'https://cpadvisordigital.in/loan-application/dmi-finance/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/dmi-finance/84447283/flubbi-fintech-llp', 1),
(5, 'Muthoot FinCorp', '047.png', 'https://cpadvisordigital.in/loan-application/muthoot-fincorp-dailyvyapar-mitra/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/muthoot-fincorp-dailyvyapar-mitra/84447283/flubbi-fintech-llp', 1),
(6, 'Prefr', '048.png', 'https://www.urbanmoney.com/personal-loan/prefr/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/prefr/apply-online?partnerId=NjI5Mjg2', 1),
(7, 'Incred', '049.png', 'https://www.urbanmoney.com/personal-loan/incred-financial-services/apply-now?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/incred-financial-services/apply-now?partnerId=NjI5Mjg2', 1),
(8, 'Finnable', '050.png', 'https://cpadvisordigital.in/loan-application/finnable/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/finnable/84447283/flubbi-fintech-llp', 1),
(9, 'Fatakpay', '051.jpg', 'https://www.urbanmoney.com/personal-loan/fatak-pay/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/fatak-pay/apply-online?partnerId=NjI5Mjg2', 1),
(10, 'bajaj-finserv', '052.png', 'https://cpadvisordigital.in/loan-application/bajaj-finserv/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/bajaj-finserv/84447283/flubbi-fintech-llp', 1),
(11, 'moneyview', '053.png', 'https://cpadvisordigital.in/loan-application/moneyview/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/moneyview/84447283/flubbi-fintech-llp', 1),
(12, 'zype', '054.png', 'https://www.urbanmoney.com/personal-loan/zype/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/zype/apply-online?partnerId=NjI5Mjg2', 1),
(13, 'l&t finance', '055.jpg', 'https://cpadvisordigital.in/loan-application/lt-personal-loan/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/lt-personal-loan/84447283/flubbi-fintech-llp', 1),
(14, 'tata-capital', '056.png', 'https://www.urbanmoney.com/personal-loan/tata-capital-financial-services-ltd/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/tata-capital-financial-services-ltd/apply-online?partnerId=NjI5Mjg2', 1),
(15, 'poonawalla', '057.png', 'https://cpadvisordigital.in/loan-application/poonawalla/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/poonawalla/84447283/flubbi-fintech-llp', 1),
(16, 'bajaj market', '058.png', 'https://cpadvisordigital.in/loan-application/bajaj-market-instant/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/bajaj-market-instant/84447283/flubbi-fintech-llp', 1),
(17, 'bright', '059.png', 'https://cpadvisordigital.in/loan-application/bright-loans/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/bright-loans/84447283/flubbi-fintech-llp', 1),
(18, 'iifl', '060.png', 'https://cpadvisordigital.in/loan-application/iifl/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/iifl/84447283/flubbi-fintech-llp', 1),
(19, 'flexi loans', '061.png', 'https://cpadvisordigital.in/loan-application/flexi-loans/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/flexi-loans/84447283/flubbi-fintech-llp', 1),
(20, 'udyog plus', '062.png', 'https://cpadvisordigital.in/loan-application/abfl-udyog-plus/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/abfl-udyog-plus/84447283/flubbi-fintech-llp', 1),
(21, 'unity small finance', '063.png', 'https://www.urbanmoney.com/personal-loan/unity-small-finance/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/unity-small-finance/apply-online?partnerId=NjI5Mjg2', 1),
(22, 'bhanix finance', '064.png', 'https://www.urbanmoney.com/personal-loan/bhanix-finance/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/bhanix-finance/apply-online?partnerId=NjI5Mjg2', 1),
(23, 'piramal finance', '065.png', 'https://www.urbanmoney.com/personal-loan/piramal-finance/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/piramal-finance/apply-online?partnerId=NjI5Mjg2', 1),
(24, 'hero-fincorp', '066.png', 'https://www.urbanmoney.com/personal-loan/hero-fincorp/apply-online?partnerId=NjI5Mjg2', 'https://www.urbanmoney.com/personal-loan/hero-fincorp/apply-online?partnerId=NjI5Mjg2', 1),
(25, 'credit saison', '067.png', 'https://cpadvisordigital.in/loan-application/credit-saison/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/credit-saison/84447283/flubbi-fintech-llp', 1),
(26, 'Chola Finance', '068.png', 'https://cpadvisordigital.in/loan-application/chola/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/chola/84447283/flubbi-fintech-llp', 1),
(27, 'indifi Finance', '069.png', 'https://cpadvisordigital.in/loan-application/indifi/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/chola/84447283/flubbi-fintech-llp', 1),
(28, 'lendingkart', '070.png', 'https://cpadvisordigital.in/loan-application/lendingkart/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/lendingkart/84447283/flubbi-fintech-llp', 1),
(29, 'Ram Fincorp ', '071.png', 'https://cpadvisordigital.in/loan-application/ram-fincorp/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/ram-fincorp/84447283/flubbi-fintech-llp', 1),
(30, 'HDFC', '072.png', 'https://cpadvisordigital.in/loan-application/hdfc/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/hdfc/84447283/flubbi-fintech-llp', 1),
(31, 'induslnd', '073.png', 'https://cpadvisordigital.in/loan-application/induslnd-bank/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/induslnd-bank/84447283/flubbi-fintech-llp', 1),
(32, 'mmbmy money bazaar', '074.png', 'https://cpadvisordigital.in/loan-application/mmbmy-money-bazaar/84447283/flubbi-fintech-llp', 'https://cpadvisordigital.in/loan-application/mmbmy-money-bazaar/84447283/flubbi-fintech-llp', 1);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `status`) VALUES
(1, 'Andhra Pradesh', 0),
(2, 'Arunachal Pradesh', 0),
(3, 'Assam', 0),
(4, 'Bihar', 0),
(5, 'Chhattisgarh', 0),
(6, 'Goa', 0),
(7, 'Gujarat', 0),
(8, 'Haryana', 0),
(9, 'Himachal Pradesh', 0),
(10, 'Jharkhand', 0),
(11, 'Karnataka', 0),
(12, 'Kerala', 0),
(13, 'Madhya Pradesh', 0),
(14, 'Maharashtra', 0),
(15, 'Manipur', 0),
(16, 'Meghalaya', 0),
(17, 'Mizoram', 0),
(18, 'Nagaland', 0),
(19, 'Odisha', 0),
(20, 'Punjab', 0),
(21, 'Rajasthan', 0),
(22, 'Sikkim', 0),
(23, 'Tamil Nadu', 0),
(24, 'Telangana', 0),
(25, 'Tripura', 0),
(26, 'Uttar Pradesh', 0),
(27, 'Uttarakhand', 0),
(28, 'West Bengal', 0),
(29, 'Andaman and Nicobar Islands', 0),
(30, 'Chandigarh', 0),
(31, 'Dadra and Nagar Haveli and Daman and Diu', 0),
(32, 'Delhi', 0),
(33, 'Jammu and Kashmir', 0),
(34, 'Ladakh', 0),
(35, 'Lakshadweep', 0),
(36, 'Puducherry', 0);

-- --------------------------------------------------------

--
-- Table structure for table `support_reasons`
--

CREATE TABLE `support_reasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_reasons`
--

INSERT INTO `support_reasons` (`id`, `label`, `status`) VALUES
(1, 'Loan Application Issue', 1),
(2, 'Payment Related', 1),
(3, 'Document Verification', 1),
(4, 'Loan Status Delay', 1),
(5, 'App / Technical Issue', 1),
(6, 'Other', 1);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_no` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL DEFAULT 0,
  `message` text NOT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `admin_reply` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `ticket_no`, `user_id`, `reason_id`, `message`, `priority`, `status`, `admin_reply`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 'ST-20260119-IIMKL9', 2, 4, 'dmeo', 'medium', 'open', NULL, NULL, '2026-01-19 06:48:58', '2026-01-19 06:48:58'),
(2, 'ST-20260205-F96LSI', 9, 4, 'hi', 'medium', 'open', NULL, NULL, '2026-02-05 13:06:12', '2026-02-05 13:06:12'),
(3, 'ST-20260314-VJDMND', 3, 3, 'Loan approved but amount not received', 'medium', 'open', NULL, NULL, '2026-03-14 10:27:32', '2026-03-14 10:27:32'),
(4, 'ST-20260315-BLW1EW', 3, 3, 'Loan approved but amount not received', 'medium', 'open', NULL, NULL, '2026-03-15 06:26:14', '2026-03-15 06:26:14'),
(5, 'ST-20260330-WDVKVT', 20, 3, 'Loan approved but amount not received', 'medium', 'open', NULL, NULL, '2026-03-30 11:30:46', '2026-03-30 11:30:46'),
(6, 'ST-20260406-5G5L3P', 9, 4, 'xyz', 'medium', 'open', NULL, NULL, '2026-04-06 12:03:06', '2026-04-06 12:03:06'),
(7, 'ST-20260416-8O04YV', 18, 3, 'jdisispqk', 'medium', 'open', NULL, NULL, '2026-04-16 13:07:01', '2026-04-16 13:07:01'),
(8, 'ST-20260425-MRXYBL', 9, 4, 'hi', 'medium', 'open', NULL, NULL, '2026-04-25 17:56:35', '2026-04-25 17:56:35'),
(9, 'ST-20260524-WQOIYS', 28, 3, 'bshsjsjsj', 'medium', 'open', NULL, NULL, '2026-05-24 09:35:15', '2026-05-24 09:35:15'),
(10, 'ST-20260729-DEZOX8', 43, 4, 'hi', 'medium', 'open', NULL, NULL, '2026-07-29 22:29:22', '2026-07-29 22:29:22');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `loan_application_id` int(11) NOT NULL DEFAULT 0,
  `gateway_response` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`id`, `user_id`, `loan_application_id`, `gateway_response`, `created_at`, `updated_at`) VALUES
(1, 26, 1, '{\"data\":{\"loan_application_id\":\"1\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Sikbai050ihX3K\",\"gateway_transaction_id\":\"pay_Sikbai050ihX3K\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Sikbai050ihX3K\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-04-28 08:08:41', '2026-04-28 08:08:41'),
(2, 29, 5, '{\"data\":{\"loan_application_id\":\"5\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Ss2zdvt1XGvGfw\",\"gateway_transaction_id\":\"pay_Ss2zdvt1XGvGfw\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Ss2zdvt1XGvGfw\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-21 19:59:09', '2026-05-21 19:59:09'),
(3, 28, 4, '{\"data\":{\"loan_application_id\":\"4\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St3kNLu6zge5t2\",\"gateway_transaction_id\":\"pay_St3kNLu6zge5t2\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St3kNLu6zge5t2\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 09:22:15', '2026-05-24 09:22:15'),
(4, 28, 7, '{\"data\":{\"loan_application_id\":\"7\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St3sdIVW6rTQRo\",\"gateway_transaction_id\":\"pay_St3sdIVW6rTQRo\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St3sdIVW6rTQRo\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 09:30:12', '2026-05-24 09:30:12'),
(5, 30, 8, '{\"data\":{\"loan_application_id\":\"8\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St5hpb8KPOYDYq\",\"gateway_transaction_id\":\"pay_St5hpb8KPOYDYq\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St5hpb8KPOYDYq\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 11:17:25', '2026-05-24 11:17:25'),
(6, 30, 9, '{\"data\":{\"loan_application_id\":\"9\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St5lvkM1WcMHuK\",\"gateway_transaction_id\":\"pay_St5lvkM1WcMHuK\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St5lvkM1WcMHuK\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 11:21:17', '2026-05-24 11:21:17'),
(7, 30, 10, '{\"data\":{\"loan_application_id\":\"10\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St5rBWJ0VgqHRj\",\"gateway_transaction_id\":\"pay_St5rBWJ0VgqHRj\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St5rBWJ0VgqHRj\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 11:26:12', '2026-05-24 11:26:12'),
(8, 30, 11, '{\"data\":{\"loan_application_id\":\"11\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St6E3CwkpS2o4O\",\"gateway_transaction_id\":\"pay_St6E3CwkpS2o4O\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St6E3CwkpS2o4O\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 11:48:20', '2026-05-24 11:48:20'),
(9, 31, 12, '{\"data\":{\"loan_application_id\":\"12\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_St6pyInETBptxw\",\"gateway_transaction_id\":\"pay_St6pyInETBptxw\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_St6pyInETBptxw\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-24 12:23:48', '2026-05-24 12:23:48'),
(10, 32, 13, '{\"data\":{\"loan_application_id\":\"13\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_StMtc9cAlqH5Qz\",\"gateway_transaction_id\":\"pay_StMtc9cAlqH5Qz\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_StMtc9cAlqH5Qz\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-25 04:06:18', '2026-05-25 04:06:18'),
(11, 32, 14, '{\"data\":{\"loan_application_id\":\"14\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_StMvzk9YihvWN6\",\"gateway_transaction_id\":\"pay_StMvzk9YihvWN6\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_StMvzk9YihvWN6\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-25 04:08:33', '2026-05-25 04:08:33'),
(12, 34, 16, '{\"data\":{\"loan_application_id\":\"16\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su00o0SofSLRGp\",\"gateway_transaction_id\":\"pay_Su00o0SofSLRGp\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su00o0SofSLRGp\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 18:22:12', '2026-05-26 18:22:12'),
(13, 34, 20, '{\"data\":{\"loan_application_id\":\"20\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su06VqVlSiZfBb\",\"gateway_transaction_id\":\"pay_Su06VqVlSiZfBb\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su06VqVlSiZfBb\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 18:27:42', '2026-05-26 18:27:42'),
(14, 36, 24, '{\"data\":{\"loan_application_id\":\"24\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su0LCiHvL9Gskd\",\"gateway_transaction_id\":\"pay_Su0LCiHvL9Gskd\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su0LCiHvL9Gskd\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 18:41:23', '2026-05-26 18:41:23'),
(15, 36, 25, '{\"data\":{\"loan_application_id\":\"25\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su0NbSpEW7WYdP\",\"gateway_transaction_id\":\"pay_Su0NbSpEW7WYdP\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su0NbSpEW7WYdP\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 18:43:35', '2026-05-26 18:43:35'),
(16, 36, 26, '{\"data\":{\"loan_application_id\":\"26\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su0RDUxj6cd5JO\",\"gateway_transaction_id\":\"pay_Su0RDUxj6cd5JO\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su0RDUxj6cd5JO\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 18:47:13', '2026-05-26 18:47:13'),
(17, 37, 29, '{\"data\":{\"loan_application_id\":\"29\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su0j5fIolQWayw\",\"gateway_transaction_id\":\"pay_Su0j5fIolQWayw\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su0j5fIolQWayw\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 19:03:53', '2026-05-26 19:03:53'),
(18, 37, 30, '{\"data\":{\"loan_application_id\":\"30\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su0lKmc0gWrWB7\",\"gateway_transaction_id\":\"pay_Su0lKmc0gWrWB7\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su0lKmc0gWrWB7\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 19:06:04', '2026-05-26 19:06:04'),
(19, 37, 31, '{\"data\":{\"loan_application_id\":\"31\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_Su0oEr5hpfZqnX\",\"gateway_transaction_id\":\"pay_Su0oEr5hpfZqnX\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_Su0oEr5hpfZqnX\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-26 19:08:46', '2026-05-26 19:08:46'),
(20, 27, 2, '{\"data\":{\"loan_application_id\":\"2\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_SunPoiXudeNTis\",\"gateway_transaction_id\":\"pay_SunPoiXudeNTis\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_SunPoiXudeNTis\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-28 18:41:49', '2026-05-28 18:41:49'),
(21, 27, 3, '{\"data\":{\"loan_application_id\":\"3\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_SunbvZWX7JLAhY\",\"gateway_transaction_id\":\"pay_SunbvZWX7JLAhY\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_SunbvZWX7JLAhY\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-28 18:53:18', '2026-05-28 18:53:18'),
(22, 27, 33, '{\"data\":{\"loan_application_id\":\"33\",\"base_amount\":\"299\",\"gst_percentage\":\"18\",\"gst_amount\":\"53.82\",\"total_amount\":\"352.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_SunjCgru6QmsMv\",\"gateway_transaction_id\":\"pay_SunjCgru6QmsMv\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_SunjCgru6QmsMv\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-05-28 19:00:11', '2026-05-28 19:00:11'),
(23, 28, 38, '{\"data\":{\"loan_application_id\":38,\"base_amount\":0,\"gst_percentage\":0,\"gst_amount\":0,\"total_amount\":0,\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"112454xs54x54ss\",\"gateway_transaction_id\":\"saasa45454d5e1s\",\"gateway_response\":{\"ss\":11},\"status\":\"success\"}}', '2026-07-28 16:08:20', '2026-07-28 16:08:20'),
(24, 28, 39, '{\"data\":{\"loan_application_id\":39,\"base_amount\":0,\"gst_percentage\":0,\"gst_amount\":0,\"total_amount\":0,\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"112454xs54x54ss\",\"gateway_transaction_id\":\"saasa45454d5e1s\",\"gateway_response\":{\"ss\":11},\"status\":\"success\"}}', '2026-07-28 16:54:15', '2026-07-28 16:54:15'),
(25, 40, 40, '{\"data\":{\"loan_application_id\":\"40\",\"base_amount\":\"0\",\"gst_percentage\":\"0\",\"gst_amount\":\"0\",\"total_amount\":\"0\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"1785253320326\",\"gateway_transaction_id\":\"1785253320326\",\"gateway_response\":{\"type\":\"self\"},\"status\":\"success\"}}', '2026-07-28 21:12:01', '2026-07-28 21:12:01'),
(26, 41, 41, '{\"data\":{\"loan_application_id\":\"41\",\"base_amount\":\"0\",\"gst_percentage\":\"0\",\"gst_amount\":\"0\",\"total_amount\":\"0\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"1785335531415\",\"gateway_transaction_id\":\"1785335531415\",\"gateway_response\":{\"type\":\"self\"},\"status\":\"success\"}}', '2026-07-29 20:02:11', '2026-07-29 20:02:11'),
(27, 42, 43, '{\"data\":{\"loan_application_id\":\"43\",\"base_amount\":\"0\",\"gst_percentage\":\"0\",\"gst_amount\":\"0\",\"total_amount\":\"0\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"1785338480521\",\"gateway_transaction_id\":\"1785338480521\",\"gateway_response\":{\"type\":\"self\"},\"status\":\"success\"}}', '2026-07-29 20:51:21', '2026-07-29 20:51:21'),
(28, 43, 45, '{\"data\":{\"loan_application_id\":\"45\",\"base_amount\":\"0\",\"gst_percentage\":\"0\",\"gst_amount\":\"0\",\"total_amount\":\"0\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"1785343909223\",\"gateway_transaction_id\":\"1785343909223\",\"gateway_response\":{\"type\":\"self\"},\"status\":\"success\"}}', '2026-07-29 22:21:49', '2026-07-29 22:21:49'),
(29, 43, 46, '{\"data\":{\"loan_application_id\":\"46\",\"base_amount\":\"499\",\"gst_percentage\":\"18\",\"gst_amount\":\"89.82\",\"total_amount\":\"588.82\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"pay_TJOKYiLeLZ2cQ5\",\"gateway_transaction_id\":\"pay_TJOKYiLeLZ2cQ5\",\"gateway_response\":{\"razorpay_payment_id\":\"pay_TJOKYiLeLZ2cQ5\",\"razorpay_order_id\":null,\"razorpay_signature\":null},\"status\":\"success\"}}', '2026-07-29 22:24:38', '2026-07-29 22:24:38'),
(30, 43, 48, '{\"data\":{\"loan_application_id\":\"48\",\"base_amount\":\"0\",\"gst_percentage\":\"0\",\"gst_amount\":\"0\",\"total_amount\":\"0\",\"payment_gateway\":\"razorpay\",\"gateway_payment_id\":\"1785344297250\",\"gateway_transaction_id\":\"1785344297250\",\"gateway_response\":{\"type\":\"self\"},\"status\":\"success\"}}', '2026-07-29 22:28:17', '2026-07-29 22:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1=admin,2=user',
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `device_token` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=pending,1=active,2=blocked',
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `pan_card` varchar(255) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `fcm_token` text DEFAULT NULL,
  `profile_pic` varchar(250) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `role`, `name`, `code`, `phone`, `email`, `password`, `device_token`, `status`, `mobile_verified_at`, `created_at`, `updated_at`, `dob`, `gender`, `state_id`, `city_id`, `city`, `pan_card`, `pincode`, `address`, `fcm_token`, `profile_pic`, `deleted_at`) VALUES
(10, '8c1c1075-eb99-4ebd-a829-456048fb51ff', 1, 'Edigitrix', NULL, '8866442201', 'dev@edigitrix.com', '$2y$12$xNrJc0Y7h0n9u.jzq8mCfOjkYSKGdc4kUHf7yRCfmnNrREKrV4j/S', NULL, 1, NULL, '2025-02-12 05:09:22', '2025-02-12 05:09:22', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(11, '12556624-6f9b-44d2-b195-bd2ca531d675', 1, 'Ajay Edigitrix', NULL, '8866442202', 'aj@edigitrix.com', '$2y$12$.ELWuUUrSg7E7Qm0MlrMX.YdRXeJCslpyYsZDeyKUR.yWbAIEiuoa', NULL, 1, NULL, '2025-02-12 05:09:22', '2025-02-12 05:09:22', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(12, '6101ca06-8079-46c3-844b-10461fe9b3f6', 1, 'Kishan Edigitrix', NULL, '8866442203', 'kishan@edigitrix.com', '$2y$12$3fAuOlgCGR9nbfO52lgHj.Ve3BCSP4RhWn5yL0HQNxjK5.BmYZ3la', NULL, 1, NULL, '2025-02-12 05:09:22', '2025-02-12 05:09:22', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(13, 'c15faa5c-0593-4dc2-a4fb-5710a7b854a6', 1, '01 Edigitrix', NULL, '8866442204', 'admin01@edigitrix.com', '$2y$12$gPOc/ULyj0DOHAvZNEZzCOqbzjhbJ2DjcLM.1ae51nUIPTPQ.oRBa', NULL, 1, NULL, '2025-02-12 05:09:22', '2025-02-12 05:09:22', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(14, '11153c2c-3f57-4a93-96e3-24d86bc693c2', 1, '02 Edigitrix', NULL, '8866442205', 'admin02@edigitrix.com', '$2y$12$GZFs1SiPTiCrqO43wHs.L.Po3H0sn8Rha361GS0bSGvCs6CFUKDsi', NULL, 1, NULL, '2025-02-12 05:09:22', '2025-02-12 05:09:22', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(15, '35fb3ff6-07d8-4f89-a05c-3d1ffe383015', 1, 'Dev User', NULL, '8800660044', 'user@edigitrix.com', '$2y$12$DkSI36esxmNQagdzNL/6f.OB3zDKwjsTnNPe9YDXBPWqsOMbt9x5K', NULL, 1, NULL, '2025-02-12 05:09:22', '2025-02-12 05:09:22', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(16, '9e51e54d-6ae9-4b45-a119-fffab912869e', 1, 'Edigitrix - 00', NULL, '8800880000', 'emp_00@edigitrix.com', '$2y$12$ixy9EXzUL7RvMBnCxUfNd.e12V/pDcT6nfjQ6sULOqsPe5TFlmsHa', NULL, 1, NULL, '2025-02-12 05:09:23', '2025-02-12 05:09:23', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(17, '47ee02a4-3080-4a45-8836-478812c54745', 1, 'Edigitrix - 01', NULL, '8800880001', 'emp_01@edigitrix.com', '$2y$12$YuyK5PuwKp3gpU8SO6DXjukORMO.V6ojbmc3rHC/WOpuntGa8IOBe', NULL, 1, NULL, '2025-02-12 05:09:23', '2025-02-12 05:09:23', NULL, NULL, 0, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL),
(26, '92da86d1-f76f-4579-8b66-26881bfaaedb', 2, 'kv', NULL, '26_D_9687887028', 'del26_kv@mail.com', NULL, NULL, 3, '2026-04-28 08:03:31', '2026-04-28 08:03:31', '2026-05-30 00:44:04', NULL, 'male', 2, 71, 'xxx', NULL, '646278', NULL, NULL, NULL, NULL),
(27, '0478b402-7461-41a4-82f9-6a8756486976', 2, 'HARESH DHADUK', NULL, '27_D_9726022099', 'del27_flubbifintech@gmail.com', NULL, NULL, 3, '2026-04-30 18:31:35', '2026-04-30 18:31:35', '2026-07-04 19:08:04', NULL, 'male', 14, 66, 'xxx', NULL, '406098', NULL, NULL, NULL, NULL),
(28, 'f2bde46f-20bb-4dd4-a718-1338223fb56f', 2, 'dev patel', NULL, '8866231236', 'dev@gmail.com', NULL, NULL, 1, '2026-05-10 10:29:06', '2026-05-10 10:29:06', '2026-05-24 08:53:33', NULL, 'male', 7, 29, 'xxx', NULL, '395009', NULL, NULL, NULL, NULL),
(29, '36c8a385-54cc-491a-80d3-1cec63e450aa', 2, 'HARESH DHADUK', NULL, '29_D_9769275689', 'del29_cahareshdhaduk@yahoo.com', NULL, NULL, 3, '2026-05-21 19:42:30', '2026-05-21 19:42:30', '2026-07-04 19:07:37', NULL, 'male', 7, 29, 'xxx', NULL, '394101', NULL, NULL, NULL, NULL),
(30, '389f6785-9957-4e81-8b7e-21d344f5e223', 2, 'Parth Lathiya', NULL, '8160727081', 'parth@yopmail.com', NULL, NULL, 1, '2026-05-24 10:50:28', '2026-05-24 10:50:28', '2026-05-24 10:51:31', NULL, 'male', 7, 29, 'xxx', NULL, '394101', NULL, NULL, NULL, NULL),
(31, 'f6d3fd84-e3ee-42a2-bcd0-a02cf8934845', 2, 'jhon doe', NULL, '8866442200', 'jhonedoe@email.com', NULL, NULL, 1, '2026-05-24 12:21:45', '2026-05-24 12:21:45', '2026-05-28 11:22:30', NULL, 'male', 1, 71, 'xxx', NULL, '395004', NULL, NULL, NULL, NULL),
(32, '7167d3e8-47cd-4d41-b78a-604778b53e5a', 2, 'test ajay', NULL, '8530241214', 'tahay@gmail.com', NULL, NULL, 1, '2026-05-25 04:02:23', '2026-05-25 04:02:23', '2026-05-25 04:03:08', NULL, 'male', 7, 2, 'xxx', NULL, '395010', NULL, NULL, NULL, NULL),
(33, '8f04af81-6658-4311-b7de-27bc6831ff00', 2, 'kruti', NULL, '8758042142', 'krutip@123gmail.com', NULL, NULL, 1, '2026-05-26 18:17:46', '2026-05-26 18:17:46', '2026-05-26 18:18:54', NULL, 'female', 8, 236, 'xxx', NULL, '852364', NULL, NULL, NULL, NULL),
(34, '94646b49-be5f-4d96-9e2c-2ce85c9805b0', 2, 'krish', NULL, '9974344036', 'krishvagshiya@gmail.com', NULL, NULL, 1, '2026-05-26 18:19:02', '2026-05-26 18:19:02', '2026-05-26 18:19:46', NULL, 'male', 7, 18, 'xxx', NULL, '395010', NULL, NULL, NULL, NULL),
(35, 'cbec872d-fec7-4ec9-a769-50496132de1b', 2, 'rajensh', NULL, '7698820142', 'jshdjsj@gmil.com', NULL, NULL, 1, '2026-05-26 18:19:31', '2026-05-26 18:19:31', '2026-05-26 18:20:04', NULL, 'male', 1, 71, 'xxx', NULL, '96565', NULL, NULL, NULL, NULL),
(36, 'a5c59517-ee27-4d19-b665-856bd614c1f8', 2, 'krish', NULL, '7600114425', 'krishvagsahiya@gmail.com', NULL, NULL, 1, '2026-05-26 18:36:37', '2026-05-26 18:36:37', '2026-05-26 18:37:09', NULL, 'male', 1, 70, 'xxx', NULL, '123456', NULL, NULL, NULL, NULL),
(37, '5df719ab-f707-4c98-b22a-05e7739773b9', 2, 'krish', NULL, '9574114392', 'krish@gamil.com', NULL, NULL, 1, '2026-05-26 19:01:02', '2026-05-26 19:01:02', '2026-05-26 19:01:22', NULL, 'male', 29, 751, 'xxx', NULL, '123456', NULL, NULL, NULL, NULL),
(40, '80ff358f-460d-4cfa-814a-3dccf941525a', 2, 'test', NULL, '8866231438', 'test@yopmail.com', NULL, NULL, 1, '2026-07-28 21:09:33', '2026-07-28 21:09:33', '2026-07-28 21:10:09', NULL, 'male', 7, 29, 'xxx', NULL, '390122', NULL, NULL, NULL, NULL),
(41, '89b53c58-6477-42d6-aa8b-3ef745c59efa', 2, 'kvp', NULL, '9687887028', 'kvp@gmail.com', NULL, NULL, 1, '2026-07-29 19:57:39', '2026-07-29 19:57:39', '2026-07-29 20:00:13', NULL, 'male', 7, 29, 'xxx', NULL, '395004', NULL, NULL, NULL, NULL),
(42, 'ba889b60-6fea-40fe-ad18-8e4120aa2eb6', 2, 'HARESH J DHADUK', NULL, '9769275689', 'cahareshdhaduk@gmail.com', NULL, NULL, 1, '2026-07-29 20:45:33', '2026-07-29 20:45:33', '2026-07-29 20:46:39', NULL, 'male', 14, 50, 'xxx', NULL, '400068', NULL, NULL, NULL, NULL),
(43, 'a40778fe-b862-49b6-81b1-eb22d6dbeadf', 2, 'krish', NULL, '9998814760', 'krishvagshiya@gamil.com', NULL, NULL, 1, '2026-07-29 22:18:45', '2026-07-29 22:18:45', '2026-07-29 22:19:24', NULL, 'male', 7, 29, 'xxx', NULL, '395001', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `web_options`
--

CREATE TABLE `web_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `op_group` varchar(80) DEFAULT NULL,
  `op_label` varchar(120) DEFAULT NULL,
  `op_key` varchar(120) DEFAULT NULL,
  `op_value` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-active, 1-deactive',
  `deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0-No, 1-Yes',
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `web_options`
--

INSERT INTO `web_options` (`id`, `uuid`, `op_group`, `op_label`, `op_key`, `op_value`, `note`, `priority`, `status`, `deleted`, `updated_by`, `updated_at`) VALUES
(1, 'cb2523e1-cc14-41e1-a2da-30ba90edc949', 'google', 'google domain verification', 'google_domain_verification', '#', NULL, 0, '0', '0', 12, '2026-02-15 04:44:41'),
(2, 'e4f37ae4-0008-4799-bded-fb31c92ba1ab', 'google', 'google analytics', 'google_analytics', 'G-#', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(3, 'f8125572-782b-4921-b36a-a6ca25dd3bc5', 'google', 'google tag manager', 'google_tag_manager', 'GTM-#', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(4, 'd64357b3-ad1c-4d21-a600-482ecc50a63d', 'pinterest', 'Pinterest Domain Verification', 'pinterest_domain_verification', '##', NULL, 0, '0', '0', 12, '2026-02-15 04:44:01'),
(5, 'dafbbd5c-75fb-4048-b283-bea05c578ad6', '2factor', '2factor OTP SMS Auth Key', 'twofactor_otp_sms_auth_key', '#-ce76-11ef-8b17-0200cd936042', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(6, '238d5c7c-4f43-4b18-86e0-d168597193af', 'facebook', 'Facebook Domain Verification', 'facebook_domain_verification', '#', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(7, 'e22aeb0c-5b66-4f72-8b0b-c4be7d9d3f6f', 'facebook', 'Facebook Pixel Key', 'facebook_pixel_key', '#', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(8, 'b8e26014-cd3c-4b4d-8d1c-f3ed5b151e67', 'facebook', 'Facebook Event Name', 'facebook_event_name', 'Purchase', NULL, 0, '0', '0', 12, '2026-02-15 04:43:53'),
(9, 'b14bfc5d-628f-47f8-99fd-b093b23f5b6e', 'facebook', 'Facebook Event Id', 'facebook_event_id', '#', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(10, '3ad1ba1e-d550-4a29-9db0-e982aad3b65d', 'facebook', 'Facebook Access Token', 'facebook_access_token', '#', NULL, 0, '0', '0', 0, '2025-02-12 05:09:47'),
(11, '3ad1ba1e-d550-4a29-9db0-e982aad3b65e', 'sms', 'Remarketing SMS', 'remarketing_sms', 'Your Pre-Approved Rs.<#preamount>/- Loan Offer is Confirmed. Get Money in Your A/C 5 min. Apply Now https://flubbi.in', NULL, 0, '0', '0', 0, '2026-03-16 01:40:29'),
(12, '4ad1ba1e-d550-4a29-9db0-f982aad3b65f', 'sms', 'Subscription Account SMS', 'subscription_account_sms', 'Your Pre-Approved Rs.<#preamount>/- Loan Offer is Confirmed. Get Money in Your A/C 5 min. Apply Now https://flubbi.in', NULL, 0, '0', '0', 0, '2026-03-16 03:27:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_history`
--
ALTER TABLE `application_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cibil_scores`
--
ALTER TABLE `cibil_scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loan_applications_application_no_unique` (`application_no`);

--
-- Indexes for table `loan_documents`
--
ALTER TABLE `loan_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loan_documents_loan_application_id_unique` (`loan_application_id`);

--
-- Indexes for table `loan_purposes`
--
ALTER TABLE `loan_purposes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_status`
--
ALTER TABLE `loan_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_index` (`user_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_logs`
--
ALTER TABLE `otp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_logs_phone_index` (`phone`);

--
-- Indexes for table `our_partners`
--
ALTER TABLE `our_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `our_partnersxxx`
--
ALTER TABLE `our_partnersxxx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_transactions_loan_application_id_unique` (`loan_application_id`),
  ADD KEY `payment_transactions_user_id_index` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `remarketing_log`
--
ALTER TABLE `remarketing_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `self_login_banks`
--
ALTER TABLE `self_login_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_reasons`
--
ALTER TABLE `support_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `support_tickets_ticket_no_unique` (`ticket_no`),
  ADD KEY `support_tickets_user_id_index` (`user_id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_history_user_id_index` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_state_id_index` (`state_id`),
  ADD KEY `users_city_id_index` (`city_id`);

--
-- Indexes for table `web_options`
--
ALTER TABLE `web_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `web_options_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `web_options_op_key_unique` (`op_key`),
  ADD KEY `priority` (`priority`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `updated_by` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_history`
--
ALTER TABLE `application_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cibil_scores`
--
ALTER TABLE `cibil_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=801;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `loan_documents`
--
ALTER TABLE `loan_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loan_purposes`
--
ALTER TABLE `loan_purposes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `loan_status`
--
ALTER TABLE `loan_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `otp_logs`
--
ALTER TABLE `otp_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `our_partners`
--
ALTER TABLE `our_partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `our_partnersxxx`
--
ALTER TABLE `our_partnersxxx`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `remarketing_log`
--
ALTER TABLE `remarketing_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `self_login_banks`
--
ALTER TABLE `self_login_banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `support_reasons`
--
ALTER TABLE `support_reasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `web_options`
--
ALTER TABLE `web_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

