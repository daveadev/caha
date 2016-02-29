<?php
/* Ledger Fixture generated on: 2016-02-29 08:23:37 : 1456730617 */
class LedgerFixture extends CakeTestFixture {
	var $name = 'Ledger';

	var $fields = array(
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

	var $records = array(
		array(
			'id' => '56d3f1f9-02e4-49ea-a312-048809efb66d',
			'account_id' => 'Lorem ipsum d',
			'type' => 'Lorem ipsum dolor sit ame',
			'transac_date' => '2016-02-29',
			'transac_time' => '08:23:37',
			'ref_no' => 'Lorem ipsum d',
			'details' => 'Lorem ipsum dolor sit amet',
			'amount' => 1,
			'created' => '2016-02-29 08:23:37',
			'modified' => '2016-02-29 08:23:37'
		),
	);
}
