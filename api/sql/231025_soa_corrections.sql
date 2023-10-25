CREATE TABLE soa_corrections (
	id int auto_increment NOT NULL,
	account_id char(8) NULL,
	esp decimal(6,2) NULL,
	correction char(20) NULL,
	details varchar(1000) NULL,
	username varchar(10) NULL,
	hash varchar(40) NULL,
	created DATETIME NULL,
	CONSTRAINT soa_corrections_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;
