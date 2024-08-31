UPDATE account_schedules
	SET due_amount=260,paid_amount=260
	WHERE id=2246;
UPDATE account_schedules
	SET paid_amount=222.5
	WHERE id=2444;
UPDATE account_schedules
	SET paid_amount=0.5
	WHERE id=2247;
UPDATE ledgers
	SET amount=223
	WHERE id=2083;
UPDATE ledgers
	SET amount=260
	WHERE id=2082;
UPDATE ledgers
	SET amount=1980
	WHERE id=2229;
INSERT INTO ledgers (account_id,`type`,transaction_type_id,esp,transac_date,transac_time,ref_no,details,amount,created)
	VALUES ('GRS78428','-','SBQPY',2024.00,'2024-08-15','02:27:37','SI10696','Subsequent Payment',174,'2024-08-29 14:27:37');
UPDATE account_schedules
	SET status='PAID',paid_amount=174
	WHERE id=2471;
INSERT INTO ledgers (account_id,`type`,transaction_type_id,esp,transac_date,transac_time,ref_no,details,amount,notes,created)
	VALUES ('GRS37788','+','ACEC',2024.00,'2024-09-01','01:23:45','GRF240901','AC/EC',75.00,'','2024-09-01 06:56:05');
INSERT INTO account_schedules (transaction_type_id,account_id,bill_month,due_amount,paid_amount,due_date,status,`order`,created,modified)
	VALUES ('ACECF','GRS37788','SEP2024',75.00,0.00,'2024-09-07','NONE',4,'2024-09-01 06:56:05','2024-09-01 06:56:05')