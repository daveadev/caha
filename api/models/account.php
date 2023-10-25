<?php
class Account extends AppModel {
	var $name = 'Account';
	//var $useDbConfig = 'sfm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'id',
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
		'Inquiry' => array(
			'className' => 'Inquiry',
			'foreignKey' => 'id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array(
				'Inquiry.id',
				'Inquiry.gender',
				'Inquiry.short_name',
				'Inquiry.full_name',
				'Inquiry.first_name',
				'Inquiry.last_name',
				'Inquiry.class_name',
				'Inquiry.year_level_id',
				'Inquiry.program_id'
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
		'AccountAdjustment' => array(
			'className' => 'AccountAdjustment',
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
		'AccountFee' => array(
			'className' => 'AccountFee',
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
		'AccountHistory' => array(
			'className' => 'AccountHistory',
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
		'AccountTransaction' => array(
			'className' => 'AccountTransaction',
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
		)
	);
	function beforeFind($queryData){
		//pr($queryData); exit();
		if($conds=$queryData['conditions']){
			foreach($conds as $i=>$cond){
				if(!is_array($cond))
					break;
				$keys =  array_keys($cond);
				
				$search = ['Account.name LIKE','Account.first_name LIKE','Account.middle_name','Account.last_name'];
			
				if(in_array($search[1],$keys)){
					$val = array_values($cond);
					$account_type = 'student';
					if(isset($_GET['account_type'])){
						$account_type = $_GET['account_type'];
					}
					if($account_type=='student')
						$students = $this->Student->findByName($val[1]);
					else if($account_type=='inquiry')
						$students = $this->Inquiry->findByName($val[1]);
					$student_ids= array_keys($students);
					unset($cond['Account.first_name LIKE']);
					unset($cond['Account.middle_name LIKE']);
					unset($cond['Account.last_name LIKE']);
					unset($cond['Account.name LIKE']);
					unset($cond['Account.id LIKE']);
					$cond['Account.id']=$student_ids;
				}
				
				$conds[$i]=$cond;
			}
			//pr($conds);exit();
			$queryData['conditions']=$conds;
		}
		
		return $queryData;
	}

	function postTransaction($account_id, $trnx){
		$A = $this->findById($account_id);
		// Update account balances and totals
		$ACC = $A['Account'];
		if($trnx['flag']=='-'):
			$amount =  $trnx['amount'];
			$ACC['outstanding_balance'] -= $amount; 
			$ACC['payment_total'] += $amount; 
			
			// Account
			$ACD = array();
			$ACD['id'] =  $ACC['id'];
			$ACD['outstanding_balance'] =  $ACC['outstanding_balance'];
			$ACD['payment_total'] =  $ACC['payment_total'];
			$this->save($ACD);

			// Account History
			$ACH = array();
			$ACH['account_id'] = $ACC['id'];
			$ACH['total_due'] =  $ACC['assessment_total'];
			$ACH['total_paid'] =  $ACC['payment_total'];
			$ACH['balance'] =  $ACC['outstanding_balance'];
			$ACH['transac_date'] =  $trnx['transac_date'];
			$ACH['transac_time'] =  date('h:i:s',time());
			$ACH['amount'] =  $amount;
			$ACH['ref_no'] =  $trnx['ref_no'];
			$ACH['details'] =  $trnx['details'];
			$ACH['flag'] =  $trnx['flag'];
			$this->AccountHistory->save($ACH);

			// Account Fees
			$ACF = array();
			foreach($A['AccountFee'] as $AF):
				if($AF['fee_id']=='TUI'):
					$AF['paid_amount'] = $ACC['payment_total'];
					$this->AccountFee->save($AF);
				endif;
			endforeach;

			// Account Transaction
			$ACT = array();
			$ACT['account_id']= $ACC['id'];
			$ACT['transaction_type_id']= $trnx['transaction_type_id'];
			$ACT['ref_no']= $trnx['ref_no'];
			$ACT['amount']= $trnx['amount'];
			$this->AccountTransaction->save($ACT);
		endif;
	}

	function udpateEspSource($sy){
		$this->setSource('accounts_'.$sy);
		$this->AccountSchedule->setSource('account_schedules_'.$sy);
	}
	protected function lookupAmount($obj,$key="amount"){
		$amount = $obj[$key];
		if(!is_numeric($amount)){
			$amount = floatval(str_replace(",", "", $amount));
		}
		return $amount;
	}
	protected function getEndBal($arr,$key){
		$amount=0;
		$lastItem = $this->getEndItem($arr);
		if($key=='paysched'):
			$amount = $this->lookupAmount($lastItem,'total_bal');
		elseif($key=='ledger'):
			$amount = $this->lookupAmount($lastItem,'bal');
		endif;

		return $amount;
	}
	protected function getEndItem(&$arr){
		return $arr[count($arr)-1];
	}
	function review($statement,$type="current"){
		$isValid = true;
		$PS = $statement['paysched_'.$type];
		$PSLast = $PS[count($PS)-1];
		$PSEndBal = $this->getEndBal($PS,'paysched');

		$LE = $statement['ledger_'.$type]; 
		$LEEndBal =$this->getEndBal($LE,'ledger');

		$isValid = $LEEndBal==$PSEndBal;
		$INIPY_0 = $PS[0]['status']!='PAID';
		$SBQPY_1 = floatval(str_replace(",", "", $PS[1]['paid_amount']))>0; 
		$isValid = $isValid && !($INIPY_0 && $SBQPY_1);
		
		return $isValid;
	}

	function ammend(&$statement,$type="current"){
		$ammended = array('corrected'=>false);
		$TUI = 0;
		$MOD = 0;
		$DSC = 0;
		$LOY = 0;
		$OLD = 0;
		$VOU = 0;
		$OR_PAY = 0;
		$LE = $statement['ledger_'.$type]; 
		$TUIIndex =null;
		foreach($LE as $index=>$entry):
			$details = $entry['details'];
			$code = $entry['transaction_type_id'];
			
			switch($code){
				case 'TUIXN':
					$TUI = $this->lookupAmount($entry);
					$TUIIndex = $index;
				break;
				case 'MODUL':
					$MOD = $this->lookupAmount($entry);
				break;
				case 'LYLTY':
					$LOY = $this->lookupAmount($entry);
				break;
				case 'OLDAC':
					$old_amt = $this->lookupAmount($entry);
					if($entry['type']=='-')
						$old_amt *=-1;
					$OLD += $old_amt;
					
				break;
				case 'INIPY': case 'SBQPY':
					$OR_PAY += $this->lookupAmount($entry);
				break;
				default:
					if($details=='Subsidy' || $code=='DSESC'):
						$DSC = $this->lookupAmount($entry);
					endif;
					if($details=='FAV'  || $code =='AMFAV'):
						$VOU += $this->lookupAmount($entry);
					endif;

				break;
			}
		endforeach;

		$PS = $statement['paysched_'.$type];
		$PSEndBal = $this->getEndBal($PS,'paysched');
		$PSTotals= &$this->getEndItem($PS);
		$PSTotalDue = $this->lookupAmount($PSTotals,'total_due');

		$LE = &$statement['ledger_'.$type];
		$LEFees = $TUI + $MOD ;
		$LEPays = $DSC + $LOY  ;
		$LETotalDue = $LEFees - $LEPays;
		
		
		$Variance = $PSTotalDue -  $LETotalDue;

		if($Variance!=0 ):
			App::import('Model','PaymentPlan');
			$PP = new PaymentPlan();
			$isPSExccess = $PSTotalDue >$LETotalDue;
			$UPON_ENROL = $this->lookupAmount($PS[0],'due_amount');
			$TOT_SBQPY =  $LETotalDue -$UPON_ENROL;
			if($isPSExccess):
				// Make correction for Loyal Discount or Modules
				$isLOYDiff = $LOY>0  &&  $Variance==$LOY;
				$isMODiff = $MOD==0  && $Variance ==4950 ;
				
				// Loyalty Discounts Correction
				if($isLOYDiff):
					$TUIObj = &$LE[$TUIIndex];
					$TUIObj['amount']+=$LOY;
					$this->Ledger->updateEntry($TUIObj);
					$PP->recomputeLedger($LE);
					$ammended['corrected']=true;
				endif;

				// Modules & eBook correction
				if($isMODiff):
					$TOT_SBQPY =  $PSTotalDue -$UPON_ENROL - $Variance;
				endif;
			endif;
			$PSAdj = array();
			$PSAdj['amount'] = $TOT_SBQPY;
			$PSAdj['apply_to'] = 'SBQPY';
			$PP->recomputePaysched($PS,$PSAdj);
			$PS=array_values($PS);
			$statement['paysched_'.$type] = $PS;
			$this->AccountSchedule->updateSchedule($PS);
			$ammended['corrected']=true;
		endif;
		if($OLD):
			App::import('Model','PaymentPlan');
			$PP = new PaymentPlan();
			$PSAdj = array();
			$PSAdj['amount'] =  $OLD;
			$PSAdj['apply_to'] = 'OLDAC';
			$PP->recomputePaysched($PS,$PSAdj);
			$PS = array_values($PS);
			$statement['paysched_'.$type] = $PS;
			$ammended['corrected']=true;
		endif;


		if($VOU):

			App::import('Model','PaymentPlan');
			$PP = new PaymentPlan();
			$PSAdj = array();
			$PSAdj['amount'] =  $VOU +$OR_PAY;
			$PSAdj['apply_to'] = 'AMFAV';
			$PP->recomputePaysched($PS,$PSAdj);
			$PS = array_values($PS);
			$statement['paysched_'.$type] = $PS;
			$this->AccountSchedule->updateSchedule($PS);
			$ammended['corrected']=true;

		endif;
		
		if($OR_PAY && $Variance==0 && !$VOU):
			App::import('Model','PaymentPlan');
				$PP = new PaymentPlan();
			$PSEndBal = $this->getEndBal($PS,'paysched');
			$PSTotals= &$this->getEndItem($PS);
			$PSTotalDue = $this->lookupAmount($PSTotals,'total_due');

			$LEEEndBal = $this->getEndBal($LE,'ledger');
			$LETotals= &$this->getEndItem($LE);

			$LETotalDue = $this->lookupAmount($LETotals,'bal');
		
			if($LETotalDue!=$PSTotalDue):
				$PSAdj = array();
				$PSAdj['amount'] =  $VOU +$OR_PAY;
				$PSAdj['apply_to'] = 'AMFAV';
				$PP->recomputePaysched($PS,$PSAdj);
				$PSAdj['apply_to'] = 'AMFAV';
				$PP->recomputePaysched($PS,$PSAdj);
				$PS = array_values($PS);
				$statement['paysched_'.$type] = $PS;
				$ammended['corrected']=true;
			endif;
		endif;
		
		return $ammended;
	}
}
