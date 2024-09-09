UPDATE account_schedules
	SET paid_amount=15024.60,due_amount=18024.60,status='NONE'
	WHERE id=1475;
DELETE FROM ledgers
	WHERE id=1475;
UPDATE account_schedules
	SET status='NONE',paid_amount=98.00
	WHERE id=2361;
UPDATE account_schedules
	SET paid_amount=0
	WHERE id=1071;
UPDATE account_schedules
	SET status='NONE',paid_amount=0
	WHERE id=1070;
UPDATE ledgers
	SET amount=1400
	WHERE id=380;
UPDATE ledgers
	SET ref_no='#9664',amount=4866
	WHERE id=381;
INSERT INTO ledgers (account_id,`type`,transaction_type_id,esp,transac_date,transac_time,ref_no,details,amount,notes,created)
	VALUES ('GRS91298','-','SBQPY',2024.00,'2024-07-15','00:34:56','#10481','Subsequent Payment',6266.50,'','2024-08-03 00:34:56');
	UPDATE account_schedules
	SET status='PAID',paid_amount=81
	WHERE id=2564;
UPDATE account_schedules
	SET status='PAID',paid_amount=1902.5
	WHERE id=596;
UPDATE account_schedules
	SET status='PAID',paid_amount=11132.5
	WHERE id=595;
UPDATE account_schedules
	SET paid_amount=1400
	WHERE id=594;
UPDATE account_schedules
	SET status='NONE'
	WHERE id=2564;
UPDATE account_schedules
	SET paid_amount=80.68
	WHERE id=2564;
UPDATE account_schedules
	SET paid_amount=106.32
	WHERE id=2314;
UPDATE account_schedules
	SET paid_amount=1902.5
	WHERE id=597;

