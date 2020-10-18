<?php
class PaymentsController extends AppController {

	var $name = 'Payments';
	var $uses = array('Account','Ledger','Transaction','Booklet','AccountSchedule','AccountFee');
	
	function add() {
		$payments =  $this->data['Payment'];
		$student = $this->data['Student'];
		$transactions = $this->data['Transaction'];
		$booklet = $this->data['Booklet'];
		$account_id = $student['id'];
		$schedule = $this->AccountSchedule->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$account_id)));
		$fees = $this->AccountFee->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$account_id)));
		$today = date("Y-m-d");
		$time = date("h:i:s");
		
		// Legder
		// booklet =  $this->Booklet->find('first');
		// series_no =  $booklet['Booklet']['receipt_type'] .' '. $booklet['Booklet']['series_counter'];
		// 
		$legder_accounts = array();
		$account_transac = array();
		$curr_book = $booklet['series_counter'];
		$total_payment = 0;
		$payment_modes = array();
		// to get the total payment more than 1 mode of payments
		foreach($payments as $i=>$pay){
			array_push($payment_modes,$pay['id']);
			$total_payment += $pay['amount'];
		}
		
		
		// for Ledgers and Account transactions
		$transac_payment = $total_payment;
		foreach($transactions as $trnx){
			switch($trnx['id']){
				case 'INIPY': $detail = 'Initial Payment'; break;
				case 'OLDAC': $detail = 'Old Account Payment'; break;
			}
			$payment = $total_payment;
			if($trnx['amount']<$total_payment){
				$payment = $trnx['amount'];
				$transac_payment -=$payment;
			}
			$ledgerItem =  array(
				'account_id'=>$student['id'],
				'type'=>'-',
				'esp'=>2020,
				'transac_date'=>$today,
				'transac_time'=>$time,
				'ref_no'=>$curr_book,
				'details'=>$detail,
				'amount'=>$payment
			);
			
			//Save in ledger
			$transac = array(
				'type'=>'payment',
				'transac_date'=>$today,
				'transac_time'=>$time,
				'status'=>'fulfilled',
				'ref_no'=>$curr_book,
				'amount'=>$payment
				
			);
			array_push($account_transac,$transac);
			array_push($legder_accounts,$ledgerItem);
			if($curr_book<$booklet['series_end'])
				$curr_book++;
		}
		
		// To credit the change if Check paid
		if(in_array('CHCK',$payment_modes))
			$account_credit = $total_payment;
		
		
		// for account payment schedule
		$sched_payment = $total_payment;
		$account_schedules = array();
		foreach($schedule as $i=>$sched){
			$sched = $sched['AccountSchedule'];
			if($sched['due_amount']==$sched['paid_amount'])
				continue;
			if($sched['paid_amount']<$sched['due_amount']){
				$payment_required = $sched['due_amount']-$sched['paid_amount'];
				if($payment_required<$sched_payment){
					$sched_payment-=$payment_required;
					$sched['paid_amount'] = $sched['due_amount'];
				}else{
					$sched['paid_amount'] = $sched_payment;
					$sched_payment = 0;
				}
				$sched['paid_date'] = $today;
				$sched['status'] = 'PAID';
				array_push($account_schedules,$sched);
			}
			if($sched_payment==0)
				break;
		}
		
		$account_fees = array();
		$fee_payment = $total_payment;
		$total_feePaid = 0;
		$total_misc = 0;
		foreach($fees as $i=>$fee){
			$fee = $fee['AccountFee'];
			$total_feePaid += $fee['paid_amount'];
			if($fee['fee_id']!='TUI')
				$total_misc += $fee['due_amount'];
		}
		/* pr($total_feePaid);
		pr($total_payment);
		pr($total_misc); */
		if(($total_feePaid+$total_payment)>$total_misc){
			foreach($fees as $i=>$fee){
				$fee = $fee['AccountFee'];
				if($fee['fee_id']=='TUI'&&$fee['paid_amount']>0){
					if($fee_payment>$fee['due_amount'])
						$payment = $total_payment-$fee['due_amount'];
					else{
						$payment=$fee_payment;
						$fee_payment = 0;
					}
					$fee['paid_amount'] += $payment;
				}else{
					$fee['paid_amount'] = $fee['due_amount'];
					if($fee['fee_id']=='TUI')
						$fee['paid_amount'] = round(($total_feePaid+$total_payment)-$total_misc,0);
					
				}
				
				array_push($account_fees,$fee);
			}
		}else{
			foreach($fees as $i=>$fee){
				$fee = $fee['AccountFee'];
				if($fee['fee_id']=='TUI')
					continue;
				$fee['paid_amount'] = ($total_feePaid+$total_payment) * $fee['percentage'];
				array_push($account_fees,$fee);
			}
		}
		//pr($account_schedules);
		pr($total_feePaid);
		pr($account_fees);
		//pr($fees);
		/* 
		pr($account_transac);
		pr($total_payment);
		pr($payment_modes);
		pr($account_credit); */
		exit();
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