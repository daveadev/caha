<?php
class Tuition extends AppModel {
	var $name = 'Tuition';
	var $useDbConfig = 'sem';
	var $displayField = 'name';
	var $recursive = 2;
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	
	var $hasMany = array(
		
		'PaymentScheme' => array(
			'className' => 'PaymentScheme',
			'foreignKey' => 'tuition_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'order',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		
	);
	
	function getTuiDetail($sy,$yl,$amt){
		$tuitionObj = array('assessment_total'=>0);
		$cond = array('Tuition.sy'=>$sy,'Tuition.year_level_id'=>$yl, 'Tuition.assessment_total'=>$amt);
		$this->recursive = 0;
		$tui = $this->find('first',array('conditions'=>$cond));
		if($tui)
			$tuitionObj = $tui['Tuition'];
		return $tuitionObj;
	}

}
