<?php
class TransactionsController extends AppController {

	var $name = 'Transactions';
	var $uses = array('Transaction','TransactionDetail','AccountHistory','AccountSchedule','Ledger','Account','AccountFee');

	function index() {
		$this->Transaction->recursive = 0;
		$this->paginate['Transaction']['contain'] = array('Account','Inquiry','Student','TransactionDetail');
		$transacs = $this->paginate();
		if($this->isAPIRequest()){
			foreach($transacs as $i=>$t){
				//pr($t);
				$details = array();
				foreach($t['TransactionDetail'] as $x=>$d){
					array_push($details,$d);
				}
				$t['Transaction']['account_type'] = $t['Account']['account_type'];
				$t['Transaction']['details'] = $details;
				if($t['Account']['account_type']!=='others'){
					if(isset($t['Student']['full_name'])){
						$t['Transaction']['name'] = $t['Student']['full_name'];
						$t['Transaction']['sno'] = $t['Student']['sno'];
						
					}else
						$t['Transaction']['name'] = $t['Inquiry']['full_name'];
				}else{
					$t['Transaction']['name'] = $t['Account']['account_details'];
				}
				
				$transacs[$i]['Transaction'] = $t['Transaction'];
			}
		}
		//exit();
		$this->set('transactions', $transacs);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid transaction', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('transaction', $this->Transaction->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Transaction->create();
			$transac = $this->data['Transaction'];
			if($transac['action']=='cancel'){
				$isOthers = false;
				
				if($transac['transac']['account_type']=='others')
					$isOthers = true;
				
				$time = date("h:i:s");
				$transac = $transac['transac'];
				$transac['id'] = '';
				$transac['status'] = 'cancelled';
				$ref_no = $transac['ref_no'];
				$amount = (double)str_replace(",","",$transac['amount']);
				
				$transac['ref_no'] = 'X'.$transac['ref_no'];
				$transac['amount'] = '-'.$amount;
				$transac['transac_time'] = $time;
				
				//Save to Transactions
				$success = $this->Transaction->saveAll($transac);
				
				$transac_id = $this->Transaction->id;
				$transac_dtl = array();
				$transac_dtl['transaction_id'] = $transac_id;
				$transac_dtl['transaction_type_id'] = 'REVRS';
				$transac_dtl['details'] = 'Reversal for '. $ref_no;
				$transac_dtl['amount'] = '-'.$amount;
				
				//Save to Transaction Detail
				$this->TransactionDetail->saveAll($transac_dtl);
				
				$account = $this->Account->find('first',array('recursive'=>0,'conditions'=>array('id'=>$transac['account_id'])));
				$account = $account['Account'];
				$account['outstanding_balance'] += $amount;
				$account['payment_total'] -= $amount;
				
				//Update Account
				if(!$isOthers)
					$this->Account->saveAll($account);
				else
					$success = $this->Account->saveAll($account);
				
				$acct_history = $transac;
				$acct_history['balance'] = $account['outstanding_balance']; 
				$acct_history['total_paid'] = $account['discount_amount']+$account['payment_total']; 
				$acct_history['total_due'] = $account['assessment_total']; 
				$acct_history['flag'] ='-'; 
				$acct_history['details'] =$transac_dtl['details']; 
				
				if(!$isOthers)
					$this->AccountHistory->saveAll($acct_history);
				
				$sched = $this->AccountSchedule->find('all',array('recursive'=>-1,'order'=>'AccountSchedule.order DESC','conditions'=>array('AccountSchedule.account_id'=>$transac['account_id'])));
				
				$total = $amount;
				$schedules = array();
				foreach($sched as $i=>$sch){
					$sc = $sch['AccountSchedule'];
					if($sc['paid_amount']>0){
						if($amount>0){
							if($amount>$sc['paid_amount'])
								$sc['paid_amount']=0;
							else
								$sc['paid_amount']-=$amount;
							$sc['paid_date']=null;
							$sc['status']='NONE';
							//$this->AccountSchedule->saveAll($sc);
							array_push($schedules,$sc);
							$amount-=$sc['due_amount'];
							
						}else
							break;
					}else
						continue;
				}
				if(!$isOthers)
					$this->AccountSchedule->saveAll($schedules);
				//$sched = $sched['AccountSchedule'];
				
				$fees = $this->AccountFee->find('all',array('recursive'=>0,'conditions'=>array('account_id'=>$account['id'])));
				$isPaidMisc = false;
				
				foreach($fees as $i=>$fee){
					$fee = $fee['AccountFee'];
					if($fee['fee_id']=='TUI'&&$fee['paid_amount']>0){
						$isPaidMisc = true;
						$fee['paid_amount'] -= $total;
						$fees[$i]['AccountFee'] = $fee;
					}
				}
				
				if($isPaidMisc==false){
					$forFees = $total;
					foreach($fees as $i=>$fee){
						$fee = $fee['AccountFee'];
						if($fee['paid_amount']>0&&$forFees>0){
							$forFees-=$fee['paid_amount'];
							$fee['paid_amount'] = 0;
							$fees[$i] = $fee;
						}
					}
				}
				
				//Update Account Fees
				if(!$isOthers)
					$this->AccountFee->saveAll($fees);
				
				$ledger = $transac;
				$ledger['type'] = '+';
				$ledger['amount'] = $total;
				$ledger['details'] = $transac_dtl['details'];
				
				if(!$isOthers)
					$this->Ledger->save($ledger);
				
				if($success){
					$this->Session->setFlash(__('The transaction has been saved', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
				}
				
			}
			else{
				if ($this->Transaction->save($transac)) {
					$this->Session->setFlash(__('The transaction has been saved', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
				}
			}
		}
		$accounts = $this->Transaction->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid transaction', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Transaction->save($this->data)) {
				$this->Session->setFlash(__('The transaction has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Transaction->read(null, $id);
		}
		$accounts = $this->Transaction->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for transaction', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Transaction->delete($id)) {
			$this->Session->setFlash(__('Transaction deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Transaction was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
