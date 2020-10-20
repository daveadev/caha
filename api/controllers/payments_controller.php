<?php
class PaymentsController extends AppController {

	var $name = 'Payments';
	var $uses = array('Account','Ledger','Transaction','Booklet','AccountSchedule','AccountFee','TransactionPayment','TransactionDetail','AccountHistory','AccountTransaction');
	
	function add() {
		$payments =  $this->data['Payment'];
		$student = $this->data['Student'];
		$transactions = $this->data['Transaction'];
		$booklet = $this->data['Booklet'];
		$account_id = $student['id'];
		$schedules = $this->AccountSchedule->find('all',array('recursive'=>-1,'conditions'=>array('AccountSchedule.account_id'=>$account_id)));
		$fees = $this->AccountFee->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$account_id)));
		//pr($schedules); exit();
		//pr($fees); exit();
		$acc = $this->Account->find('first',array('recursive'=>0,'conditions'=>array('id'=>$account_id)));
		$Account = $acc['Account'];
		$payment_to_date = $Account['payment_total']+$Account['discount_amount'];
		$curr_book = 'OR '.$booklet['series_counter'];
		$total_payment = 0;
		$today = date("Y-m-d");
		$time = date("h:i:s");
		/* pr($this->data);
		exit(); */
		// Legder
		// booklet =  $this->Booklet->find('first');
		// series_no =  $booklet['Booklet']['receipt_type'] .' '. $booklet['Booklet']['series_counter'];
		// 
		
		$transac_payments = array();
		$ledger_accounts = array();
		$account_transac = array();
		$account_history = array();
		$transac_details = array();
		$payment_modes = array();
		
		
		// For transactions table
		$transac_data = array('type'=>'payment','status'=>'fulfilled','ref_no'=>$curr_book,'transac_date'=>$today,'transac_time'=>$time,'account_id'=>$account_id);
		$this->Transaction->saveAll($transac_data);
		$last = $this->Transaction->findById($this->Transaction->id);
		$transac_id = $last['Transaction']['id'];
		
		
		// to get the total payment of all payments and save transac payments
		foreach($payments as $i=>$pay){
			$tp = array('transaction_id'=>$transac_id,'payment_method_id'=>$pay['id'],'amount'=>$pay['amount']);
			if($pay['id']=='CHCK'){
				$tp['valid_on']=$pay['date'];
				$tp['details']=$pay['bank'];
			}
			else
				$tp['details']='Cash';
			array_push($payment_modes,$pay['id']);
			array_push($transac_payments,$tp);
			$total_payment += $pay['amount'];
		}
		$account_total = $Account['discount_amount']+$Account['payment_total']+$total_payment;
		
		
		
		// for Ledgers and Account transactions
		$transac_payment = $total_payment;
		foreach($transactions as $trnx){
			switch($trnx['id']){
				case 'INIPY': $detail = 'Initial Payment'; break;
				case 'OLDAC': $detail = 'Old Account Payment';; break;
				case 'SBQPY': $detail = 'Subsequent Payment'; break;
			}
			$payment = $total_payment;
			if($trnx['amount']<$total_payment){
				$payment = $trnx['amount'];
				$transac_payment -=$payment;
			}
			$payment_to_date += $payment;
			//Save to ledger
			$ledgerItem =  array(
				'account_id'=>$account_id,
				'type'=>'-',
				'esp'=>2020,
				'transac_date'=>$today,
				'transac_time'=>$time,
				'ref_no'=>$curr_book,
				'details'=>$detail,
				'amount'=>$payment
			);
			
			
			// save to account histories
			$history = array(
				'account_id'=>$account_id,
				'transac_date'=>$today,
				'transac_time'=>$time,
				'ref_no'=>$curr_book,
				'details'=>$detail,
				'flag'=>'-',
				'amount'=>$payment
			);
			if($trnx['id']!=='OLDAC'){
				$history['total_due']=$Account['assessment_total'];
				$history['total_paid']=$payment_to_date;
				$history['balance']=$Account['outstanding_balance']-$payment;
				$Account['outstanding_balance'] = $Account['assessment_total']-$payment_to_date;
				$Account['payment_total'] = $payment_to_date;
			}
			// save to account transactions
			$acct_transac =  array('account_id'=>$account_id,'transaction_type_id'=>$trnx['id'],'ref_no'=>$curr_book,'amount'=>$payment);
			
			// save to transaction details
			$td = array(
				'transaction_id'=>$transac_id,
				'transaction_type_id'=>$trnx['id'],
				'detail'=>$detail,
				'amount'=>$payment
			);
			
			array_push($account_transac,$acct_transac);
			array_push($account_history,$history);
			array_push($ledger_accounts,$ledgerItem);
			array_push($transac_details,$td);
			if($trnx['id']=='OLDAC'){
				$total_payment -= $trnx['amount'];
			}
			
		}
		
		
		
		// for account payment schedule
		$sched_payment = $total_payment;
		//pr($sched_payment); exit();
		$account_schedules = array();
		foreach($schedules as $i=>$sched){
			$sched = $sched['AccountSchedule'];
			if($sched['paid_amount']<$sched['due_amount']){
				$payment_required = $sched['due_amount']-$sched['paid_amount'];
				if($payment_required<$sched_payment){
					$sched_payment-=$payment_required;
					$sched['paid_amount'] = $payment_required;
				}else{
					$sched['paid_amount'] = $sched_payment;
					$sched_payment = 0;
				}
				$sched['paid_date'] = $today;
				$sched['status'] = 'PAID';
				array_push($account_schedules,$sched);
				//pr($sched);
			}
			if($sched_payment==0)
				break;
		}
		
		
		// For account fees
		
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
		
		if(($total_feePaid+$total_payment)>$total_misc){
			$Account['rounding_off'] = 0;
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
						$fee['paid_amount'] = ($total_feePaid+$total_payment)-$total_misc;
					
				}
				
				array_push($account_fees,$fee);
			}
		}else{
			$round_off = 0;
			foreach($fees as $i=>$fee){
				$fee = $fee['AccountFee'];
				if($fee['fee_id']=='TUI')
					continue;
				$fee['paid_amount'] = ($total_feePaid+$total_payment) * $fee['percentage'];
				$round_off += $fee['paid_amount'];
				array_push($account_fees,$fee);
			}
			$Account['rounding_off'] = $Account['total_payment']-$round_off;
		}
		
		
		if($booklet['series_counter']<$booklet['series_end'])
			$booklet['series_counter'] = $booklet['series_counter']+1;
		else
			$booklet['status'] = 'INACTV';
		
		$DataCollection = array(
			'TransactionPayment'=>$transac_payments,
			'TransactionDetail'=>$transac_details,
			'AccountTransaction'=>$account_transac,
			'Ledger'=>$ledger_accounts,
			'AccountHistory'=>$account_history,
			'AccountSchedule'=>$account_schedules,
			'AccountFee'=>$account_fees,
			'Account'=>$Account,
			'Booklet'=>$booklet
		);
		foreach($DataCollection as $i=>$collection){
			if($this->$i->saveAll($collection)){
				$this->Session->setFlash(__('The '. $i .' has been saved', true));
				$this->set('payments',$DataCollection);
			}
			else{
				$this->Session->setFlash(__('Error saving '.$i, true));
				break;
			}
		}
		//pr($DataCollection);
		exit();
		
		$this->Booklet->saveAll('');
		
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