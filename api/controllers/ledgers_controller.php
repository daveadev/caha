<?php
class LedgersController extends AppController {

	var $name = 'Ledgers';
	var $uses = array('Ledger','Account');

	function index() {
		$this->Ledger->recursive = 0;
		$ledgers = $this->paginate();
		if($this->isAPIRequest()){
			foreach($ledgers as $i=>$led){
				//pr($led);
				$data = $led['Ledger'];
				$inq = $led['Inquiry'];
				if(isset($led['Student']['id'])){
					$data['account_name'] = $led['Student']['full_name'];
					$data['account_no'] = $led['Student']['id'];
				}else{
					$ledatad['account_no'] = $led['Inquiry']['id'];
					$data['account_name'] = $inq['first_name'].' '.$inq['last_name'];
				}
				$ledgers[$i]['Ledger'] = $data;
			}
		}
		//exit();
		$this->set('ledgers', $ledgers);
	}

	function view($id = null) {
		if($id=='testRefNo'){
			$this->testRefNo();
		}
		if (!$id) {
			$this->Session->setFlash(__('Invalid ledger', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('ledger', $this->Ledger->read(null, $id));
	}

	function add() {
		//pr($this->data); exit();
		
		if (!empty($this->data)) {
			$this->Ledger->create();
			if(isset($this->data['Ledger']['bulk'])){
				$bulk = $this->data['Ledger']['bulk'];
				if($this->Ledger->saveAll($bulk)){
					$account_ids = array();
					foreach($bulk as $i=>$ledger){
						$acc_id = $ledger['account_id'];
						if(!in_array($acc_id,$account_ids)){
							array_push($account_ids,$acc_id);
						}
					}
					
					foreach($account_ids as $id){
						
						$leds = $this->Ledger->find('all',array('recursive'=>0,'conditions'=>array('Ledger.account_id'=>$id)));
						$payment = 0;
						$data = array();
						foreach($leds as $i=>$led){
							$ledger = $led['Ledger'];
							switch($led['Ledger']['transaction_type_id']){
								case 'TUIXN':
									$data['id'] = $id;
									$data['ref_no'] = $ledger['ref_no'];
									$data['account_type'] = 'student';
									$data['assessment_total'] = $ledger['amount'];
									break;
								case 'DSESC':
									$data['discount_amount'] = $ledger['amount'];
									$data['subsidy_status'] = $ledger['transaction_type_id'];
									break;
								case 'DSQVR':
									$data['discount_amount'] = $ledger['amount'];
									$data['subsidy_status'] = $ledger['transaction_type_id'];
									break;
								case 'DSPUB':
									$data['discount_amount'] = $ledger['amount'];
									$data['subsidy_status'] = $ledger['transaction_type_id'];
									break;
								case 'INIPY':
									$payment += $ledger['amount'];
									break;
								case 'SBQPY':
									$payment += $ledger['amount'];
									break;
							}
						}
						$data['payment_total'] = $payment;
						if(!isset($data['discount_amount'])){
							$data['discount_amount'] = 0;
							$data['subsidy_status'] = 'REGXX';
						}
						$data['outstanding_balance'] = $data['assessment_total'] - ($payment + $data['discount_amount']);
						
						$this->Account->saveAll($data);
					}
					
					$this->Session->setFlash(__('The ledger has been saved', true));
					$this->redirect(array('action' => 'index'));
				}
			}
			else{
				//pr($this->data); exit();
				if(!isset($this->data['Ledger']['ref_no'])){
					$sy = $this->data['Ledger']['sy'];
					$pref = substr($this->data['Ledger']['transaction_type_id'],0,3);
					$ref_no = $this->Ledger->generateREFNO($sy,$pref);
					//pr($ref_no); exit();
					$this->data['Ledger']['ref_no']=$ref_no;
				}
				if ($this->Ledger->save($this->data)) {
					$this->Session->setFlash(__('The ledger has been saved', true));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The ledger could not be saved. Please, try again.', true));
				}
			}
		}
		
		$accounts = $this->Ledger->Account->find('list');
		$this->set(compact('accounts'));
		
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid ledger', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Ledger->save($this->data)) {
				$this->Session->setFlash(__('The ledger has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ledger could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Ledger->read(null, $id);
		}
		$accounts = $this->Ledger->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for ledger', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Ledger->delete($id)) {
			$this->Session->setFlash(__('Ledger deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Ledger was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	function testRefNo(){
		// Refund
		echo $this->Ledger->generateREFNO(2021,'RFP');
		echo "<br/>";
		// Sponsorship
		echo $this->Ledger->generateREFNO(2020,'SPO');
		exit;
	}

	function correction($sno=null){
		if($this->data):
			$corrections = $this->data['Ledger']['corrections'];
			$this->Account->AccountSchedule->saveAll($corrections);
		endif;

		if($sno):
			$this->Account->Student->recursive=-1;
			$S =$this->Account->Student->findBySno($sno);
			$student = $S['Student'];
			$actNo = $student['id'];
			$A = $this->Account->findById($actNo);
			$sched = $A['AccountSchedule'];
			
			$hasCorrections = false;
			// Load tuition by level and amount
			App::import('Model','Tuition');
			$T =  new Tuition();
			$sy = 2022;
			$yl =  $A['Student']['year_level_id'];
			$amt=  $A['Account']['assessment_total'];
			$TUI = $T->getTuiDetail($sy,$yl,$amt);

			// Load Payscheme based on TUIID
			App::import('Model','PaymentScheme');
			$P = new PaymentScheme();
			$tui =  $TUI['id'];
			$sch =  $A['Account']['payment_scheme'];
			$PSC = $P->getPSDetail($tui,$sch);
			$pss = $PSC['PaymentSchemeSchedule'];
			foreach($sched as $si=>$so):
				$so['og_bill_month']=$so['bill_month'];
				$so['og_due_amount']=$so['due_amount'];
				$so['bill_month']=$pss[$si]['billing_period_id'];
				$so['due_amount']=$pss[$si]['amount'];
				$so['hasErr'] =  $so['due_amount']!=$so['og_due_amount'];
				$so['hasErr'] = $so['hasErr'] || ($so['og_bill_month']!=$so['bill_month']);
				$sched[$si]=$so;
				$hasCorrections = $hasCorrections || $so['hasErr'];
			endforeach;

			$this->set('sched',$sched);
			$this->set('student',$student);
			$this->set('hasCorrections',$hasCorrections);

		endif;
	}
	
	
}
