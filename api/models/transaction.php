<?php
class Transaction extends AppModel {
	var $name = 'Transaction';
	var $actsAs = array('Containable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => array('Account.id','Account.account_type','account_details'),
			'order' => ''
		),
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
		'Inquiry' => array(
			'className' => 'Inquiry',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => array('Inquiry.full_name'),
			'order' => ''
		),
		'Booklet' => array(
			'className' => 'Booklet',
			'foreignKey' => 'booklet_id',
			'conditions' => '',
			'fields' => '',
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
		),
		'TransactionPayment' => array(
			'className' => 'TransactionPayment',
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
		if(!isset($queryData['conditions']['Transaction.id'])){
			
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				//$url = 'Transaction.url_from';
				if($cond==='%OR%'){
					$receipts=true;
				}
				
			}
			
			if(!isset($receipts)){
				foreach($conds as $i=>$cond){
					//$type = 'Transaction.type';
					$from = 'Transaction.from';
					$to = 'Transaction.to';
					$type = 'Transaction.type';
					$date = 'Transaction.date';
					
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
				
			$conds = array('OR'=>array('Transaction.ref_no LIKE'=> '%'.$typ.'%'),'and'=>array('Transaction.transac_date <='=>$end,'Transaction.transac_date >='=>$start));
				
			}
			//pr($conds); exit();
			$queryData['conditions']=$conds;
		}
		//pr($queryData); exit();
		return $queryData;
	}
	}
	
	
}
