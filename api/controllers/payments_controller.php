<?php
class PaymentController extends AppController {

	var $name = 'Payments';
	var $uses = array('Account','Ledger','Transaction','Booklet')

	function add() {
		$payment =  $this->data['Payment'];
		$amount = $payment['amount'];
		$student = $payment['student'];
		$transactions = $payment['transactions'];
		// Legder
		// booklet =  $this->Booklet->find('first');
		// series_no =  $booklet['Booklet']['receipt_type'] .' '. $booklet['Booklet']['series_counter'];
		// 
		$legder_accounts = array();
		

		foreach($transactions as $trnx){
			$ledgerItem =  array(flag, 'OR_1234',$trnx['detail'],$trnx['amount'])
			//Save in ledger
			array_push($legder_accounts,$ledgerItem);

		}

		$this->Legder->saveAll($legder_accounts);

		$trnx =  array('payment', 'fulfilled',$series_no,date,time ,account_id);
		$this->Transaction->save($trnx);
		$trnxID =  $this->Transaction->id;
		//Repeat saving in transactionDetails & transactionPayment
		$this->Transaction->TransactionDetail->saveAll();		// IP, SP
		$this->Transaction->TransactionPayment->saveAll();		// Cash, Check

		//Update account balances and payment
		$account = $this->Account->findById();

		// Update account_schedule
		// Loop on applicable payment schedule and udpates status, date_paid
		// Update account_fees
		// Check balance and percentage to update amounts
		// Update account_history
		// Save OR and Details

		// Update booklet series_counter plus 1

	}

}