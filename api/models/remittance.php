<?php
class Remittance extends AppModel {
	var $name = 'Remittance';
	//var $useDbConfig = 'sfm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'RemittanceBreakdown' => array(
			'className' => 'RemittanceBreakdown',
			'foreignKey' => 'remittance_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'RemittanceBooklet' => array(
			'className' => 'RemittanceBooklet',
			'foreignKey' => 'remittance_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	

}
