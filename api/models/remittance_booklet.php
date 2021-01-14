<?php
class RemittanceBooklet extends AppModel {
	var $name = 'RemittanceBooklet';


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