<?php
class RemittanceBreakdown extends AppModel {
	var $name = 'RemittanceBreakdown';
	//var $useDbConfig = 'sfm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Remittance' => array(
			'className' => 'Remittance',
			'foreignKey' => 'remittance_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	

}
