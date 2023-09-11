<?php
class PaymentPlan extends AppModel {
	var $name = 'PaymentPlan';
	var $hasMany = array(
		'PayPlanSchedule' => array(
			'className' => 'PayPlanSchedule',
			'foreignKey' => 'payment_plan_id',
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