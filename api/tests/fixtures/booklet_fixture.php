<?php
/* Booklet Fixture generated on: 2016-02-29 08:23:35 : 1456730615 */
class BookletFixture extends CakeTestFixture {
	var $name = 'Booklet';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'series_start' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'series_end' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'series_counter' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'cashier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 11, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'series_start' => 1,
			'series_end' => 1,
			'series_counter' => 1,
			'status' => 'Lorem ip',
			'cashier' => 'Lorem ips',
			'created' => '2016-02-29 08:23:35',
			'modified' => '2016-02-29 08:23:35'
		),
	);
}
