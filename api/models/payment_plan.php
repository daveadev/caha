<?php
class PaymentPlan extends AppModel {
	var $name = 'PaymentPlan';
	var $recursive = 1;
	var $consumableFields = array('label','id','account_id','guarantor','monthly_payments','total_due','terms');
	var $virtualFields = array('label'=>"CONCAT(PaymentPlan.guarantor,'/ Php ',PaymentPlan.total_due, ' for ', PaymentPlan.terms, ' mos.' )");
	var $hasMany = array(
		'PayPlanSchedule' => array(
			'className' => 'PayPlanSchedule',
			'foreignKey' => 'payment_plan_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array('id','pay_count','due_date','due_amount','paid_amount','status'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'PayplanLedger' => array(
            'className' => 'PayplanLedger',
            'foreignKey' =>false,
            'conditions' =>'',
            'fields' => '',
            'order' => ''
        )
	);


	var $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
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



	/**
	 * Forward a payment for a given account and ESP (Effective School Year Period).
	 *
	 * @param int $account_id The ID of the account.
	 * @param string $esp The Effective School Year Period.
	 * @param string $ref_no The reference number for the payment.
	 * @param float $amount The payment amount.
	 */
	function forwardPayment($account_id, $esp, $ref_no, $amount) {
	    // Find the payment plan based on account ID and ESP
	    $cond = array('account_id' => $account_id, 'esp' => $esp);
	    $PPObj = $this->find('first', array('conditions' => $cond));

	    // Extract account and payment plan information
	    $account = $PPObj['Account'];
	    $plan = $PPObj['PaymentPlan'];

	    // Update the account and payment plan with the payment amount
	    $this->updateAccount($account, $plan, $amount);

	    $planInfo = array();
	    // Save balances in payment_plan and account if the account is valid
	    if ($account['is_valid']||1) {
	        $this->save($plan);
	        $this->Account->save($account);

	        // Distribute payment and update the payment schedule
	        $sched = $PPObj['PayPlanSchedule'];
	        $this->distributePayments($sched, $amount);
	        $this->PayPlanSchedule->saveAll($sched);
	        $planInfo['amount_paid'] = $amount; 
	        $planInfo['total_payments'] = $plan['total_payments']; 
	        $planInfo['total_balance'] = $plan['total_balance']; 
	    }

	    return $planInfo;
	}

	/**
	 * Update the account and payment plan based on the payment amount.
	 *
	 * @param array $account Reference to the account array.
	 * @param array $plan Reference to the payment plan array.
	 * @param float $amount The payment amount.
	 */
	protected function updateAccount(&$account, &$plan, $amount) {
	    $total_bal = $plan['total_balance'];
	    $old_bal = $account['old_balance'];
	    $account['is_valid'] = $total_bal == $old_bal;

	    // If the account is valid, update the total payments and balance
	    if ($account['is_valid']) {
	        $plan['total_payments'] += $amount;
	        $plan['total_balance'] = $plan['total_due'] - $plan['total_payments'];
	        $account['old_balance'] = $plan['total_balance'];
	    }
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


	/**
	 * Retrieves account details and related payment plan information.
	 *
	 * @param int $account_id The account ID for which details are to be retrieved.
	 * @param float $esp The effective selling price for filtering ledgers and payment plans.
	 * @return array An array containing account details, current and old payment schedules, and ledgers.
	 */
	function getDetails($account_id, $esp,$type='old',$billMonth = 'current') {
	    // Set conditions for the Ledger association based on 'esp'
	    $this->Account->belongsTo['Student']['fields']=array('id','name','full_name','lrn','sno','section_id','year_level_id');
		
		$ledgerCond =  array('Ledger.esp' => floor($esp));
		$paySchedCond = array();
		$cutoffDate = date("Y-m-d", strtotime("last day of this month"));
		
		if($billMonth!='current'):
			$billYearText = substr($billMonth, 0, 3);
			$billMonthText = substr($billMonth, 3);
			$date = strtotime("last day of $billYearText $billMonthText");
			$cutoffDate = date('Y-m-d', $date);
		endif;
		
		$ledgerCond['Ledger.transac_date <=']=$cutoffDate;
		$paySchedCond['OR'][] = array('AccountSchedule.transaction_type_id'=>array('REGFE', 'INIPY', 'SBQPY'));
		$paySchedCond['OR'][] = array('AccountSchedule.transaction_type_id'=>'ACECF', 'AccountSchedule.due_date <='=>$cutoffDate);
	
	    $this->Account->hasMany['Ledger']['conditions'] = $ledgerCond;
	    $this->Account->hasMany['Ledger']['order'] = array('Ledger.transac_date' ,'Ledger.id');
		$this->Account->hasMany['AccountSchedule']['conditions'] = $paySchedCond;
	    // Retrieve account information based on the 'account_id'
	    if($esp<2023){
	    	$this->Account->udpateEspSource($esp);
			$this->Account->hasMany['AccountSchedule']['conditions']=array('YEAR(AccountSchedule.created)'=>$esp);
		
	    }
		
	    $accountInfo = $this->Account->findById($account_id);
		$adjustPaymentDates = false;
		if($adjustPaymentDates):
			$paysched = $accountInfo['AccountSchedule'];
			$entries =  $accountInfo['Ledger'];
			$payEntries = array();
			foreach($entries as $entry):
				$isPayment = $entry['type']=='-' && in_array($entry['transaction_type_id'],array('ADVPY','SBQPY','REGFE','DSCNT'));
				if($isPayment):
					$payEntries[] = $entry;
				endif;
			endforeach;
			$this->applyPaymentDates($paysched,$payEntries);
			$paymentDates = array();
			foreach($paysched as $ps):
				$pObj = array(
					'id'=>$ps['id'],
					'paid_amount'=>$ps['paid_amount'],
					'paid_date'=>$ps['paid_date'],
					'status'=>$ps['status']
				);
				$paymentDates[]=$pObj;
			endforeach;
			$this->Account->AccountSchedule->saveAll($paymentDates);
		endif;
	    $sect_id = $accountInfo['Student']['section_id'];
	    $sectInfo = $this->Account->Student->Section->findByid($sect_id);
		
		if($esp<2023){
			$legderConditions=array('esp'=>$esp,'account_id'=>$account_id);
			$ledgerEntries =  $this->Account->Ledger->find('all',array('recursive'=>-1,'conditions'=>$legderConditions));
			$accountInfo['Ledger']=[];
			foreach($ledgerEntries as $entry):
				$accountInfo['Ledger'][]=$entry['Ledger'];
			endforeach;
		}
	    // Statement Info
	    $sy = floor($esp);
	    $sno = trim($accountInfo['Student']['sno']);
	    $section = sprintf("%s - %s",$sectInfo['YearLevel']['name'],$sectInfo['Section']['name']);
	    $name = ucwords(strtolower($accountInfo['Student']['full_name']));
	    $accountInfo['Account']['name'] = $name;
	    $accountInfo['Account']['sno'] = $sno;
	    $accountInfo['Student']['section'] = $section;
		$accountInfo['Account']['esp'] = $esp; 
	    $accountInfo['Account']['school_year'] = sprintf("%s - %s",$sy,$sy+1);
	    // Define conditions for the PaymentPlan association based on 'account_id' and 'esp'
	    $conditions = array(
	        'PaymentPlan.account_id' => $account_id,
	        'PaymentPlan.esp' => $esp
	    );

	    // Define options for the find operation, including joins with 'payplan_ledgers'
	    $options = array(
	        'conditions' => $conditions,
	    );

	    // Retrieve payment plan information based on the defined options
	    $payplan = $this->find('first', $options);
	    $cond = array('PayplanLedger.account_id'=>$account_id,
	    	'PayplanLedger.esp'=>$esp
		);

	    
	    

	    // Prepare a statement array containing relevant data
	    $statement = array();
	    $statement['account'] = $accountInfo['Account'];
	    $statement['student'] = $accountInfo['Student'];
	    if($type=='current'):
		    $statement['paysched_current'] = $this->formatSchedule($accountInfo['AccountSchedule'],$billMonth);
		    $statement['ledger_current'] = $this->formatLedger($accountInfo['Ledger']);
	   	endif;
	   	if($type=='old'):
	   		if(!$payplan){
	   			$statement['paysched_old'] = array();
	   			$statement['ledger_old'] = array();
	   			return $statement;
	   		}
	   		$this->PayplanLedger->recursive=-1;
	    $ledger = $this->PayplanLedger->find('all',array('conditions'=>$cond));
	    	$payplan_sched = array();
	    	foreach($ledger as $l):
	    		$payplan_sched[]=$l['PayplanLedger'];
	    	endforeach;
	    	$payplan['PayplanLedger']=$payplan_sched;
		    $statement['paysched_old'] = $this->formatSchedule($payplan['PayPlanSchedule']);
		    $statement['ledger_old'] = $this->formatLedger($payplan['PayplanLedger']);
	   	endif;
	    // Return the statement containing all relevant data
	    return $statement;
	}
	function buildSchedule($schedule){
		return $this->formatSchedule($schedule);
	}
	protected function formatSchedule(&$schedule,$billMonth='current'){
		$dueNow = $this->dueThisMonth($schedule,$billMonth);
		$total_due = 0;
		$total_pay = 0;
		$total_bal = 0;
		foreach($schedule as &$sched):
			if($sched['due_date']!='Old Account')
			$sched['due_date']= date("d M Y",strtotime($sched['due_date']));

			if($sched['transaction_type_id']=='ACECF'):
				$sched['due_date'] =date('M',strtotime('-1 month',strtotime($sched['due_date']))).' ACEC';
			endif;
			if($sched['transaction_type_id']=='REGFE'):
				$sched['due_date'] = 'Registration';
			endif;
			if($sched['transaction_type_id']=='INIPY'):
				$sched['due_date'] = 'Upon Enrollment';
			endif;
			$sched['balance'] =  $sched['due_amount'] - $sched['paid_amount'];
			$total_due += $sched['due_amount'];
			$total_pay += $sched['paid_amount'];
			$total_bal += $sched['balance'];

			$sched['due_amount']= number_format($sched['due_amount'],2,'.',',');

			if($sched['due_amount']<0):
				$sched['due_amount'] ="(". str_replace('-','',$sched['due_amount']).")";
			endif;
			$sched['paid_amount']= $sched['paid_amount']>0?number_format($sched['paid_amount'],2,'.',','):'-';
			$sched['balance']= $sched['balance']>0?number_format($sched['balance'],2,'.',','):'-';
		endforeach;
		$schedule[] = array(
			'is_total'=>true,
			'total_due'=>number_format($total_due,2,'.',','),
			'total_pay'=>number_format($total_pay,2,'.',','),
			'total_bal'=>number_format($total_bal,2,'.',','),
			'due_now' =>$dueNow
		);
		return $schedule;

	}
	protected function dueThisMonth($schedule,$billMonth='current'){
		$cutoffMonth = date('Y-m');
		$cutoffTime = time();
		if($billMonth!='current'):
			$billYearText = substr($billMonth, 0, 3);
			$billMonthText = substr($billMonth, 3);
			$cutoffTime = strtotime("last day of $billYearText $billMonthText");
			$cutoffMonth = date('Y-m', $cutoffTime);
		endif;
		
		$dueAmount = 0;
		$dueNow = array();
		$dueMos = array();
		foreach ($schedule as $index=>$sched) {
			if($sched['due_date']=='Old Account' && count($schedule)>1) continue;
		    $dueDate = strtotime($sched['due_date']);
			$dueDateFormat = date('Y-m',$dueDate);
		    $hasBal = $sched['paid_amount'] < $sched['due_amount'];
		    $isOverDue = $dueDate <= $cutoffTime;
		    $isDueNow = $dueDateFormat === $cutoffMonth;

		    // Check if INIPY is UNPAID
			if($sched['bill_month']=='UPONNROL' && $sched['status']!='UNPAID'){
				$isDueNow= true;
			}
		    if($isDueNow||$isOverDue){
		    	$dueNow['date'] = date('d M Y',$dueDate);
		    }

		    if ( $hasBal && ( $isOverDue||$isDueNow )) {
		       $dueAmount += $sched['due_amount'];
		       $dueAmount -= $sched['paid_amount'];
		       $dueMos[]=$index;
		    }
		    
		}

		$dueNow['amount']=number_format($dueAmount,2,'.',',');
		$dueNow['months']=$dueMos;
		// Move due date to current month when dueAmount is zero
		if($dueAmount==0):
			$dueDate = substr($sched['due_date'],-2);
			$dueDate = $cutoffMonth.'-'.$dueDate;
			$dueNow['date'] = date('d M Y',strtotime($dueDate));
		endif;
		
		return $dueNow;
	}
	protected function formatLedger(&$entries){
		$run_bal = 0;
	
		foreach($entries as &$entry):

			$entry['transac_date']= date("d M Y",strtotime($entry['transac_date']));
			$amount = $entry['amount'];
			
			$type = $entry['type'];
			$pay=$type=='-'?$amount:0;
			$fee=$type=='+'?$amount:0;
			$run_bal += $fee - $pay;
			$entry['fee'] = $fee==0?'':number_format($fee,2,'.',',');
			$entry['pay'] = $pay==0?'': number_format($pay,2,'.',',');
			if($fee==0 && $type=='+')
				$entry['fee'] = '0.00';
			$run_bal_disp = number_format(abs($run_bal),2,'.',',');
			if($run_bal<0  && $run_bal_disp!='0.00')
				$run_bal_disp = "($run_bal_disp)";
			$entry['bal'] =$run_bal_disp; 
		endforeach;
		
		return $entries;
	}

	public function recomputeLedger(&$entries){
		$this->formatLedger($entries);
	}
	public function recomputePaysched(&$schedule,$adjust=null){
		if($adjust):
			$code =$adjust['apply_to'];
			$AdjAmount = $adjust['amount'];
			if($code =='SBQPY'):
				$PSLen = count($schedule)-1;
				$total_payments = 0;
				unset($schedule[$PSLen]);
				$due_amount = round($AdjAmount/($PSLen-1),0);
				$total_dues = 0;
				$ttid = '';
				foreach($schedule as &$sched):
					if(isset($sched['transaction_type_id']))
						$ttid = $sched['transaction_type_id'];
					$sched['due_amount'] = floatval(str_replace(",", "", $sched['due_amount']));
					$paid_amount = floatval(str_replace(",", "", $sched['paid_amount']));
					$sched['paid_amount'] =$paid_amount;
					$total_payments+=$paid_amount;
					if($ttid==$code):
						$sched['due_amount']=$due_amount;
					else:
						$total_dues+=$sched['due_amount'];
					endif;
					$sched['paid_amount']=0;
					$sched['status']='NONE';
				endforeach;
				$last_due = $AdjAmount- ($due_amount*($PSLen-2));
				
				$sched['due_amount']=$last_due;
				$this->distributePayments($schedule,$total_payments);
				$this->formatSchedule($schedule);
			endif;
			if($code =='OLDAC'):
				$total_payments = $AdjAmount;
				
				$PSLen = count($schedule)-1;
				$lastItem = $schedule[$PSLen];
				unset($schedule[$PSLen]);
				$oldAccSched= array(
					'due_date'=>'Old Account',
					'due_amount'=>$AdjAmount,
					'paid_amount'=>'-',
					'status'=>'NONE',
					'balance'=>$AdjAmount,
				);
				$oa_pay =0;
				if(isset($adjust['payments'])):
					$oa_pay =$adjust['payments'];
					$oldAccSched['paid_amount']=$oa_pay;
					$oldAccSched['balance']=$AdjAmount-$oa_pay;
				endif;

				$total_due = floatval(str_replace(",", "", $lastItem['total_due']));
				$total_pay = floatval(str_replace(",", "", $lastItem['total_pay']));
				$total_due +=$AdjAmount;
				$total_pay +=$oa_pay;
				$total_bal = $total_due- $total_pay;

				$lastItem['total_due']=number_format($total_due,2,'.',',');
				$lastItem['total_pay']=number_format($total_pay,2,'.',',');
				$lastItem['total_bal']=number_format($total_bal,2,'.',',');
				


				$oldAccSched['due_amount'] = number_format($oldAccSched['due_amount'],2,'.',',');
				if($oa_pay)
				$oldAccSched['paid_amount']=number_format($oldAccSched['paid_amount'],2,'.',',');
				$oldAccSched['balance'] = number_format($oldAccSched['balance'],2,'.',',');
				$schedule[]=$oldAccSched;
				$schedule[]=$lastItem;
				if(count($schedule)==2):
					
					$schedule[2]['due_now']['amount']=$lastItem['total_bal'];
					$schedule[2]['due_now']['months'][]=1;
					$schedule[2]['due_now']['date']=date('15 M Y',time());
				endif;
			endif;

			if($code =='AMFAV' || $code=='OR_PAY'):

				$total_payments = $AdjAmount;
				
				$PSLen = count($schedule)-1;
				$lastItem = $schedule[$PSLen];
				array_pop($schedule);
				$hasOLDACC = false;
				foreach($schedule as &$sched):
					if(isset($sched['bill_month'])):
						$sched['due_amount'] = floatval(str_replace(",", "", $sched['due_amount']));
						$sched['paid_amount']=0;
						$sched['balance']=0;
						$sched['status']='NONE';
					else:
						$hasOLDACC = $sched['due_date'] =='Old Account';
					endif;
				endforeach;
				$OLD_ACC =null;
				if($hasOLDACC):
					$OLD_ACC = array_pop($schedule);
					$OLD_ACC['due_amount']=floatval(str_replace(",", "", $OLD_ACC['due_amount']));
					$OLD_ACC['paid_amount']=floatval(str_replace(",", "", $OLD_ACC['paid_amount']));
					$OLD_ACC['balance']=floatval(str_replace(",", "", $OLD_ACC['balance']));
				endif;

				
				$this->distributePayments($schedule,$total_payments);
				if($hasOLDACC)
					array_push($schedule,$OLD_ACC);
				$this->formatSchedule($schedule);
			endif;
		endif;
	}

	protected function applyPaymentDates(&$paysched, $ledgers) {
	
		foreach ($paysched as &$schedule){
				$schedule['paid_amount']=0;
				$schedule['status']='NONE';
				$schedule['paid_date']=null;
			}
			
		foreach ($ledgers as $payment) {
			$amountRemaining = $payment['amount'];
			$transacId = $payment['transaction_type_id'];
			$paymentDate = $payment['transac_date'];
			
			//pr($payment['ref_no']);
			//pr($payment['amount']);
			// Iterate over pay schedules to apply payments
			foreach ($paysched as &$schedule) {
				
				if ($amountRemaining <= 0) {
					// No more amount to allocate
					break;
				}
				$matchTID =($transacId=='SBQPY'||$transacId=='ADVPY') && $schedule['transaction_type_id']!='REGFE' ;
				if($transacId=='DSCNT' && $schedule['paid_amount']==$payment['amount']){
					$schedule['paid_amount']=0;
					break;
				}
				if($transacId=='REGFE')
					$matchTID = $transacId == $schedule['transaction_type_id'];
				
					
				
				// Only process schedules with the same account and transaction type
				if ($matchTID &&$schedule['status']!='PAID' ) {
					
					$dueAmount = $schedule['due_amount'];
					$paidAmount =$schedule['paid_amount'];
					
					$remainingDue = $dueAmount - $paidAmount ;
					
					if ($remainingDue > 0) {
						if ($amountRemaining >= $remainingDue) {
							// Payment fully covers this schedule's due amount
							$schedule['paid_amount'] = $dueAmount;
							$schedule['paid_date'] = $paymentDate;
							$schedule['status']='PAID';
							//echo $amountRemaining.' '.$remainingDue;
							$amountRemaining -= $remainingDue; // Deduct from remaining amount
							//pr($payment);
							//pr($schedule);
							
							//echo "<hr>";
						} else {
							// Partial payment
							//echo $amountRemaining.' '.$remainingDue.'partial';
							$schedule['paid_amount'] += $amountRemaining;
							$schedule['paid_date'] = $paymentDate;
							$schedule['status']='NONE';
							$amountRemaining = 0; // Payment fully used
							//pr($payment);
							//pr($schedule);
							
						}
					}
				}
			}
		}
	}
}