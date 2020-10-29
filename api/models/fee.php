<?php
class Fee extends AppModel {
	var $name = 'Fee';
	//var $useDbConfig = 'sfm';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasAndBelongsToMany = array(
		'Account' => array(
			'className' => 'Account',
			'joinTable' => 'account_fees',
			'foreignKey' => 'fee_id',
			'associationForeignKey' => 'account_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		
	);
	
	
	

}
