<?php
class PaymentsController extends AppController {

	var $name = 'Payments';
	var $uses = array(
					'Account',
					'Ledger',
					'Transaction',
					'Booklet',
					'AccountSchedule',
					'AccountFee',
					'TransactionPayment',
					'TransactionDetail',
					'AccountHistory',
					'AccountTransaction',
					'Reservation',
					'YearLevel',
					'Student',
					'Inquiry',
					'Assessment',
					'ClasslistBlock',
					'ClasslistIrregular',
					'Section'
				);
	
	function add() {
		$payments =  $this->data['Payment'];
		$accountType=$this->data['Student']['account_type'];
		$_DATA =  $this->data;

		$this->logData($_DATA);

		//pr($this->data); exit();
		if(isset($this->data['Type'])){
			if($accountType=='inquiry'){
				$this->SaveInquiry($_DATA);
			}else{
				$this->SaveOthers($_DATA);
			}
		}else{

			// TODO:  Add comment prepare data for payment etc.
			$student = $_DATA['Student'];
			$transactions = $_DATA['Transaction'];
			$booklet = $_DATA['Booklet'];
			$account_id = $student['id'];
			$ESP =  $_DATA['Cashier']['esp'];
			$TOTAL_DUE =  $_DATA['Cashier']['total_due'];
			$USERNAME =  $this->Auth->user()['User']['username'];
			$schedules = $this->AccountSchedule->find('all',array('recursive'=>-1,'conditions'=>array('AccountSchedule.account_id'=>$account_id)));
			$fees = $this->AccountFee->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$account_id)));

			$acc = $this->Account->find('first',array('recursive'=>0,'conditions'=>array('id'=>$account_id)));
			$Account = $acc['Account'];
			$payment_to_date = $Account['payment_total'];
			$total_payment = 0;
			$today =  date("Y-m-d", strtotime($_DATA['Cashier']['date']));
			$time = date("h:i:s");
			//pr($this->data);
			//pr($this->data); exit();


			// TODO: Move to new function BookletUpdating
			$curr_refNo = $booklet['receipt_type']. ' ' .$booklet['series_counter'];
			$booklet = $this->checkBooklet($_DATA);
			
			//pr($schedules); 
			if(!isset($schedules[0])){
				foreach($transactions as $t){
					if($t['id']=='INIPY'||$t['id']=='FULLP'){
						$Account = $this->createStudent($_DATA);
						$schedules = $this->AccountSchedule->find('all',array('recursive'=>-1,'conditions'=>array('AccountSchedule.account_id'=>$Account['id'])));
						$fees = $this->AccountFee->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$Account['id'])));
						$account_id = $Account['id'];
						if($Account['payment_total']>0)
							$payment_to_date+=$Account['payment_total'];
					}
					
				}
			}else{
				foreach($transactions as $t){
					if($t['id']=='INIPY'){
						$Account = $this->createStudent($_DATA);
						$schedules = $this->AccountSchedule->find('all',array('recursive'=>-1,'conditions'=>array('AccountSchedule.account_id'=>$Account['id'])));
						$fees = $this->AccountFee->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$Account['id'])));
						$account_id = $Account['id'];
						if($Account['payment_total']>0)
							$payment_to_date+=$Account['payment_total'];
					}
					
				}
			}
			
			//pr($account_id); exit();
			$transac_payments = array();
			$ledger_accounts = array();
			$account_transac = array();
			$account_history = array();
			$transac_details = array();
			$payment_modes = array();
			

			// Todo: Move to new function PrepareTransaction
			$t_payment = 0;
			foreach($this->data['Payment'] as $i=>$t){
				$t_payment += $t['amount'];
				if($t['id']=='CHCK')
					$CHECK = true;
			}
			
			// For transactions table
			$transac_data = array(
								'type'=>'payment',
								'status'=>'fulfilled',
								'booklet_id'=>$booklet['id'],
								'ref_no'=>$curr_refNo,
								'esp' => $ESP,
								'amount'=> $TOTAL_DUE,
								'transac_date'=>$today,
								'transac_time'=>$time,
								'cashier'=>$USERNAME,
								'account_id'=>$account_id);
			if(isset($CHECK))
				$transac_data['amount']=$t_payment;
			
			
			
			$this->Transaction->saveAll($transac_data);
			
			$transac_id = $this->Transaction->id;
			//TODO: end of PrepareTransaction

			
			$isCharge = false;
			// to get the total payment of all payments and save transac payments
			foreach($payments as $i=>$pay){
				$tp = array('transaction_id'=>$transac_id,'payment_method_id'=>$pay['id'],'amount'=>$pay['amount']);
				if($pay['id']!=='CASH'){
					$tp['valid_on']=$pay['date'];
					$tp['details']=$pay['bank'];
					$isCharge = $pay['bank'];
				}
				else
					$tp['details']='Cash';
				array_push($payment_modes,$pay['id']);
				array_push($transac_payments,$tp);
				$total_payment += $pay['amount'];
			}
			$account_total = $Account['discount_amount']+$total_payment;
			
			
			//exit();
			
			// for Ledgers and Account transactions
			$transac_payment = $total_payment;
			foreach($transactions as $trnx){
				
				$detail = $trnx['name'];
				$payment = $total_payment;
				if($trnx['amount']<$total_payment){
					$payment = $trnx['amount'];
					$transac_payment -=$payment;
				}
				if(isset($CHECK))
					$payment_to_date += $total_payment;
				else
					$payment_to_date += $payment;
				// Save to reservation

				//Save to ledger
				if($trnx['type']!=='AR'){
					$ledgerItem =  array(
						'account_id'=>$account_id,
						'type'=>'-',
						'transaction_type_id'=>$trnx['id'],
						'esp'=>$ESP,
						'transac_date'=>$today,
						'transac_time'=>$time,
						'ref_no'=>$curr_refNo,
						'details'=>$detail,
						'amount'=>$payment
					);
				}
				if($trnx['type']!=='AR'&&$isCharge){
					
					$ledgerItem['amount']=$total_payment;
					$ledgerItem['details']=$detail.'/'.$isCharge;
				}
				
				if($trnx['type']=='AR'&&$isCharge){
					$charge =  array(
						'account_id'=>$account_id,
						'type'=>'+',
						'transaction_type_id'=>$trnx['id'],
						'esp'=>$ESP,
						'transac_date'=>$today,
						'transac_time'=>$time,
						'ref_no'=>$curr_refNo,
						'details'=>$detail,
						'amount'=>$payment
					);
					array_push($ledger_accounts,$charge);
					$ledgerItem =  array(
						'account_id'=>$account_id,
						'type'=>'-',
						'transaction_type_id'=>$trnx['id'],
						'esp'=>$ESP,
						'transac_date'=>$today,
						'transac_time'=>$time,
						'ref_no'=>$curr_refNo,
						'details'=>$isCharge,
						'amount'=>$total_payment
					);
				}
				
				// save to account histories
				if($trnx['type']!=='AR'){
					$history = array(
						'account_id'=>$account_id,
						'transac_date'=>$today,
						'transac_time'=>$time,
						'ref_no'=>$curr_refNo,
						'details'=>$detail,
						'flag'=>'-',
						'amount'=>$payment
					);
					if($trnx['id']!=='OLDAC'){
						$history['total_due']=$Account['assessment_total'];
						
						$history['total_paid']=$payment_to_date+abs($Account['discount_amount']);
						$history['balance']=($Account['assessment_total']-abs($Account['discount_amount']))-$payment_to_date;
						$Account['outstanding_balance'] = ($Account['assessment_total']-abs($Account['discount_amount']))-$payment_to_date;
						$Account['payment_total'] = $payment_to_date;
						if(isset($Account['old_balance'])&&$Account['old_balance']>0)
							$Account['outstanding_balance']+=$Account['old_balance'];
					}
				}else{
					$history = array(
						'account_id'=>$account_id,
						'transac_date'=>$today,
						'transac_time'=>$time,
						'ref_no'=>$curr_refNo,
						'details'=>$detail,
						'flag'=>'-',
						'amount'=>$total_payment,
						'total_due'=>0,
						'total_paid'=>0,
						'balance'=>0
					);
				}
				
				
				// save to account transactions
				$acct_transac =  array('account_id'=>$account_id,'transaction_type_id'=>$trnx['id'],'ref_no'=>$curr_refNo,'amount'=>$payment);
				if(isset($CHECK))
					$acct_transac['amount'] = $total_payment;
				// save to transaction details
				$td = array(
					'transaction_id'=>$transac_id,
					'transaction_type_id'=>$trnx['id'],
					'details'=>$detail,
					'amount'=>$total_payment
				);
				if($trnx['type']=='AR'){
					$td['details'] = $trnx['details'];
					$td['amount'] = $total_payment;
				}
				array_push($account_transac,$acct_transac);
				array_push($account_history,$history);
				
				if($trnx['type']!=='AR'){
					array_push($ledger_accounts,$ledgerItem);
				}
				array_push($transac_details,$td);
				if($trnx['id']=='OLDAC'):
					$total_payment -= $trnx['amount'];
					$Account['payment_total'] +=$trnx['amount'];
					$Account['old_balance'] -=$trnx['amount'];
					$Account['outstanding_balance'] -=$trnx['amount'];
				endif;
				if($trnx['type']=='AR')
					$total_payment -= $trnx['amount'];
				
			}
			
			//pr($schedules); exit();
			
			// for account payment schedule
			$sched_payment = $total_payment;
			//pr($sched_payment); exit();
			$account_schedules = array();
			foreach($schedules as $i=>$sched){
				if(isset($sched['AccountSchedule']))
					$sched = $sched['AccountSchedule'];
				if($sched['paid_amount']<$sched['due_amount']){
					$payment_required = $sched['due_amount']-$sched['paid_amount'];
					if($payment_required<=$sched_payment){
						$sched_payment-=$payment_required;
						$sched['status'] = 'PAID';
						$sched['paid_amount'] = $payment_required+$sched['paid_amount'];
					}else{
						$sched['paid_amount'] = $sched_payment+$sched['paid_amount'];
						$sched_payment = 0;
					}
					$sched['paid_date'] = $today;
					
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
			if(isset($_DATA['Reservation'])){
				foreach($_DATA['Reservation'] as $res){
					$fee_payment+=$res['amount'];
				}
			}
			foreach($fees as $i=>$fee){
				if(isset($fee['AccountFee']))
					$fee = $fee['AccountFee'];
				$total_feePaid += $fee['paid_amount'];
				if($fee['fee_id']!='TUI')
					$total_misc += $fee['due_amount'];
			}
			
			if(($total_feePaid+$fee_payment)>$total_misc){
				$Account['rounding_off'] = 0;
				foreach($fees as $i=>$fee){
					$fee = $fee['AccountFee'];
					if($fee['fee_id']=='TUI'&&$fee['paid_amount']>0){
						if($fee_payment>$fee['due_amount'])
							$payment = $fee_payment-$fee['due_amount'];
						else{
							$payment=$fee_payment;
							$fee_payment = 0;
						}
						$fee['paid_amount'] += $payment;
					}else{
						$fee['paid_amount'] = $fee['due_amount'];
						if($fee['fee_id']=='TUI')
							$fee['paid_amount'] = ($total_feePaid+$fee_payment)-$total_misc;
						
					}
					
					array_push($account_fees,$fee);
				}
			}else{
				$round_off = 0;
				foreach($fees as $i=>$fee){
					$fee = $fee['AccountFee'];
					if($fee['fee_id']=='TUI')
						continue;
					$fee['paid_amount'] = ($total_feePaid+$fee_payment) * $fee['percentage'];
					$round_off += $fee['paid_amount'];
					array_push($account_fees,$fee);
				}
				$Account['rounding_off'] = $Account['payment_total']-$round_off;
			}
			
			
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
			$DataCollection['Account']['transaction_id']  = $transac_id;
			foreach($DataCollection as $model=>$collection){
				//pr($collection); continue;
				if($collection){
					if($this->$model->saveAll($collection)){
						$this->Session->setFlash(__('The '. $i .' has been saved', true));
					}
					else{
						$this->Session->setFlash(__('Error saving '.$i, true));
						break;
					}
				}
			}
			$this->data['Payment'] = array('transaction_id'=>$transac_id);
			if($booklet['status'] == 'CONSM')
				$this->data['Payment'] = array('transaction_id'=>$transac_id,'booklet'=>'Consumed');
			$this->set(compact('payments'));
			
		}
	}
	
	function SaveOthers($data){
		//pr($data); exit();
		$today = date("Y-m-d");
		$time = date("h:i:s");
		$booklet = $data['Booklet'];
		$date = $data['Cashier']['date'];
		$n = 0;
		//pr($data); exit();
		if(isset($data['Student']['id'])){
			if($data['Student']['account_type']=='others'){
				$account = $data['Student'];
				$account['payment_total'] += $data['Cashier']['total_due'];
				$account_id = $data['Student']['id'];
				$this->Account->save($account);
			}else{
				$levels = $this->YearLevel->find('all');
				$lvls = array();
				foreach($levels as $lvl){
					array_push($lvls,$lvl['YearLevel']['id']);
				}
				foreach($lvls as $i=>$l){
					if($data['Student']['year_level_id']==$l){
						$res['year_level_id'] = $lvls[$i+1];
					}
				}
				//pr($res); exit();
				if($res['year_level_id']=='GY')
					$res['program_id']='MIXED';
				if(isset($data['Student']['program_id']))
					$res['program_id']=$data['Student']['program_id'];
				// TODO: Add to master_config flag  MOD_ESP  to  advance SY
				$modESP = 0;
				$nextESP =  $data['Cashier']['esp'] +$modESP; 
				$res['account_id'] = $data['Student']['id'];
				$res['esp'] = $nextESP;
				$res['field_type'] = $data['Transaction'][0]['id'];
				$res['ref_no'] = 'OR '.$booklet['series_counter'];
				$res['amount'] = $data['Cashier']['total_due'];
				$res['transac_date'] = $date;
				$this->Reservation->save($res);
				$account_id = $data['Student']['id'];
			}
		}
		else{
			do{
				$n2 = str_pad($n+1, 5, 0, STR_PAD_LEFT);
				$result = $this->Account->find('all',array('recursive'=>0,'conditions'=>array('Account.id'=>'LSO'.$n2)));
				$n++;
			}
			while(!empty($result));
			$account_id = 'LSO'.$n2;
			$acct_data = array(
				'id'=>$account_id,
				'account_type'=>'others',
				'account_details'=>$data['Student']['name'],
				'assessment_total'=> 0,
				'subsidy_status'=>'REGXX',
				'discount_amount'=>0,
				'payment_total'=>$data['Cashier']['total_due'],
				'outstanding_balance'=>0,
				'rounding_off'=>0,
			);
			
			$this->Account->saveAll($acct_data);
		}
			$transac_data = array(
							'type'=>'payment',
							'status'=>'fulfilled',
							'booklet_id'=>$booklet['id'],
							'ref_no'=>'OR '.$booklet['series_counter'],
							'account_details'=>$data['Student']['name'],
							'esp' => $data['Cashier']['esp'],
							'amount'=> $data['Cashier']['total_due'],
							'transac_date'=>$date,
							'transac_time'=>$time,
							'cashier'=>$this->Auth->user()['User']['username'],
							'account_id'=>$account_id);
			$this->Transaction->saveAll($transac_data);
			$transac_id = $this->Transaction->id;
			
			
		
		$tr_details = array();
		
		foreach($data['Transaction'] as $i=>$trnx){
			
			$td = array(
				'transaction_id'=>$transac_id,
				'transaction_type_id'=>$trnx['id'],
				'amount'=>$data['Cashier']['total_due']
			);
			if(isset($trnx['details']))
				$td['details'] = $trnx['details'];
			else
				$td['details'] = $trnx['name'];
			//pr($td); exit();
			array_push($tr_details,$td);
		}
		
		$tr_payments = array();
		
		foreach($data['Payment'] as $i=>$pay){
			$tp = array('transaction_id'=>$transac_id,'payment_method_id'=>$pay['id'],'amount'=>$pay['amount']);
			if($pay['id']!=='CASH'){
				$tp['valid_on']=$pay['date'];
				$tp['details']=$pay['bank'];
				$isCharge = $pay['bank'];
			}
			else
				$tp['details']='Cash';
			array_push($tr_payments,$tp);
		}
		$booklet = $this->checkBooklet($data);
		//$booklet['series_counter']++;
		$DataCollection = array(
			'TransactionPayment'=>$tr_payments,
			'TransactionDetail'=>$tr_details,
			'Booklet'=>$booklet,
		);
		//pr($DataCollection); exit();
		foreach($DataCollection as $model=>$collection){
			if($collection){
				if($this->$model->saveAll($collection)){
					$this->Session->setFlash(__('The '. $model .' has been saved', true));
				}
				else{
					$this->Session->setFlash(__('Error saving '.$model, true));
					break;
				}
			}
		}
		
		$this->data['Payment'] = array('transaction_id'=>$transac_id);
		if($booklet['status'] == 'CONSM')
			$this->data['Payment'] = array('transaction_id'=>$transac_id,'booklet'=>'Consumed');
		$this->set(compact('payments'));
	}

	function SaveInquiry($data){
		//pr($data); exit();
		$account_id = $data['Student']['id'];
		$today = date("Y-m-d");
		$time = date("h:i:s");
		$booklet = $data['Booklet'];
		$date = $data['Cashier']['date'];
		
		$docType = $this->data['Type']['type'];
		$refNoType =  $docType;
		if($docType=='A2O'){
			$refNoType =  'OR';
		}
		$transac_data = array(
						'type'=>'payment',
						'status'=>'fulfilled',
						'booklet_id'=>$booklet['id'],
						'ref_no'=>$refNoType.' '.$booklet['series_counter'],
						'account_details'=>$data['Student']['name'],
						'esp' => $data['Cashier']['esp'],
						'amount'=> $data['Cashier']['total_due'],
						'transac_date'=>$date,
						'transac_time'=>$time,
						'cashier'=>$this->Auth->user()['User']['username'],
						'account_id'=>$account_id);
		$this->Transaction->saveAll($transac_data);
		$transac_id = $this->Transaction->id;
		
		
		$tr_details = array();
		
		foreach($data['Transaction'] as $i=>$trnx){
			$td = array(
				'transaction_id'=>$transac_id,
				'transaction_type_id'=>$trnx['id'],
				'details'=>$trnx['name'],
				'amount'=>$trnx['amount']
			);
			// TODO: Add to master_config flag  MOD_ESP  to  advance SY

			$modESP = 0;
			if($trnx['id']=='RSRVE'||$trnx['id']=='ADVTP'){
				$nextESP = $data['Cashier']['esp']+$modESP;
				$rsrveObj =  array(
					'account_id'=>$account_id,
					'esp'=>$nextESP,
					'field_type'=>$trnx['id'],
					'year_level_id'=>$data['Student']['year_level'],
					'ref_no'=>$transac_data['ref_no'],
					'amount'=>$trnx['amount'],
					'transac_date'=>$date
				);
				$this->Reservation->save($rsrveObj);
			}
			array_push($tr_details,$td);
		}
		
		$tr_payments = array();
		
		foreach($data['Payment'] as $i=>$pay){
			$tp = array('transaction_id'=>$transac_id,'payment_method_id'=>$pay['id'],'amount'=>$pay['amount']);
			if($pay['id']!=='CASH'){
				$tp['valid_on']=$pay['date'];
				$tp['details']=$pay['bank'];
				$isCharge = $pay['bank'];
			}
			else
				$tp['details']='Cash';
			array_push($tr_payments,$tp);
		}
		$booklet = $this->checkBooklet($data);
		//$booklet['series_counter']++;
		$DataCollection = array(
			'TransactionPayment'=>$tr_payments,
			'TransactionDetail'=>$tr_details,
			'Booklet'=>$booklet,
		);
		//pr($DataCollection); exit();
		foreach($DataCollection as $model=>$collection){
			if($collection){
				if($this->$model->saveAll($collection)){
					$this->Session->setFlash(__('The '. $model .' has been saved', true));
				}
				else{
					$this->Session->setFlash(__('Error saving '.$model, true));
					break;
				}
			}
		}
		
		$this->data['Payment'] = array('transaction_id'=>$transac_id);
		if($booklet['status'] == 'CONSM')
			$this->data['Payment'] = array('transaction_id'=>$transac_id,'booklet'=>'Consumed');
		$this->set(compact('payments'));
	}

	
	
	function checkBooklet($data){
		$booklet = $data['Booklet'];
		//pr($booklet); exit();
		if($booklet['series_counter']<$booklet['series_end']){
			if(isset($booklet['mark'])){
				if($booklet['mark']=='bypass'){
					$booklet['series_counter']=$booklet['InitialCtr'];
				}else{
					$series=$booklet['series_counter']+1;
					do{
					$result = $this->Ledger->find('first',array('recursive'=>0,'conditions'=>array('Ledger.ref_no'=>'OR '.$series)));
						$series++;
					}while(!empty($result));
					$series--;
					$booklet['series_counter'] = $series;
				}
			}else{
				$series=$booklet['series_counter']+1;
				do{
				$result = $this->Ledger->find('first',array('recursive'=>0,'conditions'=>array('Ledger.ref_no'=>'OR '.$series)));
					$series++;
				}while(!empty($result));
				$series--;
				$booklet['series_counter'] = $series;
			}
		}else
			$booklet['status'] = 'CONSM';
		return $booklet;
	}
	
	
	function createStudent($all_info){
		//pr($all_info); exit();
		
		$today =  date("Y-m-d", strtotime($this->data['Cashier']['date']));
		$time = date("h:i:s");
		if(isset($all_info['StudInfo']))
			$data = $all_info['StudInfo'];
		
		$sy = $all_info['Assessment']['esp'];
		$sy = explode('.',$sy);
		//pr($sy[1]); exit();
		$ass = $all_info['Assessment'];
		$data['status'] = 'NROLD';
		$ass['status'] = 'NROLD';
		$assessment_data = array('id'=>$ass['id'],'status'=>$ass['status']);
		$esp = $all_info['Cashier']['esp'];
		if(isset($all_info['Cashier']['date']))
			$today = $all_info['Cashier']['date'];
		//update assessment status
		//pr($assessment_data); exit();
		$this->Assessment->save($assessment_data);
		$assessment_id = $ass['id'];
		$hs = array('G7','G8','G9','GX');
		//pr($data); exit();
		
		$isEnrolled = false; 


		if($ass['student_status']=='New'){
			
			//update inquiry status and account_type in accounts
			$this->Inquiry->save($data);
			$acct = array('id'=>$ass['student_id'],'account_type'=>'enrolled');
			$this->Account->save($acct);
			if(in_array($data['year_level_id'],$hs))
				$data['id'] = $this->Student->generateSID('LS','J');
			else
				$data['id'] = $this->Student->generateSID('LS','S');
			
			$sy =  floor($esp);
			$data['sno'] = $this->Student->generateSNO($sy);

			$ass['id'] = $data['id'];
			$curr_yearlvl = $data['year_level_id'];
			$data['section_id'] = $ass['section_id'];
			
			$data['program_id'] = $all_info['StudInfo']['program_id'];
			
			//save new student to student201 in SER
			$this->Student->save($data);
		}else{
			
			//if old student, modify data according to assessment
			$account = $this->Account->findById($ass['student_id']);
			$account = $account['Account'];
			$ass['id'] = $ass['student_id'];
			$yl = array('G7','G8','G9','GX','GY','GZ');
			$yindex = array_search($all_info['Student']['year_level_id'],$yl);
			if($sy[1]!=3){
				$ass['old_balance'] = $account['outstanding_balance'];
				$curr_yearlvl = $yl[$yindex+1];
			}
			else
				$curr_yearlvl = $yl[$yindex];
				
			$NROL_ESP =  $esp;
			if(!in_array($curr_yearlvl,$hs))
				$NROL_ESP = $esp+0.1;

			// Check for existing CLB records
			$checkCond = array(
						array('ClasslistBlock.student_id'=>$ass['id'],
							'ClasslistBlock.esp'=>$NROL_ESP));
			$isEnrolled = $this->ClasslistBlock->find('count', array('conditions'=>$checkCond));

			// Update only if not enrolled
			if(!$isEnrolled):
				$stud201 = array('id'=>$ass['id'],
								'year_level_id'=>$curr_yearlvl,
								'section_id'=>$ass['section_id'],
								'program_id'=>$program_id);
				$this->Student->save($stud201);
			endif;
		}
		
		
		//save to accounts
		$this->Account->save($ass);
		
		//add items to ledgers
		$isAdjustment = false;
		if($all_info['Assessment']['account_details']=='Adjust'){
			$isAdjustment = true;
			$adjustment = $ass['assessment_total']-$all_info['Student']['assessment_total'];
			$tuition = array('account_id'=>$ass['id'],'type'=>'+','transaction_type_id'=>'ADJST','esp'=>$esp,'transac_date'=>$today,'transac_time'=>$time,'ref_no'=>$assessment_id,'details'=>'Tuition Adjustment','amount'=>$adjustment);
		}else
			$tuition = array('account_id'=>$ass['id'],'type'=>'+','transaction_type_id'=>'TUIXN','esp'=>$esp,'transac_date'=>$today,'transac_time'=>$time,'ref_no'=>$assessment_id,'details'=>'Tuition and Other Fees','amount'=>$ass['assessment_total']);
		$this->Ledger->saveAll($tuition);
		if($ass['subsidy_status']!='REGXX'&&!$isAdjustment){
			$discount = array('account_id'=>$ass['id'],'type'=>'-','transaction_type_id'=>$ass['subsidy_status'],'esp'=>$esp,'transac_date'=>$today,'transac_time'=>$time,'ref_no'=>$assessment_id,'details'=>'Discount','amount'=>abs($ass['discount_amount']));
			$this->Ledger->saveAll($discount);
		}
		if(isset($all_info['Reservation'])){
			foreach($all_info['Reservation'] as $res){
				switch($res['field_type']){
					case 'RSRVE': $res['details'] = 'Reservation'; break;
					case 'ADVTP': $res['details'] = 'Advance Payment'; break;
				}
				$res['transaction_type_id']=$res['field_type'];
				$res['transac_date']=$today;
				$res['ref_no']=$assessment_id;
				$res['type']='-';
				$res['id'] = null;
				$res['account_id'] = $ass['id'];
				$this->Ledger->saveAll($res);
			}
		}
		
		// Add Record to CLB upon INIPY or FULPY
		//save to classlist blocks
		if(!in_array($curr_yearlvl,$hs))
			$esp = $esp+0.1;
		$classlist_block = array('student_id'=>$ass['id'],
								'section_id'=>$ass['section_id'],
								'esp'=>$esp,'status'=>'ACT');
		
		// CLB Add new record
		if(!$isEnrolled)
 			$this->ClasslistBlock->save($classlist_block);
		
		
		//Save to Irregular
		if($ass['account_details']=='Irregular'||$ass['account_details']=='Adjust'){
			foreach($ass['Subject'] as $sub){
				$irreg = array(
					'student_id'=>$ass['id'],
					'section_id'=>$sub['section_id'],
					'subject_id'=>$sub['subject_id'],
					'status'=>'TKG',
					'esp'=>$ass['esp']
				);
			}
		}
		
		//save account schedule and fees
		$paysched = array();
		$fees = array();
		if(!$isAdjustment){
			foreach($ass['Paysched'] as $i=>$ps){
				$ps['account_id'] = $ass['id'];
				if(!$ps['paid_amount'])
					$ps['paid_amount'] = 0;
				array_push($paysched,$ps);
			}
			$this->AccountSchedule->saveAll($paysched);
		}
		foreach($ass['Fee'] as $i=>$f){
			$f['account_id'] = $ass['id'];
			array_push($fees,$f);
		}
		
		$this->AccountFee->saveAll($fees);
		return $ass;
	}
	
	function logData($__DATA){
		$__DATA['__'] =  date('Y-m-d-h-i-A',time());
		$__DATA['__USER'] = $this->Auth->user();
		$_BKLT = $__DATA['Booklet'];
		$filename = sprintf("%s-%s-%s.txt",$_BKLT['receipt_type'],$_BKLT['series_counter'],$__DATA['__'] );
		$path = APP.DS.'tmp'.DS.'logs'.DS.'payments'.DS.$filename;
		
		$content =  json_encode($__DATA, JSON_PRETTY_PRINT);
		file_put_contents($path,$content);
	}
}