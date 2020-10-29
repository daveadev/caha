<?php
class CashierCollection extends AppModel {
	var $name = 'CashierCollection';
	var $useTable = 'account_histories';
	var $actsAs = array('Containable');
	
	
	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'account_id',
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
			),
			'order' => ''
		),
		
	);
	
	function beforeFind($queryData){
		
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				//$type = 'CashierCollection.type';
				$from = 'CashierCollection.from';
				$to = 'CashierCollection.to';
				
				if(isset($cond[$from])){
					$start =$cond[$from];
					unset($cond[$from]);
				}
				if(isset($cond[$to])){
					$end = $cond[$to];
					unset($cond[$to]);
				}
			}
			$conds = array('details LIKE'=>'% Payment','flag'=>'-','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			$queryData['conditions']=$conds;
		}
		//pr($queryData); exit();
		return $queryData;
	} 
}
