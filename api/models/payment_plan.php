<?php
class PaymentPlan extends AppModel {
	var $name = 'PaymentPlan';
	var $recursive = 1;
	var $consumableFields = array('label','id','account_id','guarantor','monthly_payments','total_due','terms');
	var $virtualFields = array('label'=>"CONCAT(PaymentPlan.guarantor,'/ Php ',PaymentPlan.total_due, ' for ', PaymentPlan.terms, ' mos.' )");
	var $hasMany = array(
		'PayPlanSchedule' => array(
			'className' => 'PayPlanSchedule',
			'foreignKey' => 'payment_plan_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array('id','pay_count','due_date','due_amount','paid_amount','status'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
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