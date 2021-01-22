<?php
class Booklet extends AppModel {
	var $name = 'Booklet';


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