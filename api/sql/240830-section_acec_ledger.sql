-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: caha_pay_240830_2
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
-- Table structure for table `section_acec_charges`
--

DROP TABLE IF EXISTS `section_acec_charges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_acec_charges` (
  `section_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `bill_month` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_acec_charges`
--

LOCK TABLES `section_acec_charges` WRITE;
/*!40000 ALTER TABLE `section_acec_charges` DISABLE KEYS */;
INSERT INTO `section_acec_charges` VALUES (1000,76.65,'2024-08-07',1),(1001,70.52,'2024-08-07',2),(1102,106.32,'2024-08-07',3),(1100,110,'2024-08-07',4),(1101,126.38,'2024-08-07',5),(2101,98.33,'2024-08-07',6),(2100,103.24,'2024-08-07',7),(2200,144,'2024-08-07',8),(2300,177,'2024-08-07',9),(2600,222.5,'2024-08-07',10),(2400,174,'2024-08-07',11),(2500,186.89,'2024-08-07',12),(2301,177,'2024-08-07',13),(1000,59,'2024-09-07',14),(1001,55,'2024-09-07',15),(1102,81,'2024-09-07',16),(1100,75,'2024-09-07',17),(1101,86,'2024-09-07',18),(2101,94,'2024-09-07',19),(2100,99,'2024-09-07',20),(2200,124,'2024-09-07',21),(2300,161,'2024-09-07',22),(2600,189,'2024-09-07',23),(2400,154,'2024-09-07',24),(2500,174,'2024-09-07',25),(2301,161,'2024-09-07',26);
/*!40000 ALTER TABLE `section_acec_charges` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-30 14:41:18

INSERT INTO account_schedules  (SELECT null, 'ACECF', cb.student_id, 'SEP2024',sac.amount , 0 , sac.bill_month ,null,'NONE',4, NOW(), NOW()   from marqa_one_240805.classlist_blocks cb   inner join section_acec_charges sac    on (cb.section_id = sac.section_id)
where sac.bill_month  ='2024-09-07');
INSERT INTO ledgers  (SELECT null,  cb.student_id,'+', 'ACEC',2024, '2024-09-01','01:23:45','GRF240901','AC/EC',sac.amount ,'', NOW()   from marqa_one_240805.classlist_blocks cb   inner join section_acec_charges sac    on (cb.section_id = sac.section_id)
where sac.bill_month  ='2024-09-07');
