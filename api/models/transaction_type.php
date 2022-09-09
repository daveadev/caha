<?php
class TransactionType extends AppModel {
	var $name = 'TransactionType';
	var $consumableFields = array('id','name','token','amount','amounts','description','type','is_quantity','is_specify');
	var $virtualFields = array(
				'token'=>"MD5(GROUP_CONCAT(AccountSchedule.due_date,'/',AccountSchedule.due_amount))",
				'amounts'=>"GROUP_CONCAT(AccountSchedule.due_date,'/',AccountSchedule.due_amount-AccountSchedule.paid_amount ORDER BY AccountSchedule.order)",
				'description'=>"GROUP_CONCAT(AccountSchedule.bill_month ORDER BY AccountSchedule.order)",
				'amount'=> "SUM(
						IF(
						AccountSchedule.transaction_type_id='INIPY',
						AccountSchedule.due_amount-AccountSchedule.paid_amount,TransactionType.default_amount
						)
				)",
				
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
		'AssessmentPaysched' => array(
			'className' => 'AssessmentPaysched',
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
		//pr($queryData); exit();
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
		                        'AccountSchedule.status !='=> 'PAID'
								//'AccountSchedule.due_date <='=> $transacDate
		                    )
		                ),
				/* 	array(
		                    'table' => 'accounts',
		                    'alias' => 'Account',
		                    'type' => 'INNER',
		                    'conditions' => array(
		                        'Account.id '=>$delimiter,
		                    )
		                ), */
						
	                );
			$queryData['group'] = array('TransactionType.id');
			
			
			$ASM = $this->AssessmentPaysched->Assessment;
			$ASM->recursive=-1;
			//$assessment = $ASM->findByStudentId($delimiter);
			$assessment = null;
			$ASMCond =  array('student_id'=>$delimiter, 'status'=>'ACTIV');
			//$assessment = $ASM->find('first',array('conditions'=>$ASMCond));
			App::import('Model','Account');
			$ACC  =  new Account();
			$A = $ACC->findById($delimiter);
			if($A['Account']['outstanding_balance']<=0):
				$assessment = $ASM->find('first',array('conditions'=>$ASMCond));
			endif;

			//Check if assessment is available
			if($assessment):
				$queryData['joins']=array();
				$AID =  $assessment['Assessment']['id'];
				$this->hasMany['AssessmentPaysched']['conditions'] = array('AssessmentPaysched.assessment_id'=>$AID);
				$SEM_DB = $ASM->getDataSource()->config['database'];
				
				// Join assessments and assessment_payscheds from SEM dbConfig
				$JASM = array(
		                    'table' => $SEM_DB.'.assessments', 
		                    'alias' => 'Assessment',
		                    'type' => 'LEFT',
		                    'conditions' => array(
		                        'Assessment.id '=>$AID
		                    )
		                );
						
				array_push($queryData['joins'],$JASM);
				$JASP = array(
		                    'table' => $SEM_DB.'.assessment_payscheds', 
		                    'alias' => 'AssessmentPaysched',
		                    'type' => 'LEFT',
		                    'conditions' => array(
		                        'AssessmentPaysched.assessment_id '=>$AID,
		                        'AssessmentPaysched.transaction_type_id = TransactionType.id',
		                        'AssessmentPaysched.status !='=> 'PAID'
		                    )
		                );
				array_push($queryData['joins'],$JASP);
				
				// Update virtual fields to display IP and Full Payment 
				$VFLDS = array(
					'token'=>"
					CASE `TransactionType`.`id`  WHEN 'FULLP' THEN
					MD5(CONCAT(Assessment.created,'/',Assessment.outstanding_balance) )
					ELSE
					MD5(GROUP_CONCAT(AssessmentPaysched.due_date,'/',AssessmentPaysched.due_amount))END",
					'amounts'=>"CASE `TransactionType`.`id`  WHEN 'FULLP' THEN CONCAT(Assessment.created,'/',Assessment.outstanding_balance) 
					ELSE 
					GROUP_CONCAT(AssessmentPaysched.due_date,'/',AssessmentPaysched.due_amount ORDER BY AssessmentPaysched.order) END ",
					'description'=>"CASE `TransactionType`.`id`  WHEN 'FULLP' THEN 'Total Assessment' ELSE   GROUP_CONCAT(AssessmentPaysched.bill_month ORDER BY AssessmentPaysched.order) END ",
					'amount'=> " SUM(
						  IF(
							`AssessmentPaysched`.`transaction_type_id` = 'INIPY' ,
							`AssessmentPaysched`.`due_amount`,
							IF (
							  `AssessmentPaysched`.`transaction_type_id` = 'SBQPY' ,
							  `AssessmentPaysched`.`due_amount` ,
							 IF (
							  `TransactionType`.`id` = 'FULLP' ,
							  `Assessment`.`outstanding_balance` ,
							  `TransactionType`.`default_amount` 
							)
							)
						  )
					)"
					);
					$this->virtualFields =  $VFLDS;
			else:
				
			endif;
			
			$conditions= array('OR'=>array(
					array('TransactionType.type'=>'active'),
					array('TransactionType.type'=>'reactive','AccountSchedule.id'),
					array('TransactionType.type'=>'passive','AccountSchedule.id'=>null,'TransactionType.id !='=>'FULLP')
					));
			if($assessment):
				// Filter transactions IP, Full and Old Accounts only
				$conditions= array('OR'=>array(
					array('TransactionType.type'=>'active'),
					array('TransactionType.type'=>'reactive','AssessmentPaysched.id','TransactionType.id'=>'INIPY'),
					array('TransactionType.type'=>'passive','AssessmentPaysched.id'=>null)
					));
					
			endif;
			//pr(array_keys($queryData['conditions'][0])); exit();
			$key = array_keys($queryData['conditions'][0]);
			if(in_array('TransactionType.type',$key))
				$conditions = array('TransactionType.type'=>'AR');
			array_push($queryData['conditions'],$conditions);
			$queryData['order']=array('AccountSchedule.id'=>'desc','AccountSchedule.order'=>'asc');
			if($assessment):
				// Adjust order 
				$queryData['order']=array('AssessmentPaysched.id'=>'desc','AssessmentPaysched.order'=>'asc');
			endif;
		else:
			$this->virtualFields['token'] ='null';
			$this->virtualFields['amount'] =0;
			$this->virtualFields['amounts'] ='null';
		endif;
		return $queryData;
	}

}