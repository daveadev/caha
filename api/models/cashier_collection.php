<?php
class CashierCollection extends AppModel {
	var $name = 'CashierCollection';
	var $useTable = 'transactions';
	var $order = 'transac_date,ref_no asc';
	var $recursive = 2;
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
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => array(
				'Account.id',
				'Account.subsidy_status',
			),
			'order' => ''
		),
	);
	var $hasMany = array(
		'TransactionDetail' => array(
			'className' => 'TransactionDetail',
			'foreignKey' => 'transaction_id',
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
	
	function beforeFind($queryData){
		//pr($queryData); exit();
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				//$type = 'CashierCollection.type';
				$from = 'CashierCollection.from';
				$to = 'CashierCollection.to';
				$type = 'CashierCollection.type';
				$date = 'CashierCollection.date';
				if(isset($cond[$from])){
					$start =$cond[$from];
					unset($cond[$from]);
				}
				if(isset($cond[$to])){
					$end = $cond[$to];
					unset($cond[$to]);
				}
				if(isset($cond[$type])){
					$typ = $cond[$type];
					unset($cond[$type]);
				}
				if(isset($cond[$date])){
					$dates = $cond[$date];
					unset($cond[$date]);
				}
			}
			if(!isset($dates))
				$conds = array('CashierCollection.ref_no LIKE'=> $typ.'%','and'=>array('transac_date <='=>$end,'transac_date >='=>$start));
			else
				$conds = array('CashierCollection.ref_no LIKE'=> $typ.'%','transac_date'=>$dates);
			
			$queryData['conditions']=$conds;
		}
		//pr($queryData); exit();
		return $queryData;
	} 
}
