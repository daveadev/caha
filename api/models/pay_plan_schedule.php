<?php
class PayPlanSchedule extends AppModel {
	var $name = 'PayPlanSchedule';

	var $belongsTo = array(
		'PaymentPlan' => array(
			'className' => 'PaymentPlan',
			'foreignKey' => 'id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
}