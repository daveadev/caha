<?php
class CashierCollection extends AppModel {
	var $name = 'CashierCollection';
	var $useTable = 'transactions';
	var $order = "CAST(REGEXP_REPLACE(CashierCollection.ref_no,'OR|XOR|AR','') AS UNSIGNED ) ASC, CashierCollection.id";
	var $recursive = 1;
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
				'Account.account_type',
				'Account.discount_amount',
				'Account.assessment_total',
				'Account.payment_total',
				'Account.outstanding_balance',
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
			'fields' =>array('Booklet.id','Booklet.booklet_number'),
			'order' => ''
		),
		'AccountHistory' => array(
			'className' => 'AccountHistory',
			'foreignKey' => '',
			'dependent' => false,
			'conditions'=>array('AccountHistory.ref_no = CashierCollection.ref_no'),
			'fields' => array(
								'AccountHistory.total_due',
								'AccountHistory.total_paid',
								'AccountHistory.balance',
								'AccountHistory.amount'),
			'order' => ''
		),
	);
	
	var $hasMany = array(
		'TransactionDetail' => array(
			'className' => 'TransactionDetail',
			'foreignKey' => 'transaction_id',
			'dependent' => false,
			'fields' => '',
			'order' => ''
		),
		'TransactionPayment' => array(
			'className' => 'TransactionPayment',
			'foreignKey' => 'transaction_id',
			'dependent' => false,
			'fields' => '',
			'order' => ''
		),
		
	);
	
	/*  function __construct($table){

	 	if($_GET['type']!=='OR')
			$this->useTable = 'transactions';
		parent::__construct();
	} */
	
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
				$conds = array('CashierCollection.ref_no LIKE'=>'%'. $typ.'%','and'=>array('CashierCollection.transac_date <='=>$end,'CashierCollection.transac_date >='=>$start));
			else
				$conds = array('CashierCollection.ref_no LIKE'=> '%'.$typ.'%','CashierCollection.transac_date'=>$dates);
			
			$queryData['conditions']=$conds;
		}
		//pr($queryData); exit();
		return $queryData;
	}
}
