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
		),
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

}