<?php
class StudentAccountCollection extends AppModel {
	var $name = 'StudentAccountCollection';
	var $useTable = 'accounts';
	var $actsAs = array('Containable');
	var $recursive = 1;
	
	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => array(
				'Student.id',
				'Student.sno',
				'Student.gender',
				'Student.short_name',
				'Student.full_name',
				'Student.class_name',
				'Student.status',
				'Student.year_level_id',
				'Student.section_id',
			),
			'order' => ''
		)
		
	);
	
	var $hasMany = array(
		'AccountSchedule' => array(
			'className' => 'AccountSchedule',
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
		'Ledger' => array(
			'className' => 'Ledger',
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
	function beforeFind($queryData){
		//pr($queryData);
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				if($i=='OR'){
					$name = $cond['StudentAccountCollection.first_name LIKE'];
					unset($conds[$i]);
					unset($cond[$i]);
					$studs = $this->Student->findByName($name);
					$studs = array_keys($studs);
					$cond = array('StudentAccountCollection.account_id'=>$studs);
					//pr($studs); exit();
				}
				$key = 'StudentAccountCollection.account_id';
				$id = $cond[$key];
				if(isset($cond[$key])){
					unset($cond[$key]);
					$cond = array('id'=>$id);
				}
				
				
				$conds[$i]=$cond;
			}
			//$conds = array('id'=>$id);
			
			$queryData['conditions']=$conds;
		}
		//$order = array("FIELD('G7','G8','G9','GX','GY','GZ')");
		//$queryData['order']=$order;
		array_push($queryData['conditions'],array('account_type'=>'student','assessment_total !='=>0));
		//pr($queryData); exit();
		return $queryData;
	} 
	
	
}
