<?php
class Assessment extends AppModel {
	var $name = 'Assessment';
	var $actsAs ='Containable';
	var $useDbConfig = 'sem';
	var $recursive = 2;
	var $belongsTo = array(
		'Inquiry' => array(
			'className' => 'Inquiry',
			'foreignKey' => false,
			'dependent' => false,
			'conditions' => array('Inquiry.id=Assessment.student_id'),
			'fields' =>array('id','first_name','middle_name','last_name','year_level_id'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array(
				'Student.sno',
				'Student.gender',
				'Student.short_name',
				'Student.full_name',
				'Student.class_name',
				'Student.status',
				'Student.year_level_id',
				'Student.section_id',
				'Student.program_id',
			),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		
	);
	
	var $hasMany = array(
		'AssessmentPaysched' => array(
			'className' => 'AssessmentPaysched',
			'foreignKey' => 'assessment_id',
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
		'AssessmentFee' => array(
			'className' => 'AssessmentFee',
			'foreignKey' => 'assessment_id',
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
		'AssessmentSubject' => array(
			'className' => 'AssessmentSubject',
			'foreignKey' => 'assessment_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
	function getDetails($sid,$esp){
		$assCond = array(
			'student_id'=>$sid,
			'esp'=>$esp,
			'status'=>'ACTIV'
		);
		$assConf = array(
				'recursive'=>1,
				'conditions'=>$assCond
		);
		$AObj = $this->find('first',$assConf);
		
		$this->Student->Section->recursive=-1;
		$sectId = $AObj['Assessment']['section_id'];
		$AObj['Section'] = $this->Student->Section->findById($sectId)['Section'];
		return $AObj;
	}
}