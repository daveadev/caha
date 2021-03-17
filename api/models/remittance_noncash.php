<?php
class RemittanceNoncash extends AppModel {
	var $name = 'RemittanceNoncash';


	var $belongsTo = array(
		'Remittance' => array(
			'className' => 'Remittance',
			'foreignKey' => 'remittance_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
}