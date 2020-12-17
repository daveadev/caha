<?php
class User extends AppModel {
	var $name = 'User';
	var $consumableFields = array('id','username','user_type_id','password');
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		
		'Cashier' => array(
			'className' => 'Cashier',
			'foreignKey' => 'user_id',
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
