-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: lapis_plus_240728
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assessment_fees`
--

DROP TABLE IF EXISTS `assessment_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessment_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` char(8) DEFAULT NULL,
  `fee_id` char(3) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `adjust_amount` decimal(10,2) DEFAULT NULL,
  `percentage` decimal(7,6) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessment_fees`
--

LOCK TABLES `assessment_fees` WRITE;
/*!40000 ALTER TABLE `assessment_fees` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessment_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessment_payscheds`
--

DROP TABLE IF EXISTS `assessment_payscheds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessment_payscheds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type_id` char(5) DEFAULT NULL,
  `assessment_id` char(8) DEFAULT NULL,
  `bill_month` char(8) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `status` char(4) DEFAULT NULL COMMENT 'PAID,OVER,LATE',
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessment_payscheds`
--

LOCK TABLES `assessment_payscheds` WRITE;
/*!40000 ALTER TABLE `assessment_payscheds` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessment_payscheds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessment_subjects`
--

DROP TABLE IF EXISTS `assessment_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessment_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assessment_id` char(8) DEFAULT NULL,
  `subject_id` char(8) DEFAULT NULL,
  `section_id` int(4) DEFAULT NULL,
  `schedule_id` char(8) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessment_subjects`
--

LOCK TABLES `assessment_subjects` WRITE;
/*!40000 ALTER TABLE `assessment_subjects` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessment_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessments`
--

DROP TABLE IF EXISTS `assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assessments` (
  `id` char(8) NOT NULL,
  `student_id` char(8) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `account_type` char(10) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `ref_no` char(15) NOT NULL,
  `account_details` text DEFAULT NULL,
  `payment_scheme` char(5) DEFAULT NULL COMMENT 'CASH,INSTL',
  `assessment_total` decimal(10,2) DEFAULT NULL,
  `subsidy_status` char(5) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `payment_total` decimal(10,2) NOT NULL,
  `outstanding_balance` decimal(10,2) DEFAULT NULL,
  `module_balance` decimal(10,2) DEFAULT NULL,
  `rounding_off` decimal(6,5) DEFAULT NULL,
  `first` decimal(10,2) DEFAULT NULL,
  `second` decimal(10,2) DEFAULT NULL,
  `misc` decimal(10,2) DEFAULT NULL,
  `status` char(5) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessments`
--

LOCK TABLES `assessments` WRITE;
/*!40000 ALTER TABLE `assessments` DISABLE KEYS */;
/*!40000 ALTER TABLE `assessments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inquiries` (
  `id` char(8) NOT NULL,
  `source` varchar(50) DEFAULT NULL,
  `first_name` char(50) DEFAULT NULL,
  `middle_name` char(30) DEFAULT NULL,
  `last_name` char(30) DEFAULT NULL,
  `preffix` char(5) DEFAULT NULL,
  `suffix` char(5) DEFAULT NULL,
  `lrn` varchar(20) DEFAULT NULL,
  `student_type` char(3) DEFAULT NULL,
  `entry_status` char(3) DEFAULT NULL,
  `entry_sy` int(4) DEFAULT NULL,
  `entry_period` int(1) DEFAULT NULL,
  `department_id` char(2) DEFAULT NULL,
  `year_level_id` char(2) DEFAULT NULL,
  `program_id` char(5) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `mobile` char(11) DEFAULT NULL,
  `landline` varchar(10) DEFAULT NULL,
  `prev_school` varchar(50) DEFAULT NULL,
  `prev_school_type` char(3) DEFAULT NULL,
  `prev_school_address` varchar(150) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `birthplace` varchar(50) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `civil_status` varchar(15) DEFAULT NULL,
  `country` varchar(15) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `barangay` varchar(30) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `c_country` varchar(15) DEFAULT NULL,
  `c_province` varchar(20) DEFAULT NULL,
  `c_city` varchar(20) DEFAULT NULL,
  `c_barangay` varchar(30) DEFAULT NULL,
  `c_address` varchar(50) DEFAULT NULL,
  `g_first_name` varchar(30) DEFAULT NULL,
  `g_middle_name` varchar(30) DEFAULT NULL,
  `g_last_name` varchar(30) DEFAULT NULL,
  `g_suffix` varchar(5) DEFAULT NULL,
  `g_contact_no` varchar(11) DEFAULT NULL,
  `g_occupation` varchar(50) DEFAULT NULL,
  `g_rel` varchar(15) DEFAULT NULL,
  `f_first_name` varchar(30) DEFAULT NULL,
  `f_middle_name` varchar(30) DEFAULT NULL,
  `f_last_name` varchar(30) DEFAULT NULL,
  `f_suffix` varchar(5) DEFAULT NULL,
  `f_mobile` varchar(11) DEFAULT NULL,
  `f_occupation` varchar(50) DEFAULT NULL,
  `m_first_name` varchar(30) DEFAULT NULL,
  `m_middle_name` varchar(30) DEFAULT NULL,
  `m_last_name` varchar(30) DEFAULT NULL,
  `m_suffix` varchar(5) DEFAULT NULL,
  `m_mobile` varchar(11) DEFAULT NULL,
  `m_occupation` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquiries`
--

LOCK TABLES `inquiries` WRITE;
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'lapis_plus_240728'
--
