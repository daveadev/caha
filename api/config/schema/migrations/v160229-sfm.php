<?php 
/* Api schema generated on: 2016-02-29 09:34:10 : 1456734850*/
class ApiSchema extends CakeSchema {
	var $name = 'Api';

	var $connection = 'sfm';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $account_adjustments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'account_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'item_code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 3, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'flag' => array('type' => 'string', 'null' => true, 'default' => '+', 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $account_fees = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'account_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fee_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 3, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'due_amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'adjust_amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'paid_amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $account_histories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'account_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'transac_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'transac_time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'ref_no' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'details' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'flag' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $account_schedules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'account_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'bill_month' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 8, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'due_amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'paid_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $accounts = array(
		'id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1', 'key' => 'primary'),
		'account_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'account_details' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'outstanding_balance' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $billing_periods = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 4, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'payment_frequency' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'bill_month_start' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
		'bill_cutoff_date' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
		'bill_cycle_increment' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $discounts = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 4, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 140, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'fees_applicable' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 140, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $fee_breakdowns = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tuition_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'fee_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 3, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $fees = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 3, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $ledgers = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'account_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'transac_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'transac_time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'ref_no' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'details' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $payment_scheme_schedules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'payment_scheme_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'billing_period_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 4, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'due_dates' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $payment_schemes = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 7, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'tuition_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'scheme_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 4, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'total_amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'variance_amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $schemes = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 4, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'payment_frequency' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $tuition_discounts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tuition_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'discount_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 4, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	var $tuitions = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 7, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'sy' => array('type' => 'text', 'null' => true, 'default' => NULL, 'length' => 4),
		'year_level_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'program_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 3, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
}
?>