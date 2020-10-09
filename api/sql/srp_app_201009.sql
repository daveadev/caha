/*
SQLyog Ultimate v9.10 
MySQL - 5.5.5-10.1.31-MariaDB : Database - srp_app_201009
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `account_adjustments` */

DROP TABLE IF EXISTS `account_adjustments`;

CREATE TABLE `account_adjustments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` char(15) DEFAULT NULL,
  `item_code` char(4) DEFAULT NULL,
  `details` varchar(50) DEFAULT NULL,
  `adjust_date` date DEFAULT NULL,
  `flag` char(1) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=latin1;

/*Data for the table `account_adjustments` */

insert  into `account_adjustments`(`id`,`account_id`,`item_code`,`details`,`adjust_date`,`flag`,`amount`,`created`,`modified`) values (10001,'AF2000001','AJCR','Test Credit','2020-10-09','+','10.00',NULL,NULL),(10002,'AF2000001','AJDB','Test Debit','2020-10-09','-','10.00',NULL,NULL);

/*Table structure for table `account_fees` */

DROP TABLE IF EXISTS `account_fees`;

CREATE TABLE `account_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` char(15) DEFAULT NULL,
  `fee_id` char(3) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `adjust_amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `account_fees` */

insert  into `account_fees`(`id`,`account_id`,`fee_id`,`due_amount`,`paid_amount`,`adjust_amount`,`created`,`modified`) values (1,'AF2000001','TUI','15000.00','8000.00','0.00',NULL,NULL);

/*Table structure for table `account_histories` */

DROP TABLE IF EXISTS `account_histories`;

CREATE TABLE `account_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` char(15) DEFAULT NULL,
  `transac_date` date DEFAULT NULL,
  `transac_time` time DEFAULT NULL,
  `ref_no` char(10) DEFAULT NULL,
  `details` varchar(50) DEFAULT NULL,
  `flag` char(1) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `account_histories` */

insert  into `account_histories`(`id`,`account_id`,`transac_date`,`transac_time`,`ref_no`,`details`,`flag`,`amount`,`created`,`modified`) values (1,'AF2000001','2020-10-09','01:23:45','AF2000001','ESCDSC','-','500.00',NULL,NULL),(2,'AF2000001','2020-10-09','01:23:45','OR12345','INIPY','-','7500.00',NULL,NULL);

/*Table structure for table `account_schedules` */

DROP TABLE IF EXISTS `account_schedules`;

CREATE TABLE `account_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` char(15) DEFAULT NULL,
  `bill_month` char(8) DEFAULT NULL,
  `due_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `status` char(4) DEFAULT NULL COMMENT 'PAID,OVER,LATE',
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `account_schedules` */

insert  into `account_schedules`(`id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`) values (1,'AF2000001','UPONNROL','7500.00','7500.00','2020-10-10 00:00:00','2020-10-09 00:00:00','PAID',1,NULL,NULL),(2,'AF2000001','NOV2020','3500.00',NULL,'2020-11-10 00:00:00',NULL,'OPEN',2,NULL,NULL),(3,'AF2000001','DEC2020','3500.00',NULL,'2020-12-10 00:00:00',NULL,'OPEN',3,NULL,NULL);

/*Table structure for table `account_transactions` */

DROP TABLE IF EXISTS `account_transactions`;

CREATE TABLE `account_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` char(15) DEFAULT NULL,
  `transaction_type_id` char(5) DEFAULT NULL COMMENT 'INIPY,SBQPY',
  `ref_no` char(10) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `source` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `account_transactions` */

insert  into `account_transactions`(`id`,`account_id`,`transaction_type_id`,`ref_no`,`amount`,`source`,`created`,`modified`) values (1,'AF2000001','INIPY','OR12345','7500.00','cashier',NULL,NULL);

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` char(15) NOT NULL,
  `account_type` char(10) DEFAULT NULL,
  `account_details` text,
  `payment_scheme` char(4) DEFAULT NULL COMMENT 'CASH,INSTL',
  `assessment_total` decimal(10,2) DEFAULT NULL,
  `outstanding_balance` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `rounding_off` decimal(6,5) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `accounts` */

insert  into `accounts`(`id`,`account_type`,`account_details`,`payment_scheme`,`assessment_total`,`outstanding_balance`,`discount_amount`,`rounding_off`,`created`,`modified`) values ('AF2000001','student',NULL,'INST','15000.00','7000.00','500.00','0.00000',NULL,NULL);

/*Table structure for table `booklets` */

DROP TABLE IF EXISTS `booklets`;

CREATE TABLE `booklets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashier_id` char(8) DEFAULT NULL,
  `series_start` int(11) DEFAULT NULL,
  `series_end` int(11) DEFAULT NULL,
  `series_counter` int(11) DEFAULT NULL,
  `status` char(10) DEFAULT NULL COMMENT 'ISSUED,ACTIVE,USED',
  `receipt_type` char(2) DEFAULT NULL COMMENT 'OR,AR,OA',
  `cashier` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `booklets` */

insert  into `booklets`(`id`,`cashier_id`,`series_start`,`series_end`,`series_counter`,`status`,`receipt_type`,`cashier`,`created`,`modified`) values (1,'LSS12345',12345,1299,12345,'ACTIVE','OR','dave',NULL,NULL);

/*Table structure for table `cashiers` */

DROP TABLE IF EXISTS `cashiers`;

CREATE TABLE `cashiers` (
  `id` char(8) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_no` varchar(10) DEFAULT NULL,
  `employee_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cashiers` */

insert  into `cashiers`(`id`,`user_id`,`employee_no`,`employee_name`) values ('LSF12345',1,'12345','Dave Arroyo');

/*Table structure for table `fees` */

DROP TABLE IF EXISTS `fees`;

CREATE TABLE `fees` (
  `id` char(3) NOT NULL COMMENT 'TUI,REG,MSC',
  `name` varchar(20) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `fees` */

insert  into `fees`(`id`,`name`,`order`,`created`,`modified`) values ('TUI','Tuition',1,NULL,NULL);

/*Table structure for table `ledgers` */

DROP TABLE IF EXISTS `ledgers`;

CREATE TABLE `ledgers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` char(15) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `esp` decimal(6,2) DEFAULT NULL,
  `transac_date` date DEFAULT NULL,
  `transac_time` time DEFAULT NULL,
  `ref_no` char(10) DEFAULT NULL,
  `details` text,
  `amount` decimal(10,2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `ledgers` */

insert  into `ledgers`(`id`,`account_id`,`type`,`esp`,`transac_date`,`transac_time`,`ref_no`,`details`,`amount`,`created`) values (1,'AF2000001','+','2020.00','2020-10-09','01:23:45','AF2000001','Tuition Fee','15000.00',NULL),(2,'AF2000001','-','2020.00','2020-10-09','01:23:45','AF2000001','ESC Discount','500.00',NULL),(3,'AF2000001','-','2020.00','2020-10-10','01:23:45','OR12345','Initial Payment','7500.00',NULL),(4,'AF2000001','+','2020.00','2020-10-10','01:23:45','AJ10001','Test Credit','10.00',NULL),(5,'AF2000001','-','2020.00','2020-10-10','01:23:56','AJ10002','Test Debit','10.00',NULL);

/*Table structure for table `master_configs` */

DROP TABLE IF EXISTS `master_configs`;

CREATE TABLE `master_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_key` char(20) DEFAULT NULL,
  `sys_value` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `master_configs` */

insert  into `master_configs`(`id`,`sys_key`,`sys_value`,`created`,`modified`) values (1,'SCHOOL_NAME','LAKESHORE','2018-06-21 06:21:23','2018-06-21 06:21:23'),(2,'SCHOOL_ALIAS','LS','2018-06-21 06:21:36','2018-06-21 06:21:36'),(3,'SCHOOL_LOGO','logo.jpg','2018-06-21 06:21:51','2018-06-21 06:21:51'),(4,'SCHOOL_ADDRESS','BINAN, LAGUNA','2018-06-21 06:22:05','2018-06-21 06:22:05'),(5,'START_SY','2020','2018-06-21 06:22:18','2020-08-24 11:27:35'),(6,'ACTIVE_SY','2020','2018-06-21 06:22:28','2020-09-17 16:09:41'),(7,'DEFAULT_','{\r\n   \"PRECISION\":0,\r\n   \"FLOOR_GRADE\":70,\r\n   \"CEIL_GRADE\":100,\r\n   \"PERIOD\":{\r\n      \"id\":1,\r\n      \"key\":\"FRSTGRDG\",\r\n      \"alias\":{\r\n         \"short\":\"MID\",\r\n         \"full\":\"Midterm\",\r\n	 \"desc\":\"1ST\"\r\n      }\r\n   },\r\n   \"SEMESTER\":{\r\n      \"id\":25,\r\n      \"key\":\"FRSTSEMS\",\r\n      \"alias\":{\r\n         \"short\":\"1st\",\r\n         \"full\":\"First Sem\"\r\n      }\r\n   }\r\n}',NULL,NULL),(8,'SCHOOL_ID','DU',NULL,NULL),(9,'ACTIVE_DEPT','SH',NULL,NULL),(10,'SCHOOL_COLOR','{\"r\":\"100\",\"g\":\"0\",\"b\":\"0\"}',NULL,NULL);

/*Table structure for table `paymen_methods` */

DROP TABLE IF EXISTS `paymen_methods`;

CREATE TABLE `paymen_methods` (
  `id` char(4) DEFAULT NULL COMMENT 'CASH,CHCK,CARD,CHRG',
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `icon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `paymen_methods` */

insert  into `paymen_methods`(`id`,`name`,`description`,`icon`) values ('CASH','Cash','Payment thru cash',NULL),('CHCK','Check','Payment thru check','ok');

/*Table structure for table `transaction_details` */

DROP TABLE IF EXISTS `transaction_details`;

CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) DEFAULT NULL,
  `transaction_type_id` char(5) DEFAULT NULL COMMENT 'INIPY,SBQPY',
  `details` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `transaction_details` */

insert  into `transaction_details`(`id`,`transaction_id`,`transaction_type_id`,`details`,`amount`) values (1,10000,'INIPY',NULL,'7500.00');

/*Table structure for table `transaction_payments` */

DROP TABLE IF EXISTS `transaction_payments`;

CREATE TABLE `transaction_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) DEFAULT NULL,
  `payment_method_id` char(4) DEFAULT NULL COMMENT 'CASH,CHCK,CARD,CHCK',
  `details` varchar(50) DEFAULT NULL,
  `valid_on` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `transaction_payments` */

insert  into `transaction_payments`(`id`,`transaction_id`,`payment_method_id`,`details`,`valid_on`,`amount`) values (1,10000,'CASH','cash',NULL,'500.00'),(2,10000,'CHCK','BDO-12345X','2020-10-09','7000.00');

/*Table structure for table `transaction_types` */

DROP TABLE IF EXISTS `transaction_types`;

CREATE TABLE `transaction_types` (
  `id` char(5) NOT NULL COMMENT 'INIPY,SBQPY',
  `name` varchar(50) DEFAULT NULL,
  `default_amount` decimal(10,2) DEFAULT '0.00',
  `is_charge` tinyint(1) DEFAULT '0',
  `is_pay` tinyint(1) DEFAULT '0',
  `is_ledger` tinyint(1) DEFAULT '0',
  `is_fixed` tinyint(1) DEFAULT '0',
  `is_tuition` tinyint(1) DEFAULT '0',
  `is_show_always` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `transaction_types` */

insert  into `transaction_types`(`id`,`name`,`default_amount`,`is_charge`,`is_pay`,`is_ledger`,`is_fixed`,`is_tuition`,`is_show_always`,`created`,`modified`) values ('INIPY','Initial Payment','0.00',0,1,1,0,1,0,NULL,NULL),('OLDAC','Old Account','0.00',0,1,1,0,0,1,NULL,NULL),('SBQPY','Subsequent Payment','0.00',0,1,1,0,1,0,NULL,NULL);

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(15) DEFAULT NULL,
  `status` char(15) DEFAULT NULL,
  `ref_no` char(10) DEFAULT NULL,
  `transac_date` date DEFAULT NULL,
  `transac_time` time DEFAULT NULL,
  `account_id` char(15) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=latin1;

/*Data for the table `transactions` */

insert  into `transactions`(`id`,`type`,`status`,`ref_no`,`transac_date`,`transac_time`,`account_id`,`created`,`modified`) values (10000,'payment','fulfilled','OR12345','2020-10-09','01:23:45','AF2000001',NULL,NULL);

/*Table structure for table `user_grants` */

DROP TABLE IF EXISTS `user_grants`;

CREATE TABLE `user_grants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_id` char(5) DEFAULT NULL,
  `master_module_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_grn_usr` (`user_type_id`),
  KEY `FK_grn_mod` (`master_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=latin1;

/*Data for the table `user_grants` */

/*Table structure for table `user_types` */

DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` char(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_types` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`id`,`user_type_id`,`username`,`password`,`status`,`login_failed`,`ip_failed`,`login_success`,`ip_success`,`password_changed`,`created`,`modified`) values (1,'cshr','admin','846385b5749d96060e2a6da69fa592c3f77c5fe0','ACTIV',NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
