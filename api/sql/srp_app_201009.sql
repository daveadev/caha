-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 09, 2020 at 04:51 AM
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
-- Database: `srp_app_201009`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` char(15) NOT NULL,
  `account_type` char(10) DEFAULT NULL,
  `student_id` char(8) NOT NULL,
  `account_details` text,
  `payment_scheme` char(4) DEFAULT NULL COMMENT 'CASH,INSTL',
  `assessment_total` decimal(10,2) DEFAULT NULL,
  `outstanding_balance` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `rounding_off` decimal(6,5) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_type`, `student_id`, `account_details`, `payment_scheme`, `assessment_total`, `outstanding_balance`, `discount_amount`, `rounding_off`, `created`, `modified`) VALUES
('AF2000001', 'student', 'LSJ10231', NULL, 'INST', '15000.00', '7000.00', '500.00', '0.00000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_adjustments`
--

CREATE TABLE `account_adjustments` (
  `id` int(11) NOT NULL,
  `account_id` char(15) DEFAULT NULL,
  `item_code` char(4) DEFAULT NULL,
  `details` varchar(50) DEFAULT NULL,
  `adjust_date` date DEFAULT NULL,
  `flag` char(1) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_adjustments`
--

INSERT INTO `account_adjustments` (`id`, `account_id`, `item_code`, `details`, `adjust_date`, `flag`, `amount`, `created`, `modified`) VALUES
(10001, 'AF2000001', 'AJCR', 'Test Credit', '2020-10-09', '+', '10.00', NULL, NULL),
(10002, 'AF2000001', 'AJDB', 'Test Debit', '2020-10-09', '-', '10.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_fees`
--

CREATE TABLE `account_fees` (
  `id` int(11) NOT NULL,
  `account_id` char(15) DEFAULT NULL,
  `fee_id` char(3) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `adjust_amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_fees`
--

INSERT INTO `account_fees` (`id`, `account_id`, `fee_id`, `due_amount`, `paid_amount`, `adjust_amount`, `created`, `modified`) VALUES
(1, 'AF2000001', 'TUI', '15000.00', '8000.00', '0.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_histories`
--

CREATE TABLE `account_histories` (
  `id` int(11) NOT NULL,
  `account_id` char(15) DEFAULT NULL,
  `transac_date` date DEFAULT NULL,
  `transac_time` time DEFAULT NULL,
  `ref_no` char(10) DEFAULT NULL,
  `details` varchar(50) DEFAULT NULL,
  `flag` char(1) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_histories`
--

INSERT INTO `account_histories` (`id`, `account_id`, `transac_date`, `transac_time`, `ref_no`, `details`, `flag`, `amount`, `created`, `modified`) VALUES
(1, 'AF2000001', '2020-10-09', '01:23:45', 'AF2000001', 'ESCDSC', '-', '500.00', NULL, NULL),
(2, 'AF2000001', '2020-10-09', '01:23:45', 'OR12345', 'INIPY', '-', '7500.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_schedules`
--

CREATE TABLE `account_schedules` (
  `id` int(11) NOT NULL,
  `account_id` char(15) DEFAULT NULL,
  `bill_month` char(8) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `status` char(4) DEFAULT NULL COMMENT 'PAID,OVER,LATE',
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_schedules`
--

INSERT INTO `account_schedules` (`id`, `account_id`, `bill_month`, `due_amount`, `paid_amount`, `due_date`, `paid_date`, `status`, `order`, `created`, `modified`) VALUES
(1, 'AF2000001', 'UPONNROL', '7500.00', '7500.00', '2020-10-10 00:00:00', '2020-10-09 00:00:00', 'PAID', 1, NULL, NULL),
(2, 'AF2000001', 'NOV2020', '3500.00', NULL, '2020-11-10 00:00:00', NULL, 'OPEN', 2, NULL, NULL),
(3, 'AF2000001', 'DEC2020', '3500.00', NULL, '2020-12-10 00:00:00', NULL, 'OPEN', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` int(11) NOT NULL,
  `account_id` char(15) DEFAULT NULL,
  `transaction_type_id` char(5) DEFAULT NULL COMMENT 'INIPY,SBQPY',
  `ref_no` char(10) DEFAULT NULL,
  `breakdown_codes` varchar(30) NOT NULL,
  `breakdown_amounts` text NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `source` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_transactions`
--

INSERT INTO `account_transactions` (`id`, `account_id`, `transaction_type_id`, `ref_no`, `breakdown_codes`, `breakdown_amounts`, `amount`, `source`, `created`, `modified`) VALUES
(1, 'AF2000001', 'INIPY', 'OR12345', 'TUI', '7500', '7500.00', 'cashier', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booklets`
--

CREATE TABLE `booklets` (
  `id` int(11) NOT NULL,
  `cashier_id` char(8) DEFAULT NULL,
  `series_start` int(11) DEFAULT NULL,
  `series_end` int(11) DEFAULT NULL,
  `series_counter` int(11) DEFAULT NULL,
  `status` char(10) DEFAULT NULL COMMENT 'ISSUED,ACTIVE,USED',
  `receipt_type` char(2) DEFAULT NULL COMMENT 'OR,AR,OA',
  `cashier` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booklets`
--

INSERT INTO `booklets` (`id`, `cashier_id`, `series_start`, `series_end`, `series_counter`, `status`, `receipt_type`, `cashier`, `created`, `modified`) VALUES
(1, 'LSS12345', 12345, 1299, 12345, 'ACTIVE', 'OR', 'dave', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` char(8) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_no` varchar(10) DEFAULT NULL,
  `employee_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `user_id`, `employee_no`, `employee_name`) VALUES
('LSF12345', 1, '12345', 'Dave Arroyo');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` char(3) NOT NULL COMMENT 'TUI,REG,MSC',
  `name` varchar(20) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `name`, `order`, `created`, `modified`) VALUES
('TUI', 'Tuition', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ledgers`
--

CREATE TABLE `ledgers` (
  `id` int(11) NOT NULL,
  `account_id` char(15) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `transac_date` date DEFAULT NULL,
  `transac_time` time DEFAULT NULL,
  `ref_no` char(10) DEFAULT NULL,
  `details` text,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ledgers`
--

INSERT INTO `ledgers` (`id`, `account_id`, `type`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `created`) VALUES
(1, 'AF2000001', '+', '2020.00', '2020-10-09', '01:23:45', 'AF2000001', 'Tuition Fee', '15000.00', NULL),
(2, 'AF2000001', '-', '2020.00', '2020-10-09', '01:23:45', 'AF2000001', 'ESC Discount', '500.00', NULL),
(3, 'AF2000001', '-', '2020.00', '2020-10-10', '01:23:45', 'OR12345', 'Initial Payment', '7500.00', NULL),
(4, 'AF2000001', '+', '2020.00', '2020-10-10', '01:23:45', 'AJ10001', 'Test Credit', '10.00', NULL),
(5, 'AF2000001', '-', '2020.00', '2020-10-10', '01:23:56', 'AJ10002', 'Test Debit', '10.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_configs`
--

CREATE TABLE `master_configs` (
  `id` int(11) NOT NULL,
  `sys_key` char(20) DEFAULT NULL,
  `sys_value` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_configs`
--

INSERT INTO `master_configs` (`id`, `sys_key`, `sys_value`, `created`, `modified`) VALUES
(1, 'SCHOOL_NAME', 'LAKESHORE', '2018-06-21 06:21:23', '2018-06-21 06:21:23'),
(2, 'SCHOOL_ALIAS', 'LS', '2018-06-21 06:21:36', '2018-06-21 06:21:36'),
(3, 'SCHOOL_LOGO', 'logo.jpg', '2018-06-21 06:21:51', '2018-06-21 06:21:51'),
(4, 'SCHOOL_ADDRESS', 'BINAN, LAGUNA', '2018-06-21 06:22:05', '2018-06-21 06:22:05'),
(5, 'START_SY', '2020', '2018-06-21 06:22:18', '2020-08-24 11:27:35'),
(6, 'ACTIVE_SY', '2020', '2018-06-21 06:22:28', '2020-09-17 16:09:41'),
(7, 'DEFAULT_', '{\r\n   \"PRECISION\":0,\r\n   \"FLOOR_GRADE\":70,\r\n   \"CEIL_GRADE\":100,\r\n   \"PERIOD\":{\r\n      \"id\":1,\r\n      \"key\":\"FRSTGRDG\",\r\n      \"alias\":{\r\n         \"short\":\"MID\",\r\n         \"full\":\"Midterm\",\r\n	 \"desc\":\"1ST\"\r\n      }\r\n   },\r\n   \"SEMESTER\":{\r\n      \"id\":25,\r\n      \"key\":\"FRSTSEMS\",\r\n      \"alias\":{\r\n         \"short\":\"1st\",\r\n         \"full\":\"First Sem\"\r\n      }\r\n   }\r\n}', NULL, NULL),
(8, 'SCHOOL_ID', 'DU', NULL, NULL),
(9, 'ACTIVE_DEPT', 'SH', NULL, NULL),
(10, 'SCHOOL_COLOR', '{\"r\":\"100\",\"g\":\"0\",\"b\":\"0\"}', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` char(4) DEFAULT NULL COMMENT 'CASH,CHCK,CARD,CHRG',
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `icon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `description`, `icon`) VALUES
('CASH', 'Cash', 'Payment thru cash', NULL),
('CHCK', 'Check', 'Payment thru check', 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `type` char(15) DEFAULT NULL,
  `status` char(15) DEFAULT NULL,
  `ref_no` char(10) DEFAULT NULL,
  `transac_date` date DEFAULT NULL,
  `transac_time` time DEFAULT NULL,
  `account_id` char(15) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `status`, `ref_no`, `transac_date`, `transac_time`, `account_id`, `created`, `modified`) VALUES
(10000, 'payment', 'fulfilled', 'OR12345', '2020-10-09', '01:23:45', 'AF2000001', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `transaction_type_id` char(5) DEFAULT NULL COMMENT 'INIPY,SBQPY',
  `details` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `transaction_type_id`, `details`, `amount`) VALUES
(1, 10000, 'INIPY', NULL, '7500.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_payments`
--

CREATE TABLE `transaction_payments` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `payment_method_id` char(4) DEFAULT NULL COMMENT 'CASH,CHCK,CARD,CHCK',
  `details` varchar(50) DEFAULT NULL,
  `valid_on` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_payments`
--

INSERT INTO `transaction_payments` (`id`, `transaction_id`, `payment_method_id`, `details`, `valid_on`, `amount`) VALUES
(1, 10000, 'CASH', 'cash', NULL, '500.00'),
(2, 10000, 'CHCK', 'BDO-12345X', '2020-10-09', '7000.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_types`
--

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
('INIPY', 'Initial Payment', 'active', '0.00', 0, 1, 1, 0, 1, 0, NULL, NULL),
('OLDAC', 'Old Account', 'passive', '0.00', 0, 1, 1, 0, 0, 1, NULL, NULL),
('SBQPY', 'Subsequent Payment', 'reactive', '0.00', 0, 1, 1, 0, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type_id` char(5) DEFAULT NULL,
  `username` varchar(15) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` char(5) DEFAULT NULL,
  `login_failed` int(11) DEFAULT NULL,
  `ip_failed` varchar(20) DEFAULT NULL,
  `login_success` int(11) DEFAULT NULL,
  `ip_success` varchar(20) DEFAULT NULL,
  `password_changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type_id`, `username`, `password`, `status`, `login_failed`, `ip_failed`, `login_success`, `ip_success`, `password_changed`, `created`, `modified`) VALUES
(1, 'cshr', 'admin', '846385b5749d96060e2a6da69fa592c3f77c5fe0', 'ACTIV', NULL, NULL, 3, '::1', NULL, NULL, '2020-10-09 10:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_grants`
--

CREATE TABLE `user_grants` (
  `id` int(11) NOT NULL,
  `user_type_id` char(5) DEFAULT NULL,
  `master_module_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` char(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_adjustments`
--
ALTER TABLE `account_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_fees`
--
ALTER TABLE `account_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_histories`
--
ALTER TABLE `account_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_schedules`
--
ALTER TABLE `account_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booklets`
--
ALTER TABLE `booklets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_configs`
--
ALTER TABLE `master_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_payments`
--
ALTER TABLE `transaction_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_types`
--
ALTER TABLE `transaction_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_grants`
--
ALTER TABLE `user_grants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_grn_usr` (`user_type_id`),
  ADD KEY `FK_grn_mod` (`master_module_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_adjustments`
--
ALTER TABLE `account_adjustments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10003;

--
-- AUTO_INCREMENT for table `account_fees`
--
ALTER TABLE `account_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `account_histories`
--
ALTER TABLE `account_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `account_schedules`
--
ALTER TABLE `account_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booklets`
--
ALTER TABLE `booklets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `master_configs`
--
ALTER TABLE `master_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10001;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_payments`
--
ALTER TABLE `transaction_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_grants`
--
ALTER TABLE `user_grants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
