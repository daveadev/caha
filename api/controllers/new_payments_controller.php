<?php
class NewPaymentsController extends AppController {

	var $name = 'NewPayments';
	var $uses = array('NewPayment','Transaction');

	function add(){
		
		// Get current cashier 
		$cashier = $this->Auth->user()['User']['username'];
		// Prepare paymentObject
		$paymentObj = $this->data['NewPayment'];
		$paymentObj['ref_no'] = $paymentObj['series_no'];
		$paymentObj['amount'] = $paymentObj['pay_due'];
		$paymentObj['cashier'] = $cashier;
		// Prepare Transaction in NewPayment
		$TRNX = $this->Transaction;
		$trnxObj = $this->NewPayment->prepareTrnx($paymentObj,$TRNX);

		// Handle Error before saving
		if(!$trnxObj['is_valid'])
			return $this->cakeError('duplicateRefNo',array('ref_no'=>$trnxObj['ref_no']));
		
		// Proceed on saving Transaction and respond to api
		$TRNX->saveAll($trnxObj);
		$this->NewPayment->id = $TRNX->id;
		$this->Session->setFlash(__('The payment has been saved', true));

		// Dispatch an event to update Account
		$account_action = $this->requestAction('/accounts/new_payment',array('pass'=>$this->data));
		$this->data['NewPayment']['account']=$account_action;

		// Dispatch an event to update Payment Plan
		$payplan_action = $this->requestAction('/payment_plans/new_payment',array('pass'=>$this->data));
		$this->data['NewPayment']['payplan']=$payplan_action;

		// Dispatch an event to update Booklet
		$booklet_action = $this->requestAction('/booklets/new_payment',array('pass'=>$this->data));
    	$this->data['NewPayment']['booklet']=$booklet_action;

    	// Dispatch an event to update legder
		$ledger_action = $this->requestAction('/ledgers/new_payment',array('pass'=>$this->data));
    	$this->data['NewPayment']['ledger']=$ledger_action;
	}

}