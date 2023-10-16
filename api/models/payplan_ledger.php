<?php
class PayplanLedger extends AppModel {
	var $name = 'PayplanLedger';
	var $belongsTo = array(
		'PaymentPlan' => array(
			'className' => 'PaymentPlan',
			'foreignKey' => false,
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