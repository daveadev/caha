<?php
/* AccountHistory Fixture generated on: 2016-02-29 08:23:33 : 1456730613 */
class AccountHistoryFixture extends CakeTestFixture {
	var $name = 'AccountHistory';

	var $fields = array(
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

	var $records = array(
		array(
			'id' => 1,
			'account_id' => 'Lorem ipsum d',
			'transac_date' => '2016-02-29',
			'transac_time' => '08:23:33',
			'ref_no' => 'Lorem ip',
			'details' => 'Lorem ipsum dolor sit amet',
			'flag' => 'Lorem ipsum dolor sit ame',
			'amount' => 1,
			'created' => '2016-02-29 08:23:33',
			'modified' => '2016-02-29 08:23:33'
		),
	);
}
