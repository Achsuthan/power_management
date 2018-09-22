-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 22, 2018 at 10:33 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `power_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` varchar(20) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `suport_device` varchar(100) NOT NULL,
  `limit_value` varchar(20) NOT NULL,
  `created_date` varchar(100) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `voltage` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `device_name`, `suport_device`, `limit_value`, `created_date`, `created_by`, `voltage`) VALUES
('DIV1111', 'AC', 'thermal sensor', '20', '2018-09-02 01:29:53', 'senthu', '20'),
('DIV1112', 'AC', 'thermal sensor', '20', '2018-09-02 01:32:46', 'senthu', '5'),
('DIV1113', 'AC', 'thermal sensor', '20', '2018-09-22 10:16:35', 'senthu', '10');

-- --------------------------------------------------------

--
-- Table structure for table `reading`
--

CREATE TABLE `reading` (
  `id` varchar(20) NOT NULL,
  `value` varchar(20) NOT NULL,
  `recorded_date` varchar(25) NOT NULL,
  `is_on` varchar(10) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `usage` varchar(100) NOT NULL,
  `device_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reading`
--

INSERT INTO `reading` (`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) VALUES
('RED1111', '21', '2018-09-02 01:30:19', '1', '0', '3434', 'DIV1111'),
('RED1112', '21', '2018-09-02 01:30:32', '1', '13', '121', 'DIV1111'),
('RED1113', '21', '2018-09-02 01:30:33', '1', '14', '112', 'DIV1111'),
('RED1114', '21', '2018-09-02 01:30:33', '1', '14', '12', 'DIV1111'),
('RED1115', '21', '2018-09-02 01:30:33', '1', '14', '2323', 'DIV1111'),
('RED1116', '21', '2018-09-02 01:30:51', '2', '32', '232', 'DIV1111'),
('RED1117', '10', '2018-09-02 01:31:05', '1', '32', '232', 'DIV1111'),
('RED1118', '10', '2018-09-02 01:31:06', '1', '32', '2323', 'DIV1111'),
('RED1119', '10', '2018-09-02 01:31:07', '1', '32', '2323', 'DIV1111'),
('RED1120', '24', '2018-09-02 01:31:19', '1', '44', '232', 'DIV1111'),
('RED1121', '24', '2018-09-02 01:33:04', '1', '0', '232', 'DIV1112'),
('RED1122', '24', '2018-09-02 01:33:04', '1', '0', '232', 'DIV1112'),
('RED1123', '24', '2018-09-02 01:33:05', '1', '1', '232', 'DIV1112'),
('RED1124', '24', '2018-09-02 01:33:05', '1', '1', '2323', 'DIV1112'),
('RED1125', '24', '2018-09-02 01:33:05', '1', '1', '2', 'DIV1112'),
('RED1126', '24', '2018-09-02 01:33:05', '1', '1', '32', 'DIV1112'),
('RED1127', '12', '2018-09-02 01:33:16', '1', '1', '232', 'DIV1112'),
('RED1128', '22', '2018-09-02 01:33:28', '1', '173', '232', 'DIV1111'),
('RED1129', '22', '2018-09-22 08:55:12', '1', '1754677', '232', 'DIV1111'),
('RED1130', '22', '2018-09-22 08:55:14', '1', '1754679', '32', 'DIV1111'),
('RED1131', '22', '2018-09-22 08:55:16', '1', '1754681', '23', 'DIV1111'),
('RED1132', '22', '2018-09-22 08:55:17', '1', '1754682', '2', 'DIV1111'),
('RED1133', '22', '2018-09-22 08:55:18', '1', '1754683', '23', 'DIV1111'),
('RED1134', '22', '2018-09-22 08:55:19', '1', '1754684', '6', 'DIV1111'),
('RED1135', '22', '2018-09-22 08:55:19', '1', '1754684', '5', 'DIV1111'),
('RED1136', '22', '2018-09-22 08:55:19', '1', '1754684', '7', 'DIV1111'),
('RED1137', '22', '2018-09-22 08:55:20', '1', '1754685', '1', 'DIV1111'),
('RED1138', '22', '2018-09-22 08:55:20', '1', '1754685', '4', 'DIV1111'),
('RED1139', '22', '2018-09-22 08:55:20', '1', '1754685', '3', 'DIV1111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reading`
--
ALTER TABLE `reading`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reading`
--
ALTER TABLE `reading`
  ADD CONSTRAINT `reading_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
