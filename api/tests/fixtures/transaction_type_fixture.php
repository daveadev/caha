<?php
/* TransactionType Fixture generated on: 2016-02-29 08:23:40 : 1456730620 */
class TransactionTypeFixture extends CakeTestFixture {
	var $name = 'TransactionType';

	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 3, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'default_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00', 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 'L',
			'name' => 'Lorem ipsum dolor sit amet',
			'default_amount' => 1,
			'created' => '2016-02-29 08:23:40',
			'modified' => '2016-02-29 08:23:40'
		),
	);
}
