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
			'order' => array('AccountSchedule.order'=>'ASC','AccountSchedule.transaction_type_id'=>'DESC'),
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
				
				$search = ['Account.name LIKE','Account.first_name LIKE','Account.middle_name','Account.last_name','Account.refresh'];
			
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
				if(in_array($search[4],$keys)):
					unset($cond['Account.refresh']);
				endif;
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

	function generateId($prefix){
		$lastAccount = $this->find('first', array(
			'recursive'=>-1,
		    'conditions' => array('Account.id LIKE'=>$prefix.'%'),
		    'fields' => array('id'),
		    'order' => array('id' => 'desc')
		));

		// Extract the ID and remove the "LSO" prefix
		$lastIdStr = $lastAccount['Account']['id']; 
		$numericPart = substr($lastIdStr, 3); // Remove the "LSO" prefix

		// Convert the numeric part to an integer and increment it
		$numericPartInt = (int)$numericPart;
		$newIdInt = $numericPartInt + 1;

		// Pad the new ID and prepend "LSO"
		$newIdStr = str_pad($newIdInt, 5, '0', STR_PAD_LEFT);
		$newAccountId = $prefix . $newIdStr;
		return $newAccountId;
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
		$hasItem = count($arr)>0;
		if($hasItem):
			$lastItem = $this->getEndItem($arr);
			if($key=='paysched'):
				$amount = $this->lookupAmount($lastItem,'total_bal');
			elseif($key=='ledger'):
				$amount = $this->lookupAmount($lastItem,'bal');
			endif;
		endif;
		return $amount;
	}
	protected function getEndItem(&$arr){
		return $arr[count($arr)-1];
	}
	function review($statement,$type="current"){
		$isValid = true;
		$PS = $statement['paysched_'.$type];
		$PSLen = count($PS);
		$PSEndBal =0;
		if($PSLen>0):
			$PSLast = $PS[count($PS)-1];
			$PSEndBal = $this->getEndBal($PS,'paysched');
		endif;
		$LE = $statement['ledger_'.$type]; 
		$LELen = count($LE);
		$LEEndBal =0;
		if($LELen>0):
			$LEEndBal =$this->getEndBal($LE,'ledger');
		endif;
		
		$isValid = $LEEndBal==$PSEndBal;
		
		if($PSLen>0 && $PSEndBal>0):
			$INIPY_0 = $PS[0]['status']!='PAID';
			$SBQPY_1 =0;
			if(isset($PS[1]['paid_amount'])):
				$SBQPY_1 = floatval(str_replace(",", "", $PS[1]['paid_amount']))>0; 
			endif;
			$isValid = $isValid && !($INIPY_0 && $SBQPY_1);
		endif;

		return $isValid;
		
	}

	function ammend(&$statement,$type="current"){
		$ammended = array('corrected'=>false);
		$TUI = 0;
		$MOD = 0;
		$DSC = 0;
		$LOY = 0;
		$OLD = 0;
		$ACEC = 0;
		$VOU = 0;
		$OR_PAY = 0;
		$LE = $statement['ledger_'.$type]; 
		$TUIIndex =null;
		$voucher = array(
				'codes'=>array('AMFAV','AMSPO','AMOTF','EDV','AMTMC'),
				'details'=>array('FAV','SPV','AMEMD','TBD'),
			);
		foreach($LE as $index=>$entry):
			$details = $entry['details'];
			$code = $entry['transaction_type_id'];
			
			switch($code){
				case 'TUIXN':
					$TUI = $this->lookupAmount($entry);
					$TUIIndex = $index;
				break;
				case 'MODUL':
					$mod_amt = $this->lookupAmount($entry);
					if($entry['type']=='-')
						$mod_amt *=-1;
					$MOD += $mod_amt;
				break;
				case 'LYLTY':
					$LOY = $this->lookupAmount($entry);
				break;
				case 'OLDAC': case 'AMPEC':
					$old_amt = $this->lookupAmount($entry);
					if($entry['type']=='-')
						$old_amt *=-1;
					$OLD += $old_amt;
					
				break;
				case 'INIPY': case 'SBQPY': case 'FULLP':
					$payment = $this->lookupAmount($entry);
					if($entry['type']=='+')
						$payment *=-1;
					$OR_PAY += $payment;
				break;
				// Late & Regular ESC Voucher
				case  'AMLES': case 'AMRES':
					$VOU += $this->lookupAmount($entry);
				break;
				case 'ACECF':
					$ACEC += $this->lookupAmount($entry);
				break;
				default:
					if($details=='Subsidy' || $code=='DSESC'):
						$DSC = $this->lookupAmount($entry);
					endif;
					$isVCode = in_array($code, $voucher['codes']);
					$isVDetail = in_array($details, $voucher['details']);
					if($isVCode || $isVDetail) :
						$VOU += $this->lookupAmount($entry);
					endif;

				break;
			}
		endforeach;

		$PS = $statement['paysched_'.$type];
		$PSEndBal = $this->getEndBal($PS,'paysched');
		$PSTotals= $this->getEndItem($PS);
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
			$hasLE = count($LE)>0;
			$UPON_ENROL = $this->lookupAmount($PS[0],'due_amount');
			$TOT_SBQPY =  $LETotalDue -$UPON_ENROL;
			if($isPSExccess):
				// Make correction for Loyal Discount or Modules
				$isLOYDiff = $LOY>0  &&  $Variance==$LOY;
				$isMODiff = $MOD==0  && $Variance ==4950 ;
				
				
				// Loyalty Discounts Correction
				if($isLOYDiff):
					$TUIObj = &$LE[$TUIIndex];
					if($TUIObj['amount']!=39925){
						$TUIObj['amount']+=$LOY;
						$this->Ledger->updateEntry($TUIObj);
						$PP->recomputeLedger($LE);
						$ammended['corrected']=true;
					}
				endif;

				// Modules & eBook correction
				if($isMODiff):
					$TOT_SBQPY =  $PSTotalDue -$UPON_ENROL - $Variance;
				endif;

				if(!$hasLE):
					$_AID =  $statement['account']['id'];
					$_ESP =  floor($statement['account']['esp']);
					$EPP_REFNO =  $this->Ledger->generateREFNO($_ESP,'EPP');
					$_EPP_AMOUNT  = $PSTotalDue;
					
					$_EPP_date = date('Y-m-d', strtotime($PS[0]['due_date']));
					$_EPP_time = '01:23:45';
					
					$EPPObj = array(
						'ref_no'=>$EPP_REFNO,
						'account_id'=>$_AID,
						'type'=>'+',
						'transaction_type_id'=>'EXTPY',
						'details'=>'Ext. Payment Plan',
						'amount'=>$_EPP_AMOUNT,
						'esp'=>$_ESP,
						'transac_date'=>$_EPP_date,
						'transac_time'=>$_EPP_time,
						'notes'=>'LE00_ERR Correction',
					);
					$LE = array($EPPObj);
					$this->Ledger->insertEntry($EPPObj);
					$PP->recomputeLedger($LE);
					$statement['ledger_'.$type] = $LE;
					$ammended['corrected']=true;
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
			//pr($PSAdj);
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

	/**
	 * Forward a payment for a given account and ESP (Effective School Year Period).
	 *
	 * @param int $account_id The ID of the account.
	 * @param string $esp The Effective School Year Period.
	 * @param string $ref_no The reference number for the payment.
	 * @param float $amount The payment amount.
	 */
	function forwardPayment($account_id, $esp, $ref_no, $amount,$trnxObj,$source) {
	    // Find the account by account_id
	    $ACObj = $this->findById($account_id);

	    // Extract account information
	    $account = $ACObj['Account'];

	    // Update the account  with the payment amount
	    $this->updateAccount($account, $amount);

	    $accountInfo = array();
	    // Save balances in payment_plan and account if the account is valid
	    if ($account['is_valid']) {
	        $this->save($account);
	        
	        // Distribute payment and update the payment schedule
	        $sched = $ACObj['AccountSchedule'];
	        $this->distributePayments($sched, $amount);
			$this->logPaymentHistory($account,$ref_no,$amount,$trnxObj,$source);
	        $this->AccountSchedule->saveAll($sched);
	        $accountInfo['amount_paid'] = $amount; 
	        $accountInfo['total_payments'] = $account['payment_total']; 
	        $accountInfo['total_balance'] = $account['outstanding_balance']; 
	    }

	    return $accountInfo;
	}

	/**
	 * Update the account and payment plan based on the payment amount.
	 *
	 * @param array $account Reference to the account array.
	 * @param array $plan Reference to the payment plan array.
	 * @param float $amount The payment amount.
	 */
	protected function updateAccount(&$account, $amount) {
	    $assess_total = $account['assessment_total'];
	    $discounts = $account['discount_amount'];
	    $payments = $account['payment_total'];
	    $outstanding_bal = $account['outstanding_balance'];
		$net_amount = $assess_total + $discounts -  $payments;
	    $account['is_valid'] = $outstanding_bal == $net_amount ;

	    // If the account is valid, update the total payments and balance
	    if ($account['is_valid']) {
	        $account['payment_total'] += $amount;
	        $account['outstanding_balance'] = $net_amount-$amount;
	    }
	}

	protected function logPaymentHistory($account,$ref_no,$amount,$trnxObj,$source){
		$tDate =  explode('~',date('Y-m-d~h:i:s',time()));
		$history = array(
			'account_id'=>$account['id'],
			'total_due'=>$account['assessment_total'],
			'total_paid'=>$account['payment_total'],
			'balance'=>$account['outstanding_balance'],
			'ref_no'=>$ref_no,
			'flag'=>'-',
			'details'=>$trnxObj['name'],
			'amount'=>$amount,
			'transac_date'=>$tDate[0],
			'transac_time'=>$tDate[1],
		);
		$this->AccountHistory->save($history);

		$transaction = array(
			'account_id'=>$account['id'],
			'transaction_type_id'=>$trnxObj['id'],
			'ref_no'=>$ref_no,
			'amount'=>$amount,
			'source'=>$source
		);

		$this->AccountTransaction->save($transaction);		
	}

	/**
	 * Distribute the payment among the payment schedule based on the payment amount.
	 *
	 * @param array $schedule Reference to the payment schedule array.
	 * @param float $totalPayment The total payment amount to be distributed.
	 */
	protected function distributePayments(&$schedule, $totalPayment) {
	    foreach ($schedule as &$payment) {
	        // Check if the payment is unpaid and there's a remaining payment to be made
	        $isPAID = $payment['status'] === 'UNPAID' || $payment['status'] === 'NONE' || $payment['paid_amount']<$payment['due_amount'];
	        if ($isPAID && $totalPayment > 0) {
	            // Calculate the amount to be paid for this schedule
	            $amountToPay = min($totalPayment, $payment['due_amount'] - $payment['paid_amount']);

	            // Update paid amount and status
	            $payment['paid_amount'] += $amountToPay;
	            $totalPayment -= $amountToPay;

	            if ($payment['paid_amount'] == $payment['due_amount']) {
	                $payment['status'] = 'PAID';
	            }

	            // Distribute excess payment to the next schedules
	            if ($totalPayment < 0) {
	                // Get the next schedule
	                $nextPayment = next($schedule);
	                if ($nextPayment !== false) {
	                    $nextPayment['paid_amount'] += abs($totalPayment);
	                    if ($nextPayment['paid_amount'] == $nextPayment['due_amount']) {
	                        $nextPayment['status'] = 'PAID';
	                    }
	                }
	            }
	        }
	    }

	    return $schedule;
	}

	function setupDetails($assessment,$new_account_id=null){
		$AObj = $assessment;
		$account = $AObj['Assessment'];
		$paysched = $AObj['AssessmentPaysched'];
		$fees = $AObj['AssessmentFee'];
		$esp = $account['esp'];
		// Setup Account Info
		if($new_account_id):
			$account_id = $new_account_id;
			$account['ref_no']=$AObj['Assessment']['id'];
			$account['account_details']='ENROLL-'.$esp;
			$account['account_type']='student';
		endif;
		$account['id'] = $account_id;

		// Setup Paysched
		foreach($paysched as $si=>$sched):
			unset($sched['id']);
			unset($sched['created']);
			unset($sched['modified']);
			$sched['account_id']=$account_id;
			$paysched[$si] = $sched;
		endforeach;

		// Setup Fees
		foreach($fees as $fi=>$fee):
			unset($sched['id']);
			unset($sched['created']);
			unset($sched['modified']);
			$fee['account_id']=$account_id;
			$fees[$fi] = $fee;
		endforeach;


		// Update Assessment status to enrolled
		$asmt = array('id'=>$AObj['Assessment']['id'],'status'=>'NROLD');
		$this->Student->Assessment->save($asmt);

		$AObj = array(
			'Account'=>$account,
			'AccountSchedule'=>$paysched,
			'AccountFee'=>$fees
		);
		$this->create();
		// Save New Account Details
		$this->saveAll($AObj);
		return $account_id;
	}

}
