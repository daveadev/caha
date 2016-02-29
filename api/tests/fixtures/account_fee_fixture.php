<?php
/* AccountFee Fixture generated on: 2016-02-29 08:23:32 : 1456730612 */
class AccountFeeFixture extends CakeTestFixture {
	var $name = 'AccountFee';

	var $fields = array(
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

	var $records = array(
		array(
			'id' => 1,
			'account_id' => 'Lorem ipsum d',
			'fee_id' => 'L',
			'due_amount' => 1,
			'adjust_amount' => 1,
			'paid_amount' => 1,
			'created' => '2016-02-29 08:23:32',
			'modified' => '2016-02-29 08:23:32'
		),
	);
}
