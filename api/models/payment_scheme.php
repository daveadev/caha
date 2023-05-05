<?php
class PaymentScheme extends AppModel {
	var $name = 'PaymentScheme';
	var $order = 'order';
	var $useDbConfig = 'sem';
	
	
	var $belongsTo = array(
		'Tuition' => array(
			'className' => 'Tuition',
			'foreignKey' => 'tuition_id',
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
	
	var $hasMany = array(
		'PaymentSchemeSchedule' => array(
			'className' => 'PaymentSchemeSchedule',
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
		),
	);
	function getPSDetail($tuid_id,$sch_id){
		$paySchObj = array();
		$cond = array('PaymentScheme.tuition_id'=>$tuid_id,'PaymentScheme.scheme_id'=>$sch_id);
		$paySchObj = $this->find('first',array('conditions'=>$cond));
		
		return $paySchObj;
	}


}