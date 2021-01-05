/*
SQLyog Ultimate v9.10 
MySQL - 5.5.5-10.1.30-MariaDB : Database - srp_app201105
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`srp_app201105` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `srp_app201105`;

/*Table structure for table `remittance_breakdowns` */

DROP TABLE IF EXISTS `remittance_breakdowns`;

CREATE TABLE `remittance_breakdowns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remittance_id` int(11) DEFAULT NULL,
  `denomination` decimal(6,2) DEFAULT NULL,
  `quantity` int(2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

/*Table structure for table `remittances` */

DROP TABLE IF EXISTS `remittances`;

CREATE TABLE `remittances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashier_id` char(8) DEFAULT NULL,
  `remittance_date` date DEFAULT NULL,
  `total_collection` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
