<?php
/* Transaction Fixture generated on: 2016-02-29 08:23:41 : 1456730621 */
class TransactionFixture extends CakeTestFixture {
	var $name = 'Transaction';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ref_no' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'transac_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'transac_time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'account_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'amount' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'type' => 'Lorem ipsum d',
			'status' => 'Lorem ipsum d',
			'ref_no' => 'Lorem ipsum d',
			'transac_date' => '2016-02-29',
			'transac_time' => '08:23:41',
			'account_id' => 'Lorem ipsum d',
			'amount' => 1,
			'created' => '2016-02-29 08:23:41',
			'modified' => '2016-02-29 08:23:41'
		),
	);
}
