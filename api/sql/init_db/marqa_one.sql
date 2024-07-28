-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: marqa_one_240728
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
-- Table structure for table `classlist_blocks`
--

DROP TABLE IF EXISTS `classlist_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classlist_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` char(8) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `status` char(5) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_esp` (`esp`) USING BTREE,
  KEY `idx_section_id` (`section_id`) USING BTREE,
  KEY `idx_student_id` (`student_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classlist_blocks`
--

LOCK TABLES `classlist_blocks` WRITE;
/*!40000 ALTER TABLE `classlist_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `classlist_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classlist_irregulars`
--

DROP TABLE IF EXISTS `classlist_irregulars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classlist_irregulars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` char(8) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `subject_id` char(8) DEFAULT NULL,
  `is_average` tinyint(4) DEFAULT 1,
  `esp` decimal(6,2) DEFAULT NULL,
  `status` char(5) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classlist_irregulars`
--

LOCK TABLES `classlist_irregulars` WRITE;
/*!40000 ALTER TABLE `classlist_irregulars` DISABLE KEYS */;
/*!40000 ALTER TABLE `classlist_irregulars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` char(2) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `alias` varchar(10) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guardians`
--

DROP TABLE IF EXISTS `guardians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guardians` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(8) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `rel` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guardians`
--

LOCK TABLES `guardians` WRITE;
/*!40000 ALTER TABLE `guardians` DISABLE KEYS */;
/*!40000 ALTER TABLE `guardians` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `household_members`
--

DROP TABLE IF EXISTS `household_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `household_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `household_id` int(11) DEFAULT NULL,
  `type` char(3) DEFAULT NULL,
  `entity_id` char(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_entity_id` (`entity_id`) USING BTREE,
  KEY `idx_household_id` (`household_id`) USING BTREE,
  KEY `idx_type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `household_members`
--

LOCK TABLES `household_members` WRITE;
/*!40000 ALTER TABLE `household_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `household_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `households`
--

DROP TABLE IF EXISTS `households`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `households` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `street` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `households`
--

LOCK TABLES `households` WRITE;
/*!40000 ALTER TABLE `households` DISABLE KEYS */;
/*!40000 ALTER TABLE `households` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_periods`
--

DROP TABLE IF EXISTS `master_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `master_periods` (
  `id` char(2) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `key` varchar(8) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_periods`
--

LOCK TABLES `master_periods` WRITE;
/*!40000 ALTER TABLE `master_periods` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_periods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programs` (
  `id` char(5) DEFAULT NULL,
  `department_id` char(2) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `track` varchar(100) DEFAULT NULL,
  `alias` varchar(10) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` char(2) DEFAULT NULL,
  `year_level_id` char(2) DEFAULT NULL,
  `program_id` char(5) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `alias` varchar(10) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_aux_fields`
--

DROP TABLE IF EXISTS `student_aux_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_aux_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` char(8) DEFAULT NULL,
  `aux_field` varchar(45) DEFAULT NULL,
  `aux_value` varchar(150) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_aux_fields`
--

LOCK TABLES `student_aux_fields` WRITE;
/*!40000 ALTER TABLE `student_aux_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_aux_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` char(8) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `classroom_user_id` varchar(50) DEFAULT NULL,
  `sno` varchar(20) DEFAULT NULL,
  `lrn` varchar(20) DEFAULT NULL,
  `rfid` char(10) DEFAULT NULL,
  `program_id` char(5) DEFAULT NULL,
  `year_level_id` char(2) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `age_bmi` decimal(3,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `height_m2` decimal(5,2) NOT NULL,
  `bmi` decimal(5,2) NOT NULL,
  `bmi_category` char(5) NOT NULL,
  `height_fa` char(5) NOT NULL,
  `remarks` int(11) NOT NULL,
  `birthday` date DEFAULT NULL,
  `nationality` varchar(20) DEFAULT NULL,
  `mother_tongue` varchar(50) DEFAULT NULL,
  `ethnic_group` varchar(50) DEFAULT NULL,
  `religion` varchar(20) DEFAULT NULL,
  `status` char(5) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `elgb_type` char(3) NOT NULL,
  `elgb_school` varchar(200) NOT NULL,
  `elgb_school_id` varchar(100) DEFAULT NULL,
  `elgb_school_type` char(4) DEFAULT NULL,
  `elgb_school_address` varchar(200) NOT NULL,
  `elgb_completion_date` date DEFAULT NULL,
  `elgb_gen_avg` decimal(6,3) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` char(8) NOT NULL,
  `department_id` char(2) DEFAULT NULL,
  `year_level_id` varchar(2) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `alias` varchar(10) DEFAULT NULL,
  `type` char(4) DEFAULT NULL,
  `units` decimal(5,2) DEFAULT NULL,
  `status` varchar(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `year_levels`
--

DROP TABLE IF EXISTS `year_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `year_levels` (
  `id` char(2) NOT NULL,
  `department_id` char(2) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `alias` varchar(10) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `year_levels`
--

LOCK TABLES `year_levels` WRITE;
/*!40000 ALTER TABLE `year_levels` DISABLE KEYS */;
/*!40000 ALTER TABLE `year_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'marqa_one_240728'
--
