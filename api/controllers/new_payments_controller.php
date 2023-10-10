<?php
class NewPaymentsController extends AppController {

	var $name = 'NewPayments';
	var $uses = array('NewPayment','Transaction');

	function add(){
		
		$cashier = $this->Auth->user()['User']['username'];
		$paymentObj = $this->data['NewPayment'];
		$paymentObj['ref_no'] = $paymentObj['series_no'];
		$paymentObj['amount'] = $paymentObj['pay_due'];
		$paymentObj['cashier'] = $cashier;
		$TRNX = $this->Transaction;
		$trnxObj = $this->NewPayment->prepareTrnx($paymentObj,$TRNX);
		if($trnxObj['is_valid']):
			$TRNX->saveAll($trnxObj);
		else:
			$this->cakeError('duplicateRefNo',array('ref_no'=>$trnxObj['ref_no']));
		endif;
	}

}