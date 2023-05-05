<?php
class PaymentSchemeSchedule extends AppModel {
	var $name = 'PaymentSchemeSchedule';
	var $useDbConfig = 'sem';
	
	var $belongsTo = array(
		'PaymentScheme' => array(
			'className' => 'PaymentScheme',
			'foreignKey' => 'payment_scheme_id',
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