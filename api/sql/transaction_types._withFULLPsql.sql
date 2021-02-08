/*
SQLyog Ultimate v9.10 
MySQL - 5.5.5-10.1.31-MariaDB : Database - srp_lks_210208
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `transaction_types` */

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
  `is_quantity` tinyint(1) DEFAULT NULL,
  `is_specify` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_types` */

insert  into `transaction_types`(`id`,`name`,`type`,`default_amount`,`charge`,`pay`,`is_ledger`,`is_fixed`,`is_tuition`,`is_show_always`,`is_quantity`,`is_specify`,`created`,`modified`) values ('CERTF','Certificate','AR','100.00',0,1,1,0,0,0,1,0,NULL,NULL),('DIPLM','Diploma','AR','0.00',0,1,1,0,0,0,0,0,NULL,NULL),('DSESC','ESC','discount','0.00',0,0,1,0,0,0,0,0,NULL,NULL),('DSPUB','Public','discount','0.00',0,0,1,0,0,0,0,0,NULL,NULL),('DSQVR','QVR','discount','0.00',0,0,1,0,0,0,0,0,NULL,NULL),('FORM1','Form 137','AR','0.00',0,1,1,0,0,0,1,0,NULL,NULL),('FULLP','Full Payment','passive','0.00',0,1,1,1,1,0,0,0,NULL,NULL),('GRDFE','Graduation Fee','AR','0.00',0,1,1,0,0,0,0,0,NULL,NULL),('INIPY','Initial Payment','reactive','0.00',0,1,1,0,1,0,0,0,NULL,NULL),('MODUL','Modules','AR','0.00',0,1,1,0,0,0,1,0,NULL,NULL),('OLDAC','Old Account','passive','0.00',0,1,1,0,0,1,0,0,NULL,NULL),('OTHRS','Others','AR','0.00',0,1,1,0,0,0,1,1,NULL,NULL),('REGXX','Regular','passive','0.00',0,0,1,0,0,0,0,0,NULL,NULL),('RENTL','Rentals','AR','0.00',0,1,1,0,0,0,0,1,NULL,NULL),('SBQPY','Subsequent Payment','reactive','0.00',0,1,1,0,1,0,0,0,NULL,NULL),('TUIXN','Tuition','passive','0.00',1,0,1,0,0,0,0,0,NULL,NULL),('UNIFM','Uniform','AR','0.00',0,1,1,0,0,0,1,1,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
