<?php
class Ledger extends AppModel {
	var $name = 'Ledger';
	//var $useDbConfig = 'sfm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'account_id',
			'dependent' => false,
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
				'Student.last_name',
				'Student.first_name',
				'Student.middle_name',
			),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Inquiry' => array(
			'className' => 'Inquiry',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => array('Inquiry.full_name'),
			'order' => ''
		),
	);
	
	function beforeFind($queryData){
		//pr($queryData); exit();
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				if(!is_array($cond))
					break;
				$keys =  array_keys($cond);
				$search = 'Ledger.rect';
				
				if(in_array($search,$keys)){
					$cond['Ledger.ref_no LIKE']='OR %';
					unset($cond[$search]);
				}
				$conds[$i]=$cond;
				//pr($conds);
			}
			
			$queryData['conditions']=$conds;
		}
		
		return $queryData;
	}
}
