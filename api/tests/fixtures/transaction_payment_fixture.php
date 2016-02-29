<?php
/* TransactionPayment Fixture generated on: 2016-02-29 08:23:39 : 1456730619 */
class TransactionPaymentFixture extends CakeTestFixture {
	var $name = 'TransactionPayment';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'transaction_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'transaction_type_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 4, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'details' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'transaction_id' => 1,
			'transaction_type_id' => 'Lo',
			'details' => 'Lorem ipsum dolor sit amet',
			'amount' => 1
		),
	);
}
