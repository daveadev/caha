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
	        if ($payment['status'] === 'UNPAID' && $totalPayment > 0) {
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
	function getDetails($account_id, $esp) {
	    // Set conditions for the Ledger association based on 'esp'
	    $this->Account->belongsTo['Student']['fields']=array('id','name','full_name','lrn','sno','section_id','year_level_id');
	    $this->Account->hasMany['Ledger']['conditions'] = array('Ledger.esp' => $esp);

	    // Retrieve account information based on the 'account_id'
	    $accountInfo = $this->Account->findById($account_id);

	    // Statement Info
	    $sy = floor($esp);
	    $sno = trim($accountInfo['Student']['sno']);
	    $name = $accountInfo['Student']['full_name'];
	    $accountInfo['Account']['name'] = sprintf("%s | %s",$sno,$name);
	    $accountInfo['Account']['school_year'] = sprintf("%s - %s",$sy,$sy+1);
	    // Define conditions for the PaymentPlan association based on 'account_id' and 'esp'
	    $conditions = array(
	        'PaymentPlan.account_id' => $account_id,
	        'PaymentPlan.esp' => $esp
	    );

	    // Define options for the find operation, including joins with 'payplan_ledgers'
	    $options = array(
	        'conditions' => $conditions,
	        'joins' => array(
	            array(
	                'table' => 'payplan_ledgers',
	                'alias' => 'PayplanLedger',
	                'type' => 'INNER',
	                'conditions' => array(
	                    'PaymentPlan.account_id = PayplanLedger.account_id',
	                    'PaymentPlan.esp = PayplanLedger.esp'
	                ),
	                'order' => array('PayplanLedger.transac_date', 'PayplanLedger.ref_no')
	            )
	        )
	    );

	    // Retrieve payment plan information based on the defined options
	    $payplan = $this->find('first', $options);

	    // Prepare a statement array containing relevant data
	    $statement = array();
	    $statement['account'] = $accountInfo['Account'];
	    $statement['student'] = $accountInfo['Student'];
	    $statement['paysched_current'] = $this->formatSchedule($accountInfo['AccountSchedule']);
	    $statement['ledger_current'] = $accountInfo['Ledger'];
	    $statement['paysched_old'] = $this->formatSchedule($payplan['PayPlanSchedule']);
	    $statement['ledger_old'] = $payplan['PayplanLedger'];

	    // Return the statement containing all relevant data
	    return $statement;
	}
	protected function formatSchedule(&$schedule){
		$total_due = 0;
		$total_pay = 0;
		$total_bal = 0;
		foreach($schedule as &$sched):
			$sched['due_date']= date("d M Y",strtotime($sched['due_date']));
			$sched['balance'] =  $sched['due_amount'] - $sched['paid_amount'];
			$total_due += $sched['due_amount'];
			$total_pay += $sched['paid_amount'];
			$total_bal += $sched['balance'];
			$sched['due_amount']= number_format($sched['due_amount'],2,'.',',');
			$sched['paid_amount']= $sched['paid_amount']>0?number_format($sched['paid_amount'],2,'.',','):'-';
			$sched['balance']= $sched['balance']>0?number_format($sched['balance'],2,'.',','):'-';
		endforeach;
		$schedule[] = array(
			'is_total'=>true,
			'total_due'=>number_format($total_due,2,'.',','),
			'total_pay'=>number_format($total_pay,2,'.',','),
			'total_bal'=>number_format($total_bal,2,'.',',')
		);
		return $schedule;

	}
}