-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 03:18 PM
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
-- Database: `rias_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessments_id` int(11) NOT NULL,
  `schooluser_id` int(11) NOT NULL,
  `ass_version_number` int(11) NOT NULL,
  `ass_version_comments` varchar(1000) DEFAULT NULL,
  `ass_date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `ass_is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessments_id`, `schooluser_id`, `ass_version_number`, `ass_version_comments`, `ass_date_created`, `ass_is_archived`) VALUES
(1, 32, 1, 'Initial assessment for intern performance.', '2025-04-25 12:00:57', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assessment_contents`
--

CREATE TABLE `assessment_contents` (
  `assessment_content_id` int(11) NOT NULL,
  `assessments_id` int(11) NOT NULL,
  `ass_content_question` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment_contents`
--

INSERT INTO `assessment_contents` (`assessment_content_id`, `assessments_id`, `ass_content_question`) VALUES
(21, 1, 'The intern reports to the office with <b>regular punctuality</b>.'),
(22, 1, 'The intern submits reports on or before the deadline.'),
(23, 1, 'The intern enjoys a comfortable working relationship with the supervisor and colleagues.'),
(24, 1, 'The intern can confidently present recommendations, suggestions, and criticisms to the supervisor/peers.'),
(25, 1, 'The intern is comfortable in airing problems and difficulties to the supervisor.'),
(26, 1, 'The intern seeks to improve their skills by taking initiative to learn new paradigms and methodologies.'),
(27, 1, 'The intern makes productive use of the resources (e.g., terminals and/or workstations) assigned to them.'),
(28, 1, 'The intern realizes the significance of the tasks assigned to them in the light of the objectives of the entire project.'),
(29, 1, 'The intern demonstrates a sense of independence at work and can work with less supervision.'),
(30, 1, 'The intern can communicate ideas in oral or written form using concise statements and correct grammar.'),
(31, 1, 'The intern is capable of updating him or herself with new technology through self-study.'),
(32, 1, 'The intern regularly informs their supervisor of any deviation from the set project schedules.'),
(33, 1, 'The intern is capable of making appropriate decisions to problems they encounter in the fulfillment of their tasks.'),
(34, 1, 'The intern reports for work in the prescribed company dress code.'),
(35, 1, 'The intern comes to the office well-groomed and presentable.'),
(36, 1, 'The intern informs in advance their supervisor when they have to be absent or late.'),
(37, 1, 'The intern accepts miscellaneous jobs and tasks with the right attitude.'),
(38, 1, 'The intern is reliable and imbues a sense of responsibility in handling the tasks assigned to them.'),
(39, 1, 'The intern applies the virtues of integrity in all aspects of their work.'),
(40, 1, 'The trainee possesses the needed technical skills to efficiently and effectively perform their tasks in the following specific areas.');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_feedback`
--

CREATE TABLE `assessment_feedback` (
  `afeedback_content_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `afeedback_questions` text NOT NULL,
  `afeedback_yesno` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment_feedback`
--

INSERT INTO `assessment_feedback` (`afeedback_content_id`, `assessment_id`, `afeedback_questions`, `afeedback_yesno`) VALUES
(1, 1, 'Please enumerate here the task items as purported in the Intern’s Job Description for Criteria #20.', 0),
(2, 1, 'Since your previous evaluation, did the Intern’s over-all performance improve?', 1),
(3, 1, 'Why? (Provide justification & indicate intern’s strong and weak points)', 0),
(4, 1, 'Given the opportunity, would you be willing let the Intern continue to work in your company? (either through continuing their Intern2 with you or absorbtion as an employee of the company)', 1),
(5, 1, 'Why? (Provide justification)', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assessment_grades`
--

CREATE TABLE `assessment_grades` (
  `assessment_grade_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `assessment_content_id` int(11) NOT NULL,
  `assessment_grade` int(11) NOT NULL DEFAULT -2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessment_grades`
--

INSERT INTO `assessment_grades` (`assessment_grade_id`, `internship_id`, `assessment_content_id`, `assessment_grade`) VALUES
(1529, 6, 21, 3),
(1530, 6, 22, 3),
(1531, 6, 23, 3),
(1532, 6, 24, 3),
(1533, 6, 25, 3),
(1534, 6, 26, 3),
(1535, 6, 27, 3),
(1536, 6, 28, 3),
(1537, 6, 29, 3),
(1538, 6, 30, 3),
(1539, 6, 31, 3),
(1540, 6, 32, 3),
(1541, 6, 33, 3),
(1542, 6, 34, 3),
(1543, 6, 35, 3),
(1544, 6, 36, 3),
(1545, 6, 37, 3),
(1546, 6, 38, 3),
(1547, 6, 39, 3),
(1548, 6, 40, 3),
(1609, 2, 21, 5),
(1610, 2, 22, 5),
(1611, 2, 23, 5),
(1612, 2, 24, 4),
(1613, 2, 25, 4),
(1614, 2, 26, 4),
(1615, 2, 27, 3),
(1616, 2, 28, 3),
(1617, 2, 29, 3),
(1618, 2, 30, 2),
(1619, 2, 31, 2),
(1620, 2, 32, 2),
(1621, 2, 33, 1),
(1622, 2, 34, 1),
(1623, 2, 35, 1),
(1624, 2, 36, 0),
(1625, 2, 37, 0),
(1626, 2, 38, 0),
(1627, 2, 39, 1),
(1628, 2, 40, 1),
(1745, 4, 21, 5),
(1746, 4, 22, 4),
(1747, 4, 23, 4),
(1748, 4, 30, 4),
(1749, 4, 33, 4);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `company_date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_name` varchar(255) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_website` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `intern_allowance` float DEFAULT NULL,
  `partnership_status` tinyint(1) NOT NULL,
  `revenue_growth` float DEFAULT NULL,
  `profit_margins` float DEFAULT NULL,
  `roi` float DEFAULT NULL,
  `roa` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_date_added`, `company_name`, `company_email`, `company_website`, `company_address`, `intern_allowance`, `partnership_status`, `revenue_growth`, `profit_margins`, `roi`, `roa`) VALUES
(2, '2025-04-23 04:29:23', 'Compa Nyname', 'companyemail@gmail.com', 'companywebsite.com', 'Com St. Panya City, Ddress', NULL, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `company_id`) VALUES
(1, 'De Partmen Tname', 2),
(2, 'De Partmen Tname2', 2);

-- --------------------------------------------------------

--
-- Table structure for table `executive_directors`
--

CREATE TABLE `executive_directors` (
  `exd_id` int(11) NOT NULL,
  `schooluser_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `executive_directors`
--

INSERT INTO `executive_directors` (`exd_id`, `schooluser_id`, `school_id`) VALUES
(6, 9, 1),
(7, 10, 2),
(8, 11, 3),
(9, 12, 4),
(10, 13, 5);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `afeedback_content_id` int(11) NOT NULL,
  `feedback_answer` text DEFAULT NULL,
  `feedback_yesno` tinyint(1) DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `internship_id`, `afeedback_content_id`, `feedback_answer`, `feedback_yesno`) VALUES
(425, 6, 1, 'f', -1),
(426, 6, 3, ' ', -1),
(427, 6, 5, '', -1),
(443, 2, 1, 'ffffff', -1),
(444, 2, 2, NULL, 0),
(445, 2, 3, 'f', -1),
(446, 2, 4, NULL, 1),
(447, 2, 5, 'ffasd', -1),
(552, 4, 1, '', 0),
(553, 4, 2, NULL, 0),
(554, 4, 3, '                 fasdf', -1),
(555, 4, 5, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `interns`
--

CREATE TABLE `interns` (
  `intern_id` int(11) NOT NULL,
  `schooluser_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `intern_birthdate` date NOT NULL,
  `intern_gender` varchar(50) DEFAULT NULL,
  `intern_city` varchar(100) DEFAULT NULL,
  `intern_province_or_state` varchar(100) DEFAULT NULL,
  `intern_postal_code` varchar(100) DEFAULT NULL,
  `intern_country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interns`
--

INSERT INTO `interns` (`intern_id`, `schooluser_id`, `program_id`, `intern_birthdate`, `intern_gender`, `intern_city`, `intern_province_or_state`, `intern_postal_code`, `intern_country`) VALUES
(1, 27, 1, '2003-08-15', 'Male', 'Las Piñas', 'Metro Manila', '1740', 'Philippines'),
(3, 29, 1, '2004-03-10', 'Male', 'Pasig City', 'Metro Manila', '1600', 'Philippines'),
(4, 30, 3, '2003-05-01', 'Female', 'Makati City', 'Metro Manila', '0771', 'Philippines'),
(5, 31, 2, '2002-12-25', 'Male', 'Manila', 'Metro Manila', '1000', 'Philippines');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `internship_id` int(11) NOT NULL,
  `intern_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `schooluser_id` int(11) NOT NULL,
  `internship_year` varchar(10) NOT NULL,
  `internship_date_started` date NOT NULL,
  `internship_date_ended` date NOT NULL,
  `internship_job_role` varchar(255) NOT NULL,
  `batch` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internships`
--

INSERT INTO `internships` (`internship_id`, `intern_id`, `supervisor_id`, `schooluser_id`, `internship_year`, `internship_date_started`, `internship_date_ended`, `internship_job_role`, `batch`) VALUES
(2, 1, 1, 32, 'INTERN1', '2025-06-01', '2025-12-01', 'Intern - Development', '2025-0001'),
(4, 3, 1, 32, 'INTERN1', '2025-06-01', '2025-12-01', 'Intern - Design', '2025-0001'),
(5, 4, 2, 32, 'INTERN2', '2025-06-01', '2025-12-01', 'Intern - HR', '2025-0002'),
(6, 5, 1, 32, 'INTERN2', '2025-06-01', '2025-12-01', 'Intern - Finance', '2025-0002');

-- --------------------------------------------------------

--
-- Table structure for table `internship_grades`
--

CREATE TABLE `internship_grades` (
  `internshipgrade_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `supervisor_grade` int(11) DEFAULT NULL,
  `supervisor_date_graded` timestamp NULL DEFAULT NULL,
  `io_grade` int(11) DEFAULT NULL,
  `io_date_graded` timestamp NULL DEFAULT NULL,
  `total_grade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internship_grades`
--

INSERT INTO `internship_grades` (`internshipgrade_id`, `internship_id`, `supervisor_grade`, `supervisor_date_graded`, `io_grade`, `io_date_graded`, `total_grade`) VALUES
(3, 2, 68, '2025-04-26 11:11:00', NULL, NULL, NULL),
(4, 6, 80, '2025-04-26 12:00:38', NULL, NULL, NULL),
(5, 6, 80, '2025-04-26 12:00:50', NULL, NULL, NULL),
(6, 6, 80, '2025-04-26 12:06:30', NULL, NULL, NULL),
(7, 6, 80, '2025-04-26 12:07:09', NULL, NULL, NULL),
(8, 6, 80, '2025-04-26 12:07:45', NULL, NULL, NULL),
(9, 6, 80, '2025-04-26 12:08:17', NULL, NULL, NULL),
(10, 6, 80, '2025-04-26 12:08:48', NULL, NULL, NULL),
(11, 6, 80, '2025-04-26 12:08:57', NULL, NULL, NULL),
(12, 6, 80, '2025-04-26 12:17:46', NULL, NULL, NULL),
(13, 6, 80, '2025-04-26 12:18:36', NULL, NULL, NULL),
(14, 6, 80, '2025-04-26 12:20:40', NULL, NULL, NULL),
(15, 6, 80, '2025-04-26 12:21:19', NULL, NULL, NULL),
(16, 6, 80, '2025-04-26 12:21:48', NULL, NULL, NULL),
(17, 6, 80, '2025-04-26 12:44:05', NULL, NULL, NULL),
(18, 6, 80, '2025-04-26 12:44:46', NULL, NULL, NULL),
(19, 6, 80, '2025-04-26 12:47:08', NULL, NULL, NULL),
(20, 6, 80, '2025-04-26 12:49:07', NULL, NULL, NULL),
(21, 6, 80, '2025-04-26 12:50:06', NULL, NULL, NULL),
(22, 6, 80, '2025-04-26 12:51:59', NULL, NULL, NULL),
(23, 6, 80, '2025-04-26 12:59:47', NULL, NULL, NULL),
(24, 6, 80, '2025-04-26 13:03:53', NULL, NULL, NULL),
(25, 6, 80, '2025-04-26 13:08:18', NULL, NULL, NULL),
(26, 2, 68, '2025-04-26 13:41:52', NULL, NULL, NULL),
(27, 2, 68, '2025-04-26 13:44:53', NULL, NULL, NULL),
(28, 2, 68, '2025-04-26 13:47:54', NULL, NULL, NULL),
(29, 2, 68, '2025-04-26 13:48:08', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `otp_id` int(11) NOT NULL,
  `schooluser_id` int(11) DEFAULT NULL,
  `otp_code` varchar(10) NOT NULL,
  `otp_date_created` datetime DEFAULT current_timestamp(),
  `otp_date_expiry` datetime DEFAULT NULL,
  `otp_is_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`otp_id`, `schooluser_id`, `otp_code`, `otp_date_created`, `otp_date_expiry`, `otp_is_used`) VALUES
(62, 27, '347704', '2025-05-01 13:07:15', '2025-05-01 13:17:15', 1),
(63, 27, '344449', '2025-05-01 13:10:19', '2025-05-01 13:20:19', 1),
(64, 27, '583715', '2025-05-01 15:22:11', '2025-05-01 15:32:11', 1),
(65, 27, '174222', '2025-05-01 15:24:38', '2025-05-01 15:34:38', 1),
(66, 27, '200154', '2025-05-01 15:25:49', '2025-05-01 15:35:49', 1),
(67, 27, '435945', '2025-05-01 15:29:08', '2025-05-01 15:39:08', 1),
(68, 27, '548664', '2025-05-01 15:30:08', '2025-05-01 15:40:08', 1),
(69, 27, '889221', '2025-05-01 15:34:28', '2025-05-01 15:44:28', 1),
(70, 27, '431489', '2025-05-01 15:35:58', '2025-05-01 15:45:58', 1),
(71, 27, '473221', '2025-05-01 15:38:19', '2025-05-01 15:48:19', 1),
(72, 27, '998207', '2025-05-01 15:40:47', '2025-05-01 15:50:47', 1),
(73, 27, '217136', '2025-05-01 15:42:06', '2025-05-01 15:52:06', 1),
(74, 27, '274917', '2025-05-01 15:44:52', '2025-05-01 15:54:52', 1),
(75, 27, '420087', '2025-05-01 15:46:17', '2025-05-01 15:56:17', 1),
(76, 27, '338694', '2025-05-01 15:49:13', '2025-05-01 15:59:13', 1),
(77, 27, '871896', '2025-05-01 15:52:18', '2025-05-01 16:02:18', 1),
(78, 27, '186641', '2025-05-01 15:56:08', '2025-05-01 16:06:08', 1),
(79, 27, '470460', '2025-05-01 15:57:27', '2025-05-01 16:07:27', 1),
(80, 32, '844020', '2025-05-01 15:58:14', '2025-05-01 16:08:14', 0),
(81, 27, '371163', '2025-05-01 15:58:35', '2025-05-01 16:08:35', 1),
(82, 32, '792238', '2025-05-01 15:59:25', '2025-05-01 16:09:25', 1),
(83, 27, '330197', '2025-05-01 16:02:06', '2025-05-01 16:12:06', 1),
(84, 32, '972727', '2025-05-01 16:25:01', '2025-05-01 16:35:01', 1),
(85, 33, '458311', '2025-05-01 16:26:21', '2025-05-01 16:36:21', 1),
(86, 33, '234550', '2025-05-01 16:27:01', '2025-05-01 16:37:01', 1),
(87, 33, '279979', '2025-05-01 16:27:59', '2025-05-01 16:37:59', 1),
(88, 33, '867623', '2025-05-01 16:28:37', '2025-05-01 16:38:37', 1),
(89, 9, '304679', '2025-05-01 16:31:16', '2025-05-01 16:41:16', 1),
(90, 33, '133052', '2025-05-01 16:34:41', '2025-05-01 16:44:41', 1),
(91, 33, '414694', '2025-05-01 16:36:30', '2025-05-01 16:46:30', 0),
(92, 33, '843796', '2025-05-01 22:43:10', '2025-05-01 22:53:10', 1),
(93, 27, '419736', '2025-05-01 23:36:34', '2025-05-01 23:46:34', 0),
(94, 33, '299419', '2025-05-02 22:13:32', '2025-05-02 22:23:32', 1),
(95, 32, '381054', '2025-05-02 22:15:03', '2025-05-02 22:25:03', 1),
(96, 32, '303274', '2025-05-04 08:59:59', '2025-05-04 09:09:59', 0),
(97, 32, '446218', '2025-05-04 09:02:54', '2025-05-04 09:12:54', 1),
(98, 32, '864303', '2025-05-04 09:03:57', '2025-05-04 09:13:57', 0),
(99, 32, '261056', '2025-05-04 09:04:56', '2025-05-04 09:14:56', 1),
(100, 32, '325564', '2025-05-05 20:19:35', '2025-05-05 20:29:35', 1),
(101, 32, '429021', '2025-05-05 20:20:47', '2025-05-05 20:30:47', 1),
(102, 32, '893193', '2025-05-05 20:22:44', '2025-05-05 20:32:44', 1),
(103, 32, '307358', '2025-05-05 20:24:09', '2025-05-05 20:34:09', 0),
(104, 32, '524785', '2025-05-07 09:51:50', '2025-05-07 10:01:50', 1),
(105, 32, '359849', '2025-05-07 09:56:05', '2025-05-07 10:06:05', 0),
(106, 32, '373463', '2025-05-07 09:59:31', '2025-05-07 10:09:31', 1),
(107, 33, '328632', '2025-05-07 10:00:06', '2025-05-07 10:10:06', 1),
(108, 32, '225332', '2025-05-07 10:00:49', '2025-05-07 10:10:49', 0),
(109, 32, '683038', '2025-05-07 10:08:31', '2025-05-07 10:18:31', 1),
(110, 33, '938325', '2025-05-07 10:09:23', '2025-05-07 10:19:23', 1),
(111, 32, '596029', '2025-05-07 10:10:59', '2025-05-07 10:20:59', 1),
(112, 32, '182259', '2025-05-07 10:11:37', '2025-05-07 10:21:37', 1),
(113, 33, '182453', '2025-05-07 10:12:35', '2025-05-07 10:22:35', 1),
(114, 32, '131986', '2025-05-07 10:13:45', '2025-05-07 10:23:45', 1),
(115, 32, '169950', '2025-05-07 10:15:02', '2025-05-07 10:25:02', 0),
(116, 32, '340627', '2025-05-07 10:16:46', '2025-05-07 10:26:46', 1),
(117, 33, '664765', '2025-05-07 10:17:12', '2025-05-07 10:27:12', 1),
(118, 32, '599007', '2025-05-07 10:24:08', '2025-05-07 10:34:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `passwordresets`
--

CREATE TABLE `passwordresets` (
  `passreset_id` int(11) NOT NULL,
  `schooluser_id` int(11) DEFAULT NULL,
  `passreset_token` varchar(255) NOT NULL,
  `passreset_date_created` datetime DEFAULT current_timestamp(),
  `passreset_date_expiry` datetime DEFAULT NULL,
  `passreset_is_used` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passwordresets`
--

INSERT INTO `passwordresets` (`passreset_id`, `schooluser_id`, `passreset_token`, `passreset_date_created`, `passreset_date_expiry`, `passreset_is_used`) VALUES
(1, 27, 'c43b21a1e677efe089f133d76c738096', '2025-05-01 20:37:07', NULL, 0),
(2, 27, '1545bc9f4bd9c6f8d656920815c12d4e', '2025-05-01 20:38:32', NULL, 0),
(3, 33, '2e54ac2ffa0ee962abe54518211bb635', '2025-05-01 20:56:10', NULL, 0),
(4, 33, '9734ec11eae139bdbe31ee4184f9808f', '2025-05-01 21:30:48', '2025-05-01 21:40:48', 0),
(5, 33, 'dca30ea2c1c950c4ebb7e3e9cb69dfb1', '2025-05-01 21:44:10', '2025-05-01 21:54:10', 0),
(6, 33, '35205959f1232d34507f2cf16fe3e2a5', '2025-05-01 21:55:17', '2025-05-01 22:05:17', 0),
(7, 33, '1defd520fc5b74ad0194e361046bf251', '2025-05-01 22:05:51', '2025-05-01 22:15:51', 0),
(8, 33, 'b97032708c49c155e86856eec0532736', '2025-05-01 22:20:13', '2025-05-01 22:30:13', 1),
(9, 33, '64d9aac50a6a31540a64e57ea9b31e7a', '2025-05-01 22:20:48', '2025-05-01 22:30:48', 1),
(10, 33, '8c6b0f5b98e69bd407cde70b4bdc1e2d', '2025-05-01 22:33:07', '2025-05-01 22:43:07', 1),
(11, 33, '94b7ee2c2aab1bd12d562ac7dd1950f8', '2025-05-01 22:35:57', '2025-05-01 22:45:57', 1),
(12, 33, '1b504b7292780ddf74c354a1d660186c', '2025-05-01 22:38:56', '2025-05-01 22:48:56', 1),
(13, 33, '98e9c8e991b5e5209a2c9d91286d4a20', '2025-05-01 22:43:50', '2025-05-01 22:53:50', 0),
(14, 33, 'faff68bf140595d0540d76c75f84e343', '2025-05-01 22:46:02', '2025-05-01 22:56:02', 0),
(15, 33, 'd1561fd1633fa70a0d682e3ee6cd24d6', '2025-05-01 22:46:18', '2025-05-01 22:56:18', 0),
(16, 33, '23ce035fadeeeb9fe79e839a2140da2d', '2025-05-01 22:48:02', '2025-05-01 22:58:02', 0),
(17, 33, '966fc5aa0e86a221acc93a19d5cdc28c', '2025-05-01 22:48:21', '2025-05-01 22:58:21', 0),
(18, 33, 'c1b3493a90237b3ecfecfd9c05f36b03', '2025-05-01 23:02:41', '2025-05-01 23:12:41', 0),
(19, 33, '72ae6f7260e7ffa3c77d3c42cb2904c1', '2025-05-01 23:08:25', '2025-05-01 23:18:25', 0),
(20, 33, 'b5b6bd3ec0a695bb020292b7c833d7e5', '2025-05-01 23:13:48', '2025-05-01 23:23:48', 0),
(21, 33, '2e8e05b7efb3d632c93cd6a89c505034', '2025-05-01 23:14:47', '2025-05-01 23:24:47', 0),
(22, 33, '5a1523d2b3894420684b9351fc9f8347', '2025-05-01 23:18:32', '2025-05-01 23:28:32', 0),
(23, 33, 'bebfb6ce208099a801e2bf50f0be1a0c', '2025-05-01 23:21:15', '2025-05-01 23:31:15', 0),
(24, 33, 'e0f7740f3cdd624daa036230ab77187e', '2025-05-01 23:25:28', '2025-05-01 23:30:28', 0),
(25, 33, 'a4af56d247ce263bd5cac0fa85426371', '2025-05-01 23:27:53', '2025-05-01 23:32:53', 0),
(26, 33, '58fb709609c0b3819590e9a8f15faa3d', '2025-05-01 23:33:34', '2025-05-01 23:38:34', 0),
(27, 27, '55ee47ef0c18fb4aedac5482bcd9fbe9', '2025-05-02 20:14:20', '2025-05-02 20:19:20', 0),
(28, 27, '8e0cd3285439f5b2ff9f0f9a2bd8f0b5', '2025-05-02 20:17:45', '2025-05-02 20:22:45', 0),
(29, 27, 'c98cd18a7c73c10012c8d48a7e4b7805', '2025-05-02 20:23:29', '2025-05-02 20:28:29', 0),
(30, 27, '97b24d27170619259147713beb99a32e', '2025-05-02 20:28:43', '2025-05-02 20:33:43', 0),
(31, NULL, '9fa098c3b7fdae36b3357ed38fe7485d', '2025-05-28 08:12:27', '2025-05-28 08:27:27', 0),
(32, NULL, '3a5696b8aec308cbb35a692e15040420', '2025-05-28 08:24:35', '2025-05-28 08:39:35', 0),
(33, NULL, 'e46ab856cedb6f199df28ae371c12eb6', '2025-05-28 08:33:01', '2025-05-28 08:48:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(50) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_name`, `school_id`) VALUES
(1, 'BS Computer Science', 2),
(2, 'BS Information Technology', 2),
(3, 'Associate in Computer Technology', 2),
(4, 'AI Manifesto', 2),
(5, 'SoCIT Digi-X', 2),
(6, 'BS Civil Engineering', 3),
(7, 'BS Computer Engineering', 3),
(8, 'BS Electronics Engineering', 3),
(9, 'BS Accountancy', 4),
(10, 'BS Business Administration', 4),
(11, 'BS Tourism Management', 4),
(12, 'Bachelor of Multimedia Arts', 5),
(13, 'Bachelor of Arts in Psychology', 5),
(17, 'Architecture', 1);

-- --------------------------------------------------------

--
-- Table structure for table `program_directors`
--

CREATE TABLE `program_directors` (
  `programdirector_id` int(11) NOT NULL,
  `schooluser_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_directors`
--

INSERT INTO `program_directors` (`programdirector_id`, `schooluser_id`, `program_id`) VALUES
(5, 15, 2);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `school_id` int(11) NOT NULL,
  `school_name` varchar(50) NOT NULL,
  `school_acr` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`school_id`, `school_name`, `school_acr`) VALUES
(1, 'School of Architecture', 'SOA'),
(2, 'School of Computing and Information Technologies', 'SOCIT'),
(3, 'School of Engineering', 'SOE'),
(4, 'School of Management', 'SOM'),
(5, 'School of Multimedia and Arts', 'SOMA');

-- --------------------------------------------------------

--
-- Table structure for table `school_users`
--

CREATE TABLE `school_users` (
  `schooluser_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_given_id` varchar(50) NOT NULL,
  `schooluser_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_users`
--

INSERT INTO `school_users` (`schooluser_id`, `user_id`, `school_given_id`, `schooluser_password`) VALUES
(9, 1, 'EDARCH001', '$2y$12$i0vYrlR/D/HBhj46c53A8OtfHhMwJAMDs49rIFptodJEYhk88jbKe'),
(10, 9, 'EDCIT001', '$2y$12$i0vYrlR/D/HBhj46c53A8OtfHhMwJAMDs49rIFptodJEYhk88jbKe'),
(11, 5, 'EDENG001', '$2y$12$i0vYrlR/D/HBhj46c53A8OtfHhMwJAMDs49rIFptodJEYhk88jbKe'),
(12, 4, 'EDMGT001', '$2y$12$i0vYrlR/D/HBhj46c53A8OtfHhMwJAMDs49rIFptodJEYhk88jbKe'),
(13, 11, 'EDMMA001', '$2y$12$i0vYrlR/D/HBhj46c53A8OtfHhMwJAMDs49rIFptodJEYhk88jbKe'),
(15, 13, 'PDCIT001', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(19, 15, 'PDENG001', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(20, 12, 'PDENG002', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(22, 8, 'PDMGT001', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(23, 7, 'PDMGT002', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(24, 14, 'PDMGT003', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(25, 6, 'PDMMA001', '$2y$12$X/eCXGMPqdTDpdtFmW5hE.ANnn3i2/44g4OIfWiNuIBnlKExe/ZCK'),
(27, 16, 'SI2025-001', '$2y$12$ZSrLR4f7aVsWUlijwmkDkOhvmIE1Xp4w.HRVocQ9syDGg76Yo7XbW'),
(29, 17, 'SI2025-003', '$2y$12$ZSrLR4f7aVsWUlijwmkDkOhvmIE1Xp4w.HRVocQ9syDGg76Yo7XbW'),
(30, 18, 'SI2025-004', '$2y$12$ZSrLR4f7aVsWUlijwmkDkOhvmIE1Xp4w.HRVocQ9syDGg76Yo7XbW'),
(31, 19, 'SI2025-005', '$2y$12$ZSrLR4f7aVsWUlijwmkDkOhvmIE1Xp4w.HRVocQ9syDGg76Yo7XbW'),
(32, 20, 'IO2025-001', '$2y$12$aOtGBu.ZQtR2eELNgavXDuWGhg8p8Y66qJRu/e7sREurN4D7lJrM.'),
(33, 10, '1', '$2y$10$kgbOjzZpmnpDs6BNQGawK.rkoypx2373oMzjiq.oFZXiOCDYkPSo.');

-- --------------------------------------------------------

--
-- Table structure for table `send_assessments`
--

CREATE TABLE `send_assessments` (
  `sendassessment_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `sendass_date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `sendass_send_date` datetime NOT NULL,
  `sendass_token` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `send_assessments`
--

INSERT INTO `send_assessments` (`sendassessment_id`, `supervisor_id`, `sendass_date_created`, `sendass_send_date`, `sendass_token`) VALUES
(6, 1, '2025-04-23 15:08:19', '2025-06-01 10:00:00', 'placeholder_token_supervisor_1'),
(7, 2, '2025-04-23 15:08:19', '2025-06-01 11:00:00', 'placeholder_token_supervisor_2');

-- --------------------------------------------------------

--
-- Table structure for table `string_literals`
--

CREATE TABLE `string_literals` (
  `string_literal_id` int(11) NOT NULL,
  `string_name` varchar(255) DEFAULT NULL,
  `string_content` text DEFAULT NULL,
  `string_date_created` datetime DEFAULT current_timestamp(),
  `schooluser_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `supervisor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supervisor_job_role` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `supervisor_contact_no` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisors`
--

INSERT INTO `supervisors` (`supervisor_id`, `user_id`, `supervisor_job_role`, `department_id`, `supervisor_contact_no`) VALUES
(1, 2, 'Super Visorj Obrole', 1, '+1-766-909-3450'),
(2, 3, 'Super Visorj Obrole2', 2, '+1-698-556-5853');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_first_name` varchar(50) NOT NULL,
  `user_last_name` varchar(50) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_is_archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_first_name`, `user_last_name`, `user_email`, `user_date_created`, `user_date_updated`, `user_is_archived`) VALUES
(1, 'Harry Joseph', 'Serrano', 'harryjosephs@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(2, 'Indus Trysup2', 'Ervisor2', 'industrysupervisor2@company.com', '2025-04-23 14:32:36', '2025-04-23 14:32:36', 0),
(3, 'Indus Trysup', 'Ervisor', 'industrysupervisor@company.com', '2025-04-23 04:14:40', '2025-04-23 04:14:40', 0),
(4, 'Edwin', 'Loma', 'jedl@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(5, 'Leonardo', 'Samaniego Jr.', 'leonardojrs@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(6, 'Liza', 'Mapagu', 'lizam@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(7, 'Manuel', 'Magbuhat', 'manuelm@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(8, 'Osler', 'Aquino', 'oslera@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(9, 'Rhea-Luz', 'Valbuena', 'rhear@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(10, 'Admin', '', 'riasadmin@apc.edu.ph', '2025-04-28 12:36:41', '2025-04-28 12:36:41', 0),
(11, 'Robert Nelson', 'Besana', 'robertb@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(12, 'Ronaldo', 'Gallardo', 'ronaldog@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(13, 'Roselle Wednesday', 'Gardon', 'roselleg@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(14, 'Rosemarie Arhlene', 'Ampil', 'rosemariea@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(15, 'Sergio', 'Peruda Jr.', 'sergiop@apc.edu.ph', '2025-04-23 13:44:42', '2025-04-23 13:44:42', 0),
(16, 'Marques', 'Baltazar', 'studentintern1@student.apc.edu.ph', '2025-04-23 14:23:23', '2025-05-21 06:01:04', 0),
(17, 'Juan', 'Dela Cruz', 'studentintern3@student.apc.edu.ph', '2025-04-23 14:23:24', '2025-05-18 01:05:06', 0),
(18, 'Stud Entint4', 'Ern4', 'studentintern4@student.apc.edu.ph', '2025-04-23 14:23:24', '2025-05-18 01:14:21', 0),
(19, 'Stud Entint5', 'Ern5', 'studentintern5@student.apc.edu.ph', '2025-04-23 14:23:24', '2025-05-18 01:16:06', 0),
(20, 'Unica Althea', 'Iranta', 'unicai@apc.edu.ph', '2025-04-23 14:48:40', '2025-04-23 14:48:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_name`) VALUES
(1, 'Executive Director'),
(1, 'Program Director'),
(2, 'Industry Supervisor'),
(3, 'Industry Supervisor'),
(4, 'Executive Director'),
(5, 'Executive Director'),
(5, 'Program Director'),
(6, 'Program Director'),
(7, 'Program Director'),
(8, 'Program Director'),
(9, 'Executive Director'),
(9, 'Program Director'),
(10, 'Admin'),
(11, 'Executive Director'),
(11, 'Program Director'),
(12, 'Program Director'),
(13, 'Program Director'),
(14, 'Program Director'),
(15, 'Program Director'),
(16, 'Student Intern'),
(17, 'Student Intern'),
(18, 'Student Intern'),
(19, 'Student Intern'),
(20, 'Internship Officer');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`session_id`, `user_id`, `session_token`, `created_at`, `expires_at`) VALUES
(1, 35, '785c7bc5930ee8c2f4d78bf59227637b1a3f3477307a45fc09d721511996edc5', '2025-04-28 14:21:00', '2025-04-28 15:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_updates`
--

CREATE TABLE `user_updates` (
  `update_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `update_type` varchar(100) NOT NULL,
  `update_description` text DEFAULT NULL,
  `update_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_updates`
--

INSERT INTO `user_updates` (`update_id`, `user_id`, `update_type`, `update_description`, `update_timestamp`) VALUES
(7, 29, 'Manual Edit', 'Name Update', '2025-05-18 00:46:21'),
(8, 31, 'Manual Edit', 'Name updated', '2025-05-18 01:05:06'),
(10, 33, 'ARCHIVE', 'jus a prank bro XP', '2025-05-18 01:12:44'),
(11, 32, 'ARCHIVE', 'Student Died', '2025-05-18 01:14:21'),
(12, 33, 'ARCHIVE', 'hehe', '2025-05-18 01:16:06'),
(17, 29, 'Manual Edit', 'Name Update', '2025-05-20 08:22:45'),
(18, 16, 'Manual Edit', 'Name Update', '2025-05-21 06:01:04'),
(19, 32, 'ARCHIVE', 'hehe', '2025-05-21 06:08:17'),
(20, 41, 'ARCHIVE', 'fdsa', '2025-05-26 12:53:06'),
(21, 43, 'ARCHIVE', 'fdsa', '2025-05-26 14:29:18'),
(22, 44, 'ARCHIVE', 'f', '2025-05-27 23:53:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessments_id`),
  ADD KEY `assessments_ibfk_1` (`schooluser_id`);

--
-- Indexes for table `assessment_contents`
--
ALTER TABLE `assessment_contents`
  ADD PRIMARY KEY (`assessment_content_id`),
  ADD KEY `assessments_id` (`assessments_id`);

--
-- Indexes for table `assessment_feedback`
--
ALTER TABLE `assessment_feedback`
  ADD PRIMARY KEY (`afeedback_content_id`),
  ADD KEY `assessment_id` (`assessment_id`);

--
-- Indexes for table `assessment_grades`
--
ALTER TABLE `assessment_grades`
  ADD PRIMARY KEY (`assessment_grade_id`),
  ADD KEY `internship_id` (`internship_id`),
  ADD KEY `assessment_content_id` (`assessment_content_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `executive_directors`
--
ALTER TABLE `executive_directors`
  ADD PRIMARY KEY (`exd_id`),
  ADD KEY `schooluser_id` (`schooluser_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `afeedback_content_id` (`afeedback_content_id`),
  ADD KEY `feedback_ibfk_1` (`internship_id`);

--
-- Indexes for table `interns`
--
ALTER TABLE `interns`
  ADD PRIMARY KEY (`intern_id`),
  ADD KEY `schooluser_id` (`schooluser_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`internship_id`),
  ADD KEY `intern_id` (`intern_id`),
  ADD KEY `supervisor_id` (`supervisor_id`),
  ADD KEY `schooluser_id` (`schooluser_id`) USING BTREE;

--
-- Indexes for table `internship_grades`
--
ALTER TABLE `internship_grades`
  ADD PRIMARY KEY (`internshipgrade_id`),
  ADD KEY `internship_grades_ibfk_1` (`internship_id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `fk_otp_schooluser` (`schooluser_id`);

--
-- Indexes for table `passwordresets`
--
ALTER TABLE `passwordresets`
  ADD PRIMARY KEY (`passreset_id`),
  ADD KEY `fk_passreset_schooluser` (`schooluser_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `program_directors`
--
ALTER TABLE `program_directors`
  ADD PRIMARY KEY (`programdirector_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `program_directors_ibfk_1` (`schooluser_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `school_users`
--
ALTER TABLE `school_users`
  ADD PRIMARY KEY (`schooluser_id`),
  ADD UNIQUE KEY `school_given_id` (`school_given_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `send_assessments`
--
ALTER TABLE `send_assessments`
  ADD PRIMARY KEY (`sendassessment_id`),
  ADD UNIQUE KEY `sendass_token` (`sendass_token`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Indexes for table `string_literals`
--
ALTER TABLE `string_literals`
  ADD PRIMARY KEY (`string_literal_id`),
  ADD KEY `fk_schooluser` (`schooluser_id`);

--
-- Indexes for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD PRIMARY KEY (`supervisor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_name`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_sessions_ibfk_1` (`user_id`);

--
-- Indexes for table `user_updates`
--
ALTER TABLE `user_updates`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `user_updates_ibfk_1` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessments_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `assessment_contents`
--
ALTER TABLE `assessment_contents`
  MODIFY `assessment_content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `assessment_feedback`
--
ALTER TABLE `assessment_feedback`
  MODIFY `afeedback_content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assessment_grades`
--
ALTER TABLE `assessment_grades`
  MODIFY `assessment_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1750;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `executive_directors`
--
ALTER TABLE `executive_directors`
  MODIFY `exd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;

--
-- AUTO_INCREMENT for table `interns`
--
ALTER TABLE `interns`
  MODIFY `intern_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `internship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `internship_grades`
--
ALTER TABLE `internship_grades`
  MODIFY `internshipgrade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `otp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `passwordresets`
--
ALTER TABLE `passwordresets`
  MODIFY `passreset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `program_directors`
--
ALTER TABLE `program_directors`
  MODIFY `programdirector_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `school_users`
--
ALTER TABLE `school_users`
  MODIFY `schooluser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `send_assessments`
--
ALTER TABLE `send_assessments`
  MODIFY `sendassessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `string_literals`
--
ALTER TABLE `string_literals`
  MODIFY `string_literal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `supervisor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_updates`
--
ALTER TABLE `user_updates`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `assessment_contents`
--
ALTER TABLE `assessment_contents`
  ADD CONSTRAINT `assessment_contents_ibfk_1` FOREIGN KEY (`assessments_id`) REFERENCES `assessments` (`assessments_id`);

--
-- Constraints for table `assessment_feedback`
--
ALTER TABLE `assessment_feedback`
  ADD CONSTRAINT `assessment_feedback_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`assessments_id`);

--
-- Constraints for table `assessment_grades`
--
ALTER TABLE `assessment_grades`
  ADD CONSTRAINT `assessment_grades_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`internship_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assessment_grades_ibfk_2` FOREIGN KEY (`assessment_content_id`) REFERENCES `assessment_contents` (`assessment_content_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `executive_directors`
--
ALTER TABLE `executive_directors`
  ADD CONSTRAINT `executive_directors_ibfk_1` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`),
  ADD CONSTRAINT `executive_directors_ibfk_2` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`internship_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`afeedback_content_id`) REFERENCES `assessment_feedback` (`afeedback_content_id`);

--
-- Constraints for table `interns`
--
ALTER TABLE `interns`
  ADD CONSTRAINT `interns_ibfk_1` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interns_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`);

--
-- Constraints for table `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `internships_ibfk_1` FOREIGN KEY (`intern_id`) REFERENCES `interns` (`intern_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `internships_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`supervisor_id`),
  ADD CONSTRAINT `internships_ibfk_3` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `internship_grades`
--
ALTER TABLE `internship_grades`
  ADD CONSTRAINT `internship_grades_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`internship_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `otps`
--
ALTER TABLE `otps`
  ADD CONSTRAINT `fk_otp_schooluser` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `passwordresets`
--
ALTER TABLE `passwordresets`
  ADD CONSTRAINT `fk_passreset_schooluser` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`);

--
-- Constraints for table `program_directors`
--
ALTER TABLE `program_directors`
  ADD CONSTRAINT `program_directors_ibfk_1` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `program_directors_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`);

--
-- Constraints for table `school_users`
--
ALTER TABLE `school_users`
  ADD CONSTRAINT `school_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `send_assessments`
--
ALTER TABLE `send_assessments`
  ADD CONSTRAINT `send_assessments_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`supervisor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `string_literals`
--
ALTER TABLE `string_literals`
  ADD CONSTRAINT `fk_schooluser` FOREIGN KEY (`schooluser_id`) REFERENCES `school_users` (`schooluser_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD CONSTRAINT `supervisors_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
