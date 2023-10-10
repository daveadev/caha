<?php
class Booklet extends AppModel {
	var $name = 'Booklet';

	var $belongsTo = array(

		'Cashier' => array(
			'className' => 'Cashier',
			'foreignKey' => 'cashier_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
var $hasMany = array(
		'CashierCollection' => array(
			'className' => 'CashierCollection',
			'foreignKey' => 'booklet_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
}