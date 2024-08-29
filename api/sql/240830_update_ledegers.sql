INSERT INTO ledgers (account_id,`type`,transaction_type_id,esp,transac_date,transac_time,ref_no,details,amount,notes,created)
	VALUES ('GRS63602','-','DSCNT',2024.00,'2024-07-15','00:34:56','GRA000205','Honor Discount',990,'','2024-08-03 00:34:56');
UPDATE account_schedules
	SET paid_amount=260.5,due_amount=1250
	WHERE id=2246;
UPDATE account_schedules
	SET paid_amount=222.50
	WHERE id=2444;
UPDATE ledgers
	SET amount=222.5
	WHERE id=2083;
UPDATE  ledgers
	SET amount=260.5
	WHERE id=2082;
UPDATE ledgers
	SET ref_no='#10625'
	WHERE id=2083;
DELETE FROM ledgers
	WHERE id=1893;
DELETE FROM ledgers
	WHERE id=1905;
UPDATE ledgers
	SET amount=223
	WHERE id=1906;
UPDATE account_schedules
	SET paid_amount=0.5
	WHERE id=2192;
UPDATE ledgers
	SET transac_date='2024-08-06'
	WHERE id=1937;
UPDATE ledgers
	SET ref_no='#10387'
	WHERE id=497;
UPDATE ledgers
	SET ref_no='#9443'
	WHERE id=496;
DELETE FROM ledgers
	WHERE id=1953;
UPDATE ledgers
	SET transac_date='2024-08-06'
	WHERE id=1933;
UPDATE ledgers
	SET transac_date='2024-08-06'
	WHERE id=1932;
DELETE FROM ledgers
	WHERE id=1892;
UPDATE ledgers
	SET amount=13212
	WHERE id=685;