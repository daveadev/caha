-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 23, 2020 at 07:50 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srp_app_201021`
--

-- --------------------------------------------------------

--
-- Table structure for table `transaction_types`
--

DROP TABLE IF EXISTS `transaction_types`;
CREATE TABLE `transaction_types` (
  `id` char(5) NOT NULL COMMENT 'INIPY,SBQPY',
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(15) NOT NULL,
  `default_amount` decimal(10,2) DEFAULT '0.00',
  `charge` tinyint(1) DEFAULT '0',
  `pay` tinyint(1) DEFAULT '0',
  `is_ledger` tinyint(1) DEFAULT '0',
  `is_fixed` tinyint(1) DEFAULT '0',
  `is_tuition` tinyint(1) DEFAULT '0',
  `is_show_always` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_types`
--

INSERT INTO `transaction_types` (`id`, `name`, `type`, `default_amount`, `charge`, `pay`, `is_ledger`, `is_fixed`, `is_tuition`, `is_show_always`, `created`, `modified`) VALUES
('DSESC', 'ESC', 'discount', '9000.00', 0, 0, 1, 0, 1, 0, NULL, NULL),
('DSPUB', 'PUBLIC', 'discount', '17500.00', 0, 0, 1, 0, 1, 0, NULL, NULL),
('DSQVR', 'QVR', 'discount', '14000.00', 0, 0, 1, 0, 1, 0, NULL, NULL),
('INIPY', 'Initial Payment', 'active', '0.00', 0, 1, 1, 0, 1, 0, NULL, NULL),
('OLDAC', 'Old Account', 'passive', '0.00', 0, 1, 1, 0, 0, 1, NULL, NULL),
('SBQPY', 'Subsequent Payment', 'reactive', '0.00', 0, 1, 1, 0, 1, 0, NULL, NULL),
('TUIXN', 'Tuition and Other Fees ', 'tuition', '0.00', 1, 0, 1, 0, 1, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
