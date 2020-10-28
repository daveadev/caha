<?php
class TransactionType extends AppModel {
	var $name = 'TransactionType';
	var $consumableFields = array('id','name','token','amount','amounts','description');
	var $virtualFields = array(
				'token'=>"MD5(GROUP_CONCAT(AccountSchedule.due_date,'/P',AccountSchedule.due_amount))",
				'amounts'=>"GROUP_CONCAT(AccountSchedule.due_date,'/P',AccountSchedule.due_amount-AccountSchedule.paid_amount)",
				'description'=>"GROUP_CONCAT(AccountSchedule.bill_month)",
				'amount'=> "SUM(
						IF(
						AccountSchedule.transaction_type_id='INIPY'
						AND AccountSchedule.order =1,
						AccountSchedule.due_amount-AccountSchedule.paid_amount,
							IF (
								AccountSchedule.transaction_type_id='SBQPY'
								AND AccountSchedule.order >1,
								AccountSchedule.due_amount-AccountSchedule.paid_amount,0
							)
						)
				)"
				);
	//var $useDbConfig = 'sfm';
	var $actsAs = array('Containable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	
	
	var $hasMany = array(
		'AccountSchedule' => array(
			'className' => 'AccountSchedule',
			'foreignKey' => 'transaction_type_id',
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
		return $this->sanitizeQuery($queryData);
	}
	function preparePagination($pagination){
		$queryData = $pagination['TransactionType'];
		return array('TransactionType'=>$this->sanitizeQuery($queryData));
	}
	protected function sanitizeQuery($queryData){
		$delimiter = null;

		if(isset($_GET['account_no']))
			$delimiter = $_GET['account_no'];
		if(isset($queryData['conditions'][0])){
			foreach($queryData['conditions'] as $i=>$condition){
				foreach($condition as $cond=>$value){
					if(preg_match('/account_no/',$cond)){
						unset($queryData['conditions'][$i][$cond]);
					}
				}
			}
		}
		
		//Look up for the Account by Student ID
		//$this->AccountTransaction->Account->recursive=-1;
		//$AccountInfo = $this->AccountTransaction->Account->findByStudentId($delimiter);

		
		if(isset($queryData['conditions'][0])):
			$transacDate = date('Y-m-d',time());
			
			$queryData['joins']= array(
						
					array(
		                    'table' => 'account_schedules', // or products_categories
		                    'alias' => 'AccountSchedule',
		                    'type' => 'LEFT',
		                    'conditions' => array(
		                        'AccountSchedule.account_id '=>$delimiter,
		                        'AccountSchedule.transaction_type_id = TransactionType.id',
		                        'AccountSchedule.status !='=> 'PAID',
								'AccountSchedule.due_date <='=> $transacDate
		                    )
		                ),
	                );
			$queryData['group'] = array('TransactionType.id');
			$conditions= array('OR'=>array(
					array('TransactionType.type'=>'active'),
					array('TransactionType.type'=>'reactive','AccountSchedule.id'),
					array('TransactionType.type'=>'passive','AccountSchedule.id'=>null)
					));

			array_push($queryData['conditions'],$conditions);
			$queryData['order']=array('AccountSchedule.id'=>'desc','AccountSchedule.order'=>'asc');
		else:
			$this->virtualFields['token'] ='null';
			$this->virtualFields['amount'] =0;
			$this->virtualFields['amounts'] ='null';
		endif;
		return $queryData;
	}

}
