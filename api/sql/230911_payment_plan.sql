CREATE TABLE payment_plans (
	id int auto_increment NULL,
	account_id char(8) NULL,
	payment_start date NULL,
	total_due decimal(8,2) NULL,
	total_payments decimal(8,2) NULL,
	total_balance decimal(8,2) NULL,
	terms int NULL,
	monthly_payments decimal(8,2) NULL,
	guarantor varchar(150) NULL,
	`user` varchar(20) NULL,
	created datetime NULL,
	modified datetime NULL,
	CONSTRAINT payment_plans_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

CREATE TABLE pay_plan_schedules (
	id int auto_increment NULL,
	payment_plan_id int NULL,
	due_date date NULL,
	due_amount decimal(8,2) NULL,
	paid_amount decimal(8,2) NULL,
	status char(5) NULL,
	CONSTRAINT pay_plan_schedules_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;

