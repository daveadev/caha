<?php
/* TransactionDetail Fixture generated on: 2016-02-29 08:23:39 : 1456730619 */
class TransactionDetailFixture extends CakeTestFixture {
	var $name = 'TransactionDetail';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'transaction_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'ref_no' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'details' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'transaction_id' => 1,
			'ref_no' => 'Lorem ipsum d',
			'details' => 'Lorem ipsum dolor sit amet',
			'amount' => 1
		),
	);
}
