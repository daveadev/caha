<?php
class PayplanLedger extends AppModel {
	var $name = 'PayplanLedger';
	var $belongsTo = array(
		'PaymentPlan' => array(
			'className' => 'PaymentPlan',
			'foreignKey' => false,
			'dependent' => false,
			'conditions' => array(
					'PaymentPlan.account_id = PayplanLedger.account',
					'PaymentPlan.esp = PayplanLedger.esp'),
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