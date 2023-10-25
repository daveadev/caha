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
	    if ($account['is_valid']) {
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
	        $isPAID = $payment['status'] === 'UNPAID' || $payment['status'] === 'NONE';
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
	function getDetails($account_id, $esp,$type='old') {
	    // Set conditions for the Ledger association based on 'esp'
	    $this->Account->belongsTo['Student']['fields']=array('id','name','full_name','lrn','sno','section_id','year_level_id');
	    $this->Account->hasMany['Ledger']['conditions'] = array('Ledger.esp' => floor($esp));


	    // Retrieve account information based on the 'account_id'
	    if($esp<2023){
	    	$this->Account->udpateEspSource($esp);
	    }
	    $accountInfo = $this->Account->findById($account_id);
	    $sect_id = $accountInfo['Student']['section_id'];
	    $sectInfo = $this->Account->Student->Section->findByid($sect_id);


	    // Statement Info
	    $sy = floor($esp);
	    $sno = trim($accountInfo['Student']['sno']);
	    $section = sprintf("%s - %s",$sectInfo['YearLevel']['name'],$sectInfo['Section']['name']);
	    $name = ucwords(strtolower($accountInfo['Student']['full_name']));
	    $accountInfo['Account']['name'] = $name;
	    $accountInfo['Account']['sno'] = $sno;
	    $accountInfo['Student']['section'] = $section;
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
		    $statement['paysched_current'] = $this->formatSchedule($accountInfo['AccountSchedule']);
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
	protected function formatSchedule(&$schedule){
		$dueNow = $this->dueThisMonth($schedule);
		$total_due = 0;
		$total_pay = 0;
		$total_bal = 0;
		foreach($schedule as &$sched):
			if($sched['due_date']!='Old Account')
			$sched['due_date']= date("d M Y",strtotime($sched['due_date']));
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
	protected function dueThisMonth($schedule){
		$currentMonth = date('Y-m');
		$dueAmount = 0;
		$dueNow = array();
		$dueMos = array();
		foreach ($schedule as $index=>$sched) {
			if($sched['due_date']=='Old Account') continue;
		    $dueDate = strtotime($sched['due_date']);
		    $hasBal = $sched['paid_amount'] < $sched['due_amount'];
		    $isOverDue = $dueDate <= time();
		    $isDueNow = substr($sched['due_date'], 0, 7) === $currentMonth;
		    if ( $hasBal && ( $isOverDue||$isDueNow )) {
		       $dueAmount += $sched['due_amount'];
		       $dueAmount -= $sched['paid_amount'];
		       $dueMos[]=$index;
		    }
		    if($isDueNow||$isOverDue){
		    	$dueNow['date'] = date('d M Y',$dueDate);
		    }
		}
		$dueNow['amount']=number_format($dueAmount,2,'.',',');
		$dueNow['months']=$dueMos;
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
			if($run_bal<0)
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
				foreach($schedule as &$sched):
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

			endif;

			if($code =='AMFAV'):

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

	
}