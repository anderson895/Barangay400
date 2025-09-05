-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 12:56 PM
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
-- Database: `barangay`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_audit`
--

CREATE TABLE `tbl_audit` (
  `audit_id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `brgyOfficer_id` int(11) DEFAULT NULL,
  `requestType` varchar(50) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `role` varchar(11) NOT NULL,
  `details` varchar(255) NOT NULL,
  `processedBy` varchar(100) NOT NULL,
  `dateTimeCreated` datetime DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `lastEdited` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_audit`
--

INSERT INTO `tbl_audit` (`audit_id`, `resident_id`, `brgyOfficer_id`, `requestType`, `user_id`, `role`, `details`, `processedBy`, `dateTimeCreated`, `status`, `lastEdited`) VALUES
(544, 5, NULL, 'First Time Job Seeker', '1', 'admin', 'Certificate Status Updated to: Approved', 'admin', '2025-05-03 03:34:52', 'Certificate Status U', '2025-05-03 03:34:52'),
(545, NULL, NULL, '', '0', 'admin', 'Logged out', '', '2025-05-03 04:48:14', '', '2025-05-03 04:48:14'),
(546, NULL, NULL, '', '1', 'Registratio', 'Registration Initiated', '', '2025-05-03 04:49:46', '', '2025-05-03 04:49:46'),
(547, NULL, NULL, '', '2', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-05-03 04:50:06', '', '2025-05-03 04:50:06'),
(548, NULL, NULL, '', '0', 'admin', 'Logged in', '', '2025-05-03 04:50:49', '', '2025-05-03 04:50:49'),
(549, NULL, NULL, '', '5', 'Registratio', 'Registration Initiated', '', '2025-05-03 18:33:19', '', '2025-05-03 18:33:19'),
(550, NULL, NULL, '', '5', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-05-03 18:33:59', '', '2025-05-03 18:33:59'),
(551, NULL, NULL, '', '1', 'admin', 'Logged in', '', '2025-05-03 18:34:27', '', '2025-05-03 18:34:27'),
(552, NULL, NULL, '', '1', 'admin', 'Logged out', '', '2025-05-03 18:42:28', '', '2025-05-03 18:42:28'),
(553, NULL, NULL, '', '5', 'Registratio', 'Registration Initiated', '', '2025-05-03 18:44:39', '', '2025-05-03 18:44:39'),
(554, NULL, NULL, '', '5', 'Resident', 'Registered in tbl_user and tbl_resident', '', '2025-05-03 18:45:05', '', '2025-05-03 18:45:05'),
(555, NULL, NULL, '', '1', 'admin', 'Logged in', '', '2025-05-03 18:45:27', '', '2025-05-03 18:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banned_users`
--

CREATE TABLE `tbl_banned_users` (
  `ban_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `banned_by` varchar(50) NOT NULL,
  `reason` text DEFAULT NULL,
  `ban_date` datetime DEFAULT current_timestamp(),
  `lift_date` datetime DEFAULT NULL,
  `status` enum('Active','Lifted') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_banned_users`
--

INSERT INTO `tbl_banned_users` (`ban_id`, `user_id`, `banned_by`, `reason`, `ban_date`, `lift_date`, `status`) VALUES
(4, '4', '1', 's', '2025-04-16 22:12:01', '2025-04-17 18:08:23', 'Lifted');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bid`
--

CREATE TABLE `tbl_bid` (
  `BID_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  `ID_No` varchar(20) NOT NULL,
  `precinctNumber` varchar(20) DEFAULT NULL,
  `bloodType` varchar(5) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `SSSGSIS_Number` varchar(20) DEFAULT NULL,
  `TIN_number` varchar(15) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `dateApplied` datetime NOT NULL DEFAULT current_timestamp(),
  `dateIssued` date DEFAULT NULL,
  `dateExpiration` date DEFAULT NULL,
  `personTwoName` varchar(100) DEFAULT NULL,
  `personTwoAddress` varchar(255) DEFAULT NULL,
  `personTwoContactInfo` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bid`
--

INSERT INTO `tbl_bid` (`BID_id`, `resident_id`, `user_id`, `last_name`, `first_name`, `middle_name`, `suffix`, `address`, `marital_status`, `ID_No`, `precinctNumber`, `bloodType`, `birthdate`, `birthplace`, `height`, `weight`, `status`, `SSSGSIS_Number`, `TIN_number`, `document_path`, `dateApplied`, `dateIssued`, `dateExpiration`, `personTwoName`, `personTwoAddress`, `personTwoContactInfo`, `created_at`) VALUES
(5, 5, '1', 'Robin', 'Nico', '', '', 'Makati City', '', '5454545454', '15255544558', 'A+', '1995-08-10', 'Pembo Makati City', 155.00, 55.00, 'Denied', '561256456145', '45451514555', '1744811868_template.pdf', '2025-04-16 21:57:00', NULL, NULL, 'Kim', 'Lef', 'sdas', '2025-05-01 22:34:14'),
(7, 5, '1', 'Robin', 'Nico', '', '', '234567654321ewrsdgfhjk', 'Single', '', '342sfsdfs', 'B+', '2007-01-17', 'Sampaloc City', 234.00, 32.00, 'Approved', '423578oi87654', '234567897654', '1746166101_68146155af188_temp-id.jpg', '2025-05-02 14:08:21', NULL, NULL, 'Kathleen Nicole Panis', 'qewrtyguo\';esdfghjkl', '01234567897', '2025-05-02 06:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_blotter`
--

CREATE TABLE `tbl_blotter` (
  `blotter_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `dateFiled` date NOT NULL,
  `caseNumber` varchar(20) NOT NULL,
  `complainant` varchar(100) NOT NULL,
  `respondent` varchar(100) NOT NULL,
  `victim` varchar(100) DEFAULT NULL,
  `witness` varchar(100) DEFAULT NULL,
  `natureOfCase` varchar(100) NOT NULL,
  `blotter_desc` text NOT NULL,
  `brgyOfficer_id` int(11) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_blotter`
--

INSERT INTO `tbl_blotter` (`blotter_id`, `resident_id`, `user_id`, `dateFiled`, `caseNumber`, `complainant`, `respondent`, `victim`, `witness`, `natureOfCase`, `blotter_desc`, `brgyOfficer_id`, `document_path`, `status`, `created_at`) VALUES
(12, 1, '3', '2025-04-16', 'CASE-20250416-8713', 'Chris Doe J', 'Wilfredo Jr.', 'Chris Doe J', 'Kim Doe J', 'Family Dispute', '', 1, '1744811890_f.jpg', 'Pending', '2025-04-16 14:09:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brgyofficer`
--

CREATE TABLE `tbl_brgyofficer` (
  `brgyOfficer_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `birthDate` date NOT NULL,
  `startTerm` date NOT NULL,
  `endTerm` date DEFAULT NULL,
  `is_senior` enum('Yes','No') DEFAULT 'No',
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brgyofficer`
--

INSERT INTO `tbl_brgyofficer` (`brgyOfficer_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `address`, `mobile`, `position`, `birthDate`, `startTerm`, `endTerm`, `is_senior`, `is_pwd`, `status`, `created_at`) VALUES
(1, '2', 'Johns', 'J.s', 'Does', 'Makati', '0981026533', 'Barangay Treasurer', '1988-08-10', '2019-05-10', '2025-05-08', 'No', 'No', 'Active', '2025-04-16 14:10:20'),
(4, '4', 'Chris', 'Medina', 'Chua', 'Makati', '09318689501', 'Barangay Secretary', '1993-10-08', '2022-10-05', '2022-10-05', 'No', 'No', 'Active', '2025-04-25 13:27:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certification`
--

CREATE TABLE `tbl_certification` (
  `certification_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `certificationType` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `remarks` text DEFAULT NULL,
  `registeredVoter` tinyint(1) DEFAULT 0,
  `resident_status` tinyint(1) DEFAULT 0,
  `dateToday` date NOT NULL DEFAULT current_timestamp(),
  `dateApplied` datetime NOT NULL DEFAULT current_timestamp(),
  `dateReceived` date DEFAULT NULL,
  `residentSignature` varchar(255) DEFAULT NULL,
  `document_path` longtext NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type_of_calamity` varchar(100) DEFAULT NULL,
  `calamity_date` date DEFAULT NULL,
  `requested_by` varchar(100) DEFAULT NULL,
  `calamity_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_certification`
--

INSERT INTO `tbl_certification` (`certification_id`, `resident_id`, `user_id`, `certificationType`, `name`, `suffix`, `address`, `purpose`, `remarks`, `registeredVoter`, `resident_status`, `dateToday`, `dateApplied`, `dateReceived`, `residentSignature`, `document_path`, `status`, `created_at`, `type_of_calamity`, `calamity_date`, `requested_by`, `calamity_notes`) VALUES
(29, 1, '3', 'Calamity', 'Screenshot 2025-04-25 222742.png', NULL, 'gastambidest', 'Maynilad Requirement', '', 1, 1, '2025-04-26', '2025-04-26 14:05:00', NULL, NULL, '1745647572_680c77d40e06f_pngfind.com-default-image-png-6764065.png,1745647572_680c77d40eec7_Screenshot 2025-04-25 222742.png', 'Approved', '2025-05-01 14:11:37', NULL, NULL, NULL, NULL),
(30, 5, '1', 'Good Moral', 'Nico Robin', NULL, '234567654321ewrsdgfhjk', 'Bank Transaction', '', 0, 0, '2025-05-01', '2025-05-01 22:59:41', NULL, NULL, '1746111581_68138c5dc4b90_clearance2.jpeg,1746111581_68138c5dc4f5b_ID.NEW.FORMAT-1_page-0001.jpg', 'Approved', '2025-05-01 19:17:39', NULL, NULL, NULL, NULL),
(31, 5, '1', 'First Time Job Seeker', 'Nico Robin', NULL, '234567654321ewrsdgfhjk', 'Emploment typa shi', '', 0, 1, '2025-05-01', '2025-05-01 23:07:31', NULL, NULL, '1746112051_68138e338c58c_temp_oath_ftjs.jpeg', 'Approved', '2025-05-01 19:38:39', NULL, NULL, NULL, NULL),
(32, 5, '1', 'Calamity', 'Nico Robin', NULL, '234567654321ewrsdgfhjk', 'School Requirement', '', 0, 1, '2025-05-01', '2025-05-02 01:11:00', NULL, NULL, '1746119460_6813ab241be7b_clearance2.jpeg', 'Approved', '2025-05-01 18:49:47', 'Typhoon (Yolanda)', '2025-05-01', 'Kathleen Parker', 'sdfghjkn');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clearance`
--

CREATE TABLE `tbl_clearance` (
  `clearance_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `clearanceType` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `remarks` text DEFAULT NULL,
  `registeredVoter` tinyint(1) DEFAULT 0,
  `birthday` date NOT NULL,
  `dateToday` date NOT NULL DEFAULT current_timestamp(),
  `dateApplied` datetime NOT NULL DEFAULT current_timestamp(),
  `dateReceived` date DEFAULT NULL,
  `residentSignature` varchar(255) DEFAULT NULL,
  `document_path` longtext NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clearance`
--

INSERT INTO `tbl_clearance` (`clearance_id`, `resident_id`, `user_id`, `clearanceType`, `name`, `address`, `purpose`, `remarks`, `registeredVoter`, `birthday`, `dateToday`, `dateApplied`, `dateReceived`, `residentSignature`, `document_path`, `status`, `created_at`) VALUES
(16, 1, '3', 'Barangay Clearance', 'Beige at Ginto Curated Editoryal CopywriterEditor Freelance Website.png', 'sdfsdfsd', 'Maynila Requirement', '', 1, '2025-04-25', '2025-04-24', '2025-04-25 03:32:00', NULL, NULL, '1745523165_680a91dd6cf6e_Beige_at_Ginto_Curated_Editoryal_CopywriterEditor_Freelance_Website-removebg-preview.png,1745523165_680a91dd6d2d8_Beige at Ginto Curated Editoryal CopywriterEditor Freelance Website.png', 'Approved', '2025-05-01 10:11:38'),
(17, 5, '1', 'Barangay Clearance', 'Nico fghjkl Robin', '234567654321ewrsdgfhjk', 'Proof Of Residency', '', 0, '0000-00-00', '2025-05-02', '2025-05-02 18:22:18', NULL, NULL, '1746181338_68149cdaca5ef_temp-id.jpg', 'Approved', '2025-05-02 10:33:00'),
(18, 5, '1', 'Garbage Disposal', 'Nico fghjkl Robin', '234567654321ewrsdgfhjk', 'Other', '', 0, '0000-00-00', '2025-05-02', '2025-05-02 18:28:59', NULL, NULL, '1746181739_68149e6b59f08_temp-clearance.jpg', 'Approved', '2025-05-02 11:51:38'),
(19, 5, '1', 'Declogging', 'Nico fghjkl Robin', '234567654321ewrsdgfhjk', 'Medical Assistance', '', 0, '0000-00-00', '2025-05-02', '2025-05-02 18:29:15', NULL, NULL, '1746181755_68149e7bd46c6_temp-service.jpeg', 'Approved', '2025-05-02 11:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_compgriev`
--

CREATE TABLE `tbl_compgriev` (
  `complaint_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `dateFiled` date NOT NULL,
  `caseNumber` varchar(20) NOT NULL,
  `complainant` varchar(100) NOT NULL,
  `respondent` varchar(100) NOT NULL,
  `victim` varchar(100) DEFAULT NULL,
  `witness` varchar(100) DEFAULT NULL,
  `natureOfCase` varchar(100) NOT NULL,
  `comp_desc` text NOT NULL,
  `actionTaken` text DEFAULT NULL,
  `brgyOfficer_id` int(11) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_compgriev`
--

INSERT INTO `tbl_compgriev` (`complaint_id`, `resident_id`, `user_id`, `dateFiled`, `caseNumber`, `complainant`, `respondent`, `victim`, `witness`, `natureOfCase`, `comp_desc`, `actionTaken`, `brgyOfficer_id`, `document_path`, `status`, `created_at`) VALUES
(8, 1, '3', '2025-04-16', 'CASE-20250416-7101', 'Chris Doe J', 'Wenmar jr', 'Sa', 'Kim Doe J', 'Family Dispute', '', NULL, 1, '1744811908_template.pdf', 'Approved', '2025-04-16 14:21:08'),
(9, 5, '1', '2025-05-02', 'CASE-20250502-6661', 'sdfsdfsd', 'fsdfsdfsdfsd', 'dsfsdfsdfs', 'dfsdfsd', 'Family Dispute', 'tfygjhbjnm,', NULL, 1, '1746206057_6814fd69f24ff_temp-id.jpg', 'To Be Approved', '2025-05-02 20:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

CREATE TABLE `tbl_event` (
  `event_id` int(11) NOT NULL,
  `brgyOfficer_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `comment_date` datetime NOT NULL,
  `dateCreated` datetime DEFAULT current_timestamp(),
  `lastEdited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `brgyOfficer_id`, `resident_id`, `user_id`, `title`, `description`, `image`, `name`, `role`, `comments`, `comment_date`, `dateCreated`, `lastEdited`) VALUES
(9, 4, 0, '4', '4ps meeting', 'we have a 4ps meetings', '1744812116_img.jpg', '', '', '', '0000-00-00 00:00:00', '2025-04-16 22:01:56', '2025-04-16 22:02:12'),
(10, 4, 0, '4', 'Senior Citizen', 'We have a senior Meeting today', '1744884258_Copy of Database ER diagram (Projects Inc) - Database ER diagram (crow\'s foot).png', '', '', '', '0000-00-00 00:00:00', '2025-04-17 18:04:18', '2025-04-17 22:56:38'),
(11, 4, 0, '4', 'Health Meeting', 'asdksabdhjsadsad', '1744903788_Screenshot 2025-04-16 194927.png', '', '', '', '0000-00-00 00:00:00', '2025-04-17 23:29:48', '2025-04-17 23:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_comments`
--

CREATE TABLE `tbl_event_comments` (
  `comment_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `is_official` tinyint(1) DEFAULT 0,
  `comment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event_comments`
--

INSERT INTO `tbl_event_comments` (`comment_id`, `event_id`, `user_id`, `comment`, `position`, `is_official`, `comment_date`) VALUES
(17, 11, '3', 'I don\'t want to hahahahahahaha', 'Resident', 1, '2025-04-17 23:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `feedback_id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `brgyOfficer_id` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `action` text DEFAULT NULL,
  `action_by` varchar(100) DEFAULT NULL,
  `dateCreated` datetime DEFAULT current_timestamp(),
  `lastEdited` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`feedback_id`, `resident_id`, `user_id`, `brgyOfficer_id`, `feedback`, `action`, `action_by`, `dateCreated`, `lastEdited`) VALUES
(3, 1, '3', NULL, 'Hi i want to report something', 'we already have action to yout repot', 'Thomas Esteban', '2025-04-16 21:59:02', '2025-05-02 16:32:08'),
(4, 5, '1', NULL, 'Hello i want to submit a follow up.', 'Hi yes we already accepted ', 'Luffy Monkey', '2025-05-03 00:59:40', '2025-05-03 01:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_generated_documents`
--

CREATE TABLE `tbl_generated_documents` (
  `document_id` int(11) NOT NULL,
  `certification_id` int(11) DEFAULT NULL,
  `resident_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('Certificate','Clearance','ID') NOT NULL,
  `document_subtype` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `other_purpose` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `notification_type` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `user_id`, `message`, `notification_type`, `date_created`, `is_read`) VALUES
(45, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-04-16 17:44:36', 1),
(46, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:34:28', 0),
(47, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:34:31', 0),
(48, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:43:06', 0),
(49, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:46:56', 0),
(50, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:50:11', 0),
(51, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 00:50:16', 0),
(52, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 01:24:32', 0),
(53, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 01:24:44', 0),
(54, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 01:54:40', 0),
(55, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 02:06:29', 0),
(56, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 07:57:37', 0),
(57, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 07:59:18', 0),
(58, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:00:17', 0),
(59, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:00:25', 0),
(60, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:01:42', 0),
(61, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:01:49', 0),
(62, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:01:57', 0),
(63, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:02:27', 0),
(64, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 08:38:02', 0),
(65, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:38:08', 0),
(66, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 08:58:48', 0),
(67, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 09:18:49', 0),
(68, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 09:29:12', 0),
(69, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 12:02:57', 0),
(70, '3', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-01 12:11:38', 0),
(71, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 15:48:10', 0),
(72, '3', 'Your certificate request has been updated to: Denied', 'certification_update', '2025-05-01 16:11:29', 0),
(73, '3', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 16:11:37', 0),
(74, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 19:57:11', 1),
(75, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 20:05:24', 1),
(76, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 21:17:39', 1),
(77, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-01 21:38:39', 1),
(78, '1', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-05-02 08:33:35', 0),
(79, '1', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-05-02 08:33:37', 0),
(80, '1', 'Your barangay ID request has been updated to: Approved', 'bid_update', '2025-05-02 08:33:51', 0),
(81, '1', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-02 12:32:47', 0),
(82, '1', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-02 12:32:53', 0),
(83, '1', 'Your clearance request has been updated to: Approved', 'clearance_update', '2025-05-02 12:33:00', 0),
(84, '1', 'Your certificate request has been updated to: Approved', 'certification_update', '2025-05-02 21:34:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_resident`
--

CREATE TABLE `tbl_resident` (
  `resident_id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `precint_number` varchar(50) DEFAULT NULL,
  `residentStatus` varchar(20) NOT NULL,
  `voter_status` enum('Active','Inactive','Not Registered') DEFAULT 'Not Registered',
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') DEFAULT 'Unknown',
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `type_of_id` varchar(100) DEFAULT NULL,
  `id_number` varchar(100) DEFAULT NULL,
  `SSSGSIS_Number` varchar(20) DEFAULT NULL,
  `TIN_number` varchar(15) DEFAULT NULL,
  `barangay_number` varchar(20) DEFAULT NULL,
  `is_senior_citizen` enum('Yes','No') DEFAULT 'No',
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `is_4ps_member` enum('Yes','No') DEFAULT 'No',
  `suffix` varchar(20) DEFAULT NULL,
  `is_household_head` tinyint(1) DEFAULT 0,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `senior_document` varchar(255) DEFAULT NULL,
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency_document` varchar(255) DEFAULT NULL,
  `residency_tenure` enum('Temporary','Permanent') DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_resident`
--

INSERT INTO `tbl_resident` (`resident_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `birthdate`, `birthplace`, `marital_status`, `phone_number`, `gender`, `address`, `precint_number`, `residentStatus`, `voter_status`, `blood_type`, `height`, `weight`, `type_of_id`, `id_number`, `SSSGSIS_Number`, `TIN_number`, `barangay_number`, `is_senior_citizen`, `is_pwd`, `is_4ps_member`, `suffix`, `is_household_head`, `household_head_name`, `relationship_to_head`, `senior_document`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency_document`, `residency_tenure`, `occupation`, `email`, `image`, `created_at`, `updated_at`) VALUES
(1, '3', 'Chris', 'J.', 'Doe', '1995-08-10', 'Pembo Makati City', 'Married', '09810265312', 'Male', 'Makati', '15255544558', 'Permanent', '', 'A+', 160.00, 55.00, 'TIN ID', '15454541125', '561256456145', '45451514555', '884554545', 'No', 'Yes', 'Yes', NULL, 0, NULL, NULL, NULL, NULL, 'No', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-03 10:12:25', '2025-05-03 10:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_residentcop`
--

CREATE TABLE `tbl_residentcop` (
  `resident_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `precint_number` varchar(50) DEFAULT NULL,
  `voter_status` enum('Active','Inactive','Not Registered') DEFAULT 'Not Registered',
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') DEFAULT 'Unknown',
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `type_of_id` varchar(100) DEFAULT NULL,
  `id_number` varchar(100) DEFAULT NULL,
  `barangay_number` varchar(20) DEFAULT NULL,
  `SSSGSIS_Number` varchar(20) NOT NULL,
  `TIN_number` varchar(15) NOT NULL,
  `is_household_head` tinyint(1) DEFAULT 0,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `is_senior_citizen` enum('Yes','No') DEFAULT 'No',
  `senior_document` varchar(255) DEFAULT NULL,
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency_document` varchar(255) DEFAULT NULL,
  `is_4ps_member` enum('Yes','No') DEFAULT 'No',
  `residency_tenure` enum('Temporary','Permanent') DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_residentcop`
--

INSERT INTO `tbl_residentcop` (`resident_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `birthplace`, `gender`, `marital_status`, `phone_number`, `address`, `precint_number`, `voter_status`, `blood_type`, `height`, `weight`, `type_of_id`, `id_number`, `barangay_number`, `SSSGSIS_Number`, `TIN_number`, `is_household_head`, `household_head_name`, `relationship_to_head`, `is_senior_citizen`, `senior_document`, `is_pwd`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency_document`, `is_4ps_member`, `residency_tenure`, `occupation`, `email`, `image`, `created_at`, `updated_at`) VALUES
(5, 1, 'Nico', 'fghjkl', 'Robin', 'fuygjkh', '2007-01-17', 'Sampaloc City', 'Female', 'Single', '89465132879', '234567654321ewrsdgfhjk', '342sfsdfs', 'Not Registered', 'B+', 234.00, 32.00, 'UMID', '24354678', '324543245324543543', '423578oi87654', '234567897654', 0, '', '', 'Yes', NULL, 'No', '', 'No', NULL, '', 'No', '', 'N/A', 'panis.kathleennicole@ue.edu.ph', NULL, '2025-04-29 17:23:14', '2025-05-02 14:22:17'),
(8, 32407, 'Nami', '', 'Ocean', '', '2025-05-01', '', 'Female', 'Single', '01234567899', 'East Blue, Sampaloc Manila', NULL, 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, '', '', 0, '', '', 'No', '', 'No', '', 'No', '', '', 'No', NULL, 'N/A', 'storagekath@gmail.com', NULL, '2025-05-02 14:38:45', '2025-05-02 14:38:45'),
(9, 2, 'Ace', 'D', 'Portgas', '', '2025-05-01', '', 'Male', 'Single', '09999999999', '42356ryhgfasdfghjgf', NULL, 'Not Registered', 'Unknown', NULL, NULL, NULL, NULL, NULL, '', '', 0, '', '', 'No', '', 'No', '', 'No', '', '', 'No', NULL, 'N/A', 'nicolepanis421@gmail.com', NULL, '2025-05-02 20:50:06', '2025-05-02 20:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `account_status` enum('Pending','Verified','Inactive') DEFAULT 'Pending',
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `is_logged_in` tinyint(1) DEFAULT 0,
  `is_logged_in_time` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `terms` tinyint(1) DEFAULT 0,
  `suffix` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `is_household_head` enum('Yes','No') NOT NULL,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `is_senior_citizen` enum('Yes','No') DEFAULT 'No',
  `senior_document` varchar(255) DEFAULT NULL,
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT 'N/A',
  `birthdate` date NOT NULL DEFAULT '2000-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `password`, `image`, `email`, `phone_number`, `address`, `account_status`, `role`, `is_logged_in`, `is_logged_in_time`, `remember_token`, `reset_token`, `reset_token_expiry`, `terms`, `suffix`, `gender`, `marital_status`, `is_household_head`, `household_head_name`, `relationship_to_head`, `is_senior_citizen`, `senior_document`, `is_pwd`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency`, `occupation`, `birthdate`) VALUES
(1, '1', 'Thomas', 'hey', 'Esteban', '$2y$10$xnOHjZKdjfUHEIvOs6sUBOPv/wUStSaION3CKxhhY72u8BE/heUWy', '67ec160d6cefc_face1.jpg', 'thomasesteban@gmail.com', '09123456789', 'Makati', 'Verified', 'admin', 1, '2025-05-03 18:45:27', 'ce70dd33eaef62c29fd1c40257e0989e', NULL, NULL, 0, NULL, 'Male', 'Single', 'Yes', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(2, '2', 'Johns', 'J.s', 'Does', '$2y$10$xnLXYJgt3exUrnUy00ZCnOtl9Dk.GtbP0F5GeyXnRKcAa2i1nWgly', '67ec05e7332b6_face21.jpg', 'jared@gmail.com', '0981026533', 'Makati', 'Verified', 'barangay_official', 0, NULL, '735128857e8a5fa5d5b6fb7ebfa5abd2', NULL, NULL, 0, NULL, 'Male', 'Single', 'Yes', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(3, '3', 'Chris', 'J.', 'Doe', '$2y$10$xnLXYJgt3exUrnUy00ZCnOtl9Dk.GtbP0F5GeyXnRKcAa2i1nWgly', '67ec001e376d8_face12.jpg', 'chrisdoe@gmail.com', '09810265312', 'Makati', 'Inactive', 'resident', 0, NULL, '443284fbc33a6392542a5bbbfd502e5f', NULL, NULL, 0, NULL, 'Male', 'Single', 'Yes', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01'),
(4, '4', 'Chris', 'Medina', 'Chua', '$2y$10$K1atoby5dFpn4YpuPip1KObuYN3ySDPmR.bcSmIwO0C.5AYzML62G', '67fc10d77d641_face16.jpg', 'chrischua@gmail.com', '09318689501', 'Makati', 'Verified', 'barangay_official', 1, '2025-04-26 14:06:27', 'f879bd159e289ed2c6763b58c3d0e9f4', NULL, NULL, 0, NULL, 'Male', 'Single', 'Yes', NULL, NULL, 'No', NULL, 'No', NULL, 'No', NULL, NULL, 'N/A', '2000-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_cop`
--

CREATE TABLE `tbl_user_cop` (
  `userID` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced','Separated') NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `is_household_head` enum('Yes','No') NOT NULL,
  `household_head_name` varchar(255) DEFAULT NULL,
  `relationship_to_head` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `is_senior_citizen` enum('Yes','No') DEFAULT 'No',
  `senior_document` varchar(255) DEFAULT NULL,
  `is_pwd` enum('Yes','No') DEFAULT 'No',
  `pwd_document` varchar(255) DEFAULT NULL,
  `is_registered_voter` enum('Yes','No') DEFAULT 'No',
  `voter_document` varchar(255) DEFAULT NULL,
  `proof_of_residency` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT 'N/A',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `image` varchar(255) DEFAULT 'default.png',
  `account_status` enum('Pending','Verified','Inactive') DEFAULT 'Pending',
  `is_logged_in_time` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `terms` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_logged_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_cop`
--

INSERT INTO `tbl_user_cop` (`userID`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `gender`, `marital_status`, `phone_number`, `is_household_head`, `household_head_name`, `relationship_to_head`, `address`, `is_senior_citizen`, `senior_document`, `is_pwd`, `pwd_document`, `is_registered_voter`, `voter_document`, `proof_of_residency`, `occupation`, `email`, `password`, `role`, `image`, `account_status`, `is_logged_in_time`, `remember_token`, `reset_token`, `reset_token_expiry`, `terms`, `created_at`, `updated_at`, `is_logged_in`) VALUES
(2, 'admin_680fd53ebc708', 'Luffy', '', 'Monkey', NULL, '0000-00-00', 'Male', 'Single', '32456789876', 'Yes', NULL, NULL, 'sadfghjkljhgfdsadfghj', 'No', '', 'No', NULL, 'No', '', NULL, 'N/A', 'paniskathleen@gmail.com', '$2y$10$lgHWS4OOmSP5Y81JE1tRxuMSXpYBRwE2ZcMZy0KsTUfG6e56f484C', 'admin', 'default.png', 'Verified', '2025-05-03 04:50:49', NULL, 'cf3bd3fcb05e6e59010033145f7bef4eb01fbe62e5be9e67c9d0210c0859052e', '2025-04-29 05:38:43', 0, '2025-04-28 19:21:34', '2025-05-02 20:50:49', 1),
(47, '1', 'Nico', '', 'Robin', '', '2025-04-02', 'Female', 'Single', '89465132879', 'Yes', '', '', '234567654321ewrsdgfhjk', 'No', '', 'Yes', '', 'No', '', '', 'N/A', 'panis.kathleennicole@ue.edu.ph', '$2y$10$5z5EFjBJmOE2gdQwFZcw0.C3m6zpmSRUsrE5UvMivXIs6glB37H7W', 'Resident', NULL, 'Verified', NULL, NULL, NULL, NULL, 0, '2025-04-29 17:23:14', '2025-05-02 20:38:16', 0),
(61, '2D352E9CFC68D9DF07D414CB0A2C307A', 'Ace', 'D', 'Portgas', '', '2025-05-01', 'Male', 'Single', '09999999999', 'Yes', '', '', '42356ryhgfasdfghjgf', 'No', '', 'No', '', 'No', '', '', 'N/A', 'nicolepanis421@gmail.com', '$2y$10$95v6vQDgR8UIPJS9mzCDXuEW26pnScdgkAnt9cyrN2ezLUADB34um', 'Resident', NULL, 'Pending', NULL, NULL, NULL, NULL, 0, '2025-05-02 20:50:06', '2025-05-02 20:50:06', 0);

-- --------------------------------------------------------

--
-- Table structure for table `web_officials`
--

CREATE TABLE `web_officials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `committee` enum('Barangay Council','Barangay Committees','Sangguniang Kabataan') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `web_officials`
--

INSERT INTO `web_officials` (`id`, `name`, `position`, `committee`, `email`, `image`, `created_at`) VALUES
(5, 'asdsad', 'SK Secretary', 'Sangguniang Kabataan', 'sadasdasda@afadf', 'p.jpg', '2025-04-25 22:53:34'),
(6, 'Felix C. Taguba', 'SK Kagawad', 'Sangguniang Kabataan', 'FelixCTaguba@gmai.com', 'p.jpg', '2025-04-25 23:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `web_services`
--

CREATE TABLE `web_services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(100) NOT NULL DEFAULT 'Barangay Certificate',
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `web_services`
--

INSERT INTO `web_services` (`id`, `title`, `description`, `requirements`, `created_at`, `category`, `file`) VALUES
(12, 'Good Moral Character', 'The Certificate of Good Moral Character is an official document issued by the barangay that certifies an individual’s adherence to ethical standards and responsible behavior within the community.\r\n\r\nThe certificate serves as proof that the applicant has no record of involvement in any unlawful activities and has demonstrated respect for the law, fellow residents, and the values upheld by the barangay.\r\n\r\nResidents may request a Certificate of Good Moral Character by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Inhabitants Form, Letter from the Admin, Valid ID, Log Sheet', '2025-04-25 19:53:08', 'Barangay Certificate', '680be824e7292_icon1.png'),
(13, 'First-time Job Seeker', 'The First-Time Job Seeker Certification is an official document issued by the barangay to certify that an applicant is seeking employment for the first time.\r\n\r\nIt is in accordance with Republic Act No. 11261, also known as the First-Time Jobseekers Assistance Act, which grants eligible individuals the opportunity to obtain government-issued pre-employment documents for free.\r\n\r\nApplicants may secure the First-Time Job Seeker Certification by visiting the barangay hall or create an Account and submitting the necessary documents.', 'Inhabitants Form,\r\nLetter from the Admin,\r\nOath of Undertaking,\r\nValid ID\r\nLog Sheet', '2025-04-25 20:10:07', 'Barangay Certificate', '680bec1f14b7c_icon2.png'),
(14, 'Calamity', 'The Calamity Certification is an official document issued by the barangay to certify that a resident, or property has been affected by a natural disaster or calamity. This certification serves as proof that the individual or family has suffered damage or loss due to the calamity and is often required to avail of financial assistance, insurance claims, or relief support from government agencies and non-governmental organizations.\r\n\r\nResidents may request a Certificate of Good Moral Character by visiting the barangay hall or create an Account and submitting with the necessary requirements.', 'Inhabitants Form, Valid ID, Log Sheet', '2025-04-25 21:02:04', 'Barangay Certificate', '680bf84c13dd9_icon3.png'),
(15, 'sampl', 'Hey!', 'sdasdasd', '2025-04-25 21:33:01', 'Barangay Clearances and Services', '680bff8dc5c17_b4.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_audit`
--
ALTER TABLE `tbl_audit`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_banned_users`
--
ALTER TABLE `tbl_banned_users`
  ADD PRIMARY KEY (`ban_id`);

--
-- Indexes for table `tbl_bid`
--
ALTER TABLE `tbl_bid`
  ADD PRIMARY KEY (`BID_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_blotter`
--
ALTER TABLE `tbl_blotter`
  ADD PRIMARY KEY (`blotter_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_brgyofficer`
--
ALTER TABLE `tbl_brgyofficer`
  ADD PRIMARY KEY (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  ADD PRIMARY KEY (`certification_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_clearance`
--
ALTER TABLE `tbl_clearance`
  ADD PRIMARY KEY (`clearance_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_compgriev`
--
ALTER TABLE `tbl_compgriev`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_event_comments`
--
ALTER TABLE `tbl_event_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `res_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `brgyOfficer_id` (`brgyOfficer_id`);

--
-- Indexes for table `tbl_generated_documents`
--
ALTER TABLE `tbl_generated_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_resident`
--
ALTER TABLE `tbl_resident`
  ADD PRIMARY KEY (`resident_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_residentcop`
--
ALTER TABLE `tbl_residentcop`
  ADD PRIMARY KEY (`resident_id`),
  ADD KEY `tbl_resident_ibfk_1` (`user_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_cop`
--
ALTER TABLE `tbl_user_cop`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `web_officials`
--
ALTER TABLE `web_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_services`
--
ALTER TABLE `web_services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_audit`
--
ALTER TABLE `tbl_audit`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;

--
-- AUTO_INCREMENT for table `tbl_banned_users`
--
ALTER TABLE `tbl_banned_users`
  MODIFY `ban_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_bid`
--
ALTER TABLE `tbl_bid`
  MODIFY `BID_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_blotter`
--
ALTER TABLE `tbl_blotter`
  MODIFY `blotter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_brgyofficer`
--
ALTER TABLE `tbl_brgyofficer`
  MODIFY `brgyOfficer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  MODIFY `certification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_clearance`
--
ALTER TABLE `tbl_clearance`
  MODIFY `clearance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_compgriev`
--
ALTER TABLE `tbl_compgriev`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_event_comments`
--
ALTER TABLE `tbl_event_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_generated_documents`
--
ALTER TABLE `tbl_generated_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `tbl_resident`
--
ALTER TABLE `tbl_resident`
  MODIFY `resident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_residentcop`
--
ALTER TABLE `tbl_residentcop`
  MODIFY `resident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_user_cop`
--
ALTER TABLE `tbl_user_cop`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `web_officials`
--
ALTER TABLE `web_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `web_services`
--
ALTER TABLE `web_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_audit`
--
ALTER TABLE `tbl_audit`
  ADD CONSTRAINT `tbl_audit_ibfk_2` FOREIGN KEY (`brgyOfficer_id`) REFERENCES `tbl_brgyofficer` (`brgyOfficer_id`);

--
-- Constraints for table `tbl_event_comments`
--
ALTER TABLE `tbl_event_comments`
  ADD CONSTRAINT `tbl_event_comments_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `tbl_event` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_generated_documents`
--
ALTER TABLE `tbl_generated_documents`
  ADD CONSTRAINT `tbl_generated_documents_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `tbl_residentcop` (`resident_id`),
  ADD CONSTRAINT `tbl_generated_documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_cop` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
