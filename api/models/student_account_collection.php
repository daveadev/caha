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
	function beforeFind($queryData){
		//pr($queryData);
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				$key = 'StudentAccountCollection.account_id';
				$id = $cond[$key];
				//pr($id);
				if(isset($cond[$key])){
					unset($cond[$key]);
					$cond = array('id'=>$id);
				}
				$conds[$i]=$cond;
			}
			//$conds = array('id'=>$id);
			
			$queryData['conditions']=$conds;
		}
		$order = array("FIELD('G7','G8','G9','GX','GY','GZ')");
		$queryData['order']=$order;
		//pr($queryData); //exit();
		return $queryData;
	} 
	
	
}
