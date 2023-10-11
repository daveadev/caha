CREATE TABLE payplan_ledgers (
	id int(11) auto_increment NOT NULL,
	account_id char(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	`type` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	transaction_type_id char(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	esp decimal(6,2) NULL,
	transac_date date NULL,
	transac_time time NULL,
	ref_no char(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	details text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	amount decimal(10,2) NULL,
	notes text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
	created datetime NULL,
	CONSTRAINT `PRIMARY` PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci
COMMENT='';
