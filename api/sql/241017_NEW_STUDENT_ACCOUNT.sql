/*
Add to accounts , refno latest, assessment amount,  total payment, outstanding balance
*/
INSERT INTO `accounts`(`id`,`account_type`,`ref_no`,`account_details`,`payment_scheme`,`assessment_total`,`subsidy_status`,`discount_amount`,`payment_total`,`old_balance`,`outstanding_balance`,`module_balance`,`rounding_off`,`created`,`modified`)VALUES('GRS76216','student','GRA00211',NULL,NULL,'32350.00','0',NULL,'15750','0.00','16600','0.00','0.00000','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
/*
Account schedules breakdown
*/
 INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`) VALUES(NULL,'REGFE','GRS76216','UPONNROL','1500.00','1500.00','2024-10-16','2024-10-16','PAID','1','2024-10-17 01:23:45','2024-10-17 01:23:45');
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'INIPY','GRS76216','UPONNROL','14250','14250','2024-10-16','2024-10-16','PAID','2','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'SBQPY','GRS76216','NOV2024','3650','0','2024-11-07','','NONE','3','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'SBQPY','GRS76216','DEC2024','3650','0.00','2024-12-07','0000-00-00','NONE','4','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'SBQPY','GRS76216','JAN2024','3825.00','0.00','2025-01-07','0000-00-00','NONE','5','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'SBQPY','GRS76216','FEB2024','1825.00','0.00','2025-02-07','0000-00-00','NONE','6','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'SBQPY','GRS76216','MAR2024','1825.00','0.00','2025-03-07','0000-00-00','NONE','7','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
INSERT INTO `account_schedules`(`id`,`transaction_type_id`,`account_id`,`bill_month`,`due_amount`,`paid_amount`,`due_date`,`paid_date`,`status`,`order`,`created`,`modified`)VALUES(NULL,'SBQPY','GRS76216','APR2024','1825.00','0.00','2025-04-07','0000-00-00','NONE','8','2024-10-17 01:23:45','2024-10-17 01:23:45'); 
/*
Ledgers entriess
charges and inipay
*/
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','+','REGFE','2024.00','2024-10-16','00:34:56','GRA00211','Registration Fee','1500.00','','2024-10-17 01:23:45');
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','+','ANNSF','2024.00','2024-10-16','00:34:56','GRA00211','Annual School Fee','6100.00','','2024-10-17 01:23:45');
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','+','TUIXN','2024.00','2024-10-16','00:34:56','GRA00211','Tuition','17250.00','','2024-10-17 01:23:45');
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','+','ENERF','2024.00','2024-10-16','00:34:56','GRA00211','Energy Fee','1000.00','','2024-10-17 01:23:45');
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','+','LERMA','2024.00','2024-10-16','00:34:56','GRA00211','Learning Material','6500.00','','2024-10-17 01:23:45');
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','-','REGFE','2024.00','2024-10-16','00:34:56','#11078','Registration Fee','1500.00','','2024-10-17 01:23:45');
insert into `ledgers` (`account_id`, `type`, `transaction_type_id`, `esp`, `transac_date`, `transac_time`, `ref_no`, `details`, `amount`, `notes`, `created`) values('GRS76216','-','SBQPY','2024.00','2024-10-16','00:34:56','#11078','Subsequent Payment','14250.00','','2024-10-17 01:23:45');


