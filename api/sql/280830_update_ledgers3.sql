UPDATE account_schedules
	SET paid_amount=174
	WHERE id=2470;
UPDATE account_schedules
	SET due_amount=260,paid_amount=260
	WHERE id=1751;
UPDATE account_schedules
	SET due_amount=21242.60,paid_amount=21242.6
	WHERE id=1750;
UPDATE ledgers
	SET ref_no='#10324'
	WHERE id=1104;
UPDATE ledgers
	SET ref_no='#10324'
	WHERE id=1103;
UPDATE ledgers
	SET ref_no='#9394'
	WHERE id=1102;
DELETE FROM ledgers
	WHERE id=1042;
UPDATE account_schedules
	SET paid_amount=1000,due_amount=6014.6
	WHERE id=1531;
UPDATE ledgers
	SET amount=12010
	WHERE id=965;
UPDATE account_schedules
	SET paid_amount=12010
	WHERE id=1431;
INSERT INTO ledgers (account_id,`type`,transaction_type_id,esp,transac_date,transac_time,ref_no,details,amount,notes,created)
	VALUES ('GRS81380','-','DSCNT',2024.00,'2024-07-15','00:34:56','GRA000104','Honor Discount',1980,'','2024-08-03 00:34:56');
UPDATE account_schedules
	SET due_amount=250,paid_amount=250
	WHERE id=1520;
UPDATE account_schedules
	SET paid_amount=177
	WHERE id=2430;