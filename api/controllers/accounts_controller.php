<?php
class AccountsController extends AppController {

	var $name = 'Accounts';
	var $uses = array('Account','AccountSchedule','AccountFee','Assessment');

	function index() {
		$this->Account->recursive = 0;
		$accounts =  $this->paginate();
		//pr($this->paginate); exit();
		if($this->isAPIRequest()){
			$yrLevels = $this->Account->Student->YearLevel->find('list',array('fields'=>array('id','description')));
			$sections = $this->Account->Student->YearLevel->Section->find('list',array('fields'=>array('id','description')));
			

			foreach($accounts as $i =>$acc){
				$stud =  $acc['Student'];
				$inqu =  $acc['Inquiry'];
				//pr($acc); exit();
				if($acc['Account']['account_type']=='student'){
					$paySched =  $acc['AccountSchedule'];
					$yrlvId =  $acc['Student']['year_level_id'];
					$sectId =  $acc['Student']['section_id'];
					$yearLevel = "";
					$section = "";
				
					if(isset($yrLevels[$yrlvId]))
						$yearLevel = $yrLevels[$yrlvId];
					if(isset($sections[$sectId]))
						$section = $sections[$sectId];
					if(isset($stud['full_name']))
						$acc['Account']['name'] =$stud['full_name'];
					else
						$acc['Account']['name'] =$stud['first_name'].' '.$stud['middle_name'].' '.$stud['last_name'];
						
					$acc['Account']['sno'] =$stud['sno'];
					$acc['Account']['rfid'] =$stud['rfid'];
					
					$acc['Account']['year_level'] =$yearLevel;
					$acc['Account']['year_level_id'] =$yrlvId;
					$acc['Account']['section'] =$section;
					$acc['Account']['program_id'] =$acc['Student']['program_id'];
					$acc['Account']['section_id'] =$sectId;
					$acc['Account']['Paysched'] = $paySched;
				}else if($inqu){
					$yrlvId =  $acc['Inquiry']['year_level_id'];
					$yearLevel = "";
					$section = "";
					//pr($acc); exit();
					if(isset($yrLevels[$yrlvId]))
						$yearLevel = $yrLevels[$yrlvId];
					//pr($inqu);
					$acc['Account']['name'] =$inqu['full_name'];
					if($inqu['full_name']==null)
						$acc['Account']['name'] = $inqu['first_name'].' '.$inqu['last_name'];
					$acc['Account']['sno'] =$acc['Account']['id'];
					
					$acc['Account']['year_level'] =$yearLevel;
					$acc['Account']['year_level'] =$yrlvId;
					$acc['Account']['section'] =$section;
					
					/* if(!isset($acc['Inquiry']['program_id']))
						pr($acc); */
					$acc['Account']['program_id'] =$acc['Inquiry']['program_id'];
				}
				//
				$acc['Account']['account_no'] =$acc['Account']['id'];
				//pr($acc); exit();
				//$acc['department_id'] = $stud['department_id'];
				$accounts[$i]=$acc;
			}
		}
		//exit();
		//TODOS: Adjust data based on tests/accounts.js
		$this->set('accounts',$accounts);
	}

	function view($id = null,$inqId=null, $refNo=null) {
		if($id=='init_new_student') return $this->init_new_student($inqId,$refNo);
		if (!$id) {
			$this->Session->setFlash(__('Invalid account', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('account', $this->Account->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Account->create();
			$this->data['Account']['id']=$this->Account->generateId('LSO');
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash(__('The account has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash(__('The account has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Account->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Account->delete($id)) {
			$this->Session->setFlash(__('Account deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Process a new payment based on a transaction.
	 *
	 * @param array $transaction The transaction details containing ESP, reference number, and account ID.
	 */
	function new_payment($transaction) {
	    // Check if the transaction details are set
	    if (isset($transaction)) {
	        // Extract relevant information from the transaction
	        $esp = $transaction['esp'];  
	        $ref_no = $transaction['series_no']; 
	        $account_id = $transaction['account_id']; 
	        // Iterate through transaction details
	        foreach ($transaction['details'] as $dtl) {
	            // Check if the detail is for account schedule ('SBQPY')
	            $TXID = $dtl['id'];
	            switch($TXID){
	            	case 'INIPY': case 'INRES':
	            		$is_new_stud  =substr($account_id, 0, 3) === 'LSN';
	            		$new_account_id = null;
	            		if($is_new_stud):
	            			$new_account_id = $this->new_student($account_id,$esp);
	            		endif;
	            		$this->setup_account($account_id,$esp,$new_account_id);
	            		$account = $this->forward_payment($account_id,$ref_no,$dtl,$esp);
	            		$account['account_id']=$account_id;
	            		return $account;
	            	break;
	            	case 'SBQPY': case 'REGFE':
	            		$account = $this->forward_payment($account_id,$ref_no,$dtl,$esp);
		              	return $account;
	            	break;
	            }
	        }
	    }
	}

	function new_student($inquiry_id,$sy){
		App::import('Model','Inquiry');
		$INQ = new Inquiry();
		$INQ->recursive=0;
		$IObj = $INQ->findById($inquiry_id);
		$IInfo = $IObj['Inquiry'];
		$STU = $this->Account->Student->createNew201($IInfo,$sy);
		$SID = $STU['id'];
		return $SID;

	}

	function setup_account(&$account_id,$esp,$new_account_id=null){
		$esp = $esp +0.25;
		$AObj = $this->Assessment->getDetails($account_id,$esp);
		$account_id = $this->Account->setupDetails($AObj,$new_account_id);
		$section_id = $AObj['Assessment']['section_id'];
		$this->Account->Student->updateSection($account_id,$section_id,$esp);
		return $AObj;
	}

	function forward_payment($account_id,$ref_no,$dtl,$esp){
		 // Extract the amount from the detail
        $amount = $dtl['amount'];
		$trnxObj = array('id'=>$dtl['id'],'name'=>$dtl['description']);
		$source = 'cashier2';
        // Forward the payment using the Account model's forwardPayment function
      	$account=  $this->Account->forwardPayment($account_id, $esp, $ref_no, $amount,$trnxObj,$source);
      	return $account;
	}

	function init_new_student($inquiry_id,$ref_no){
		App::import('Model','Transaction');
		$TNX = new Transaction();
		$TObj = $TNX->findByRefNo($ref_no);
		pr($ref_no);
		$transaction = $TObj['Transaction'];
		$transaction['series_no'] = $TObj['Transaction']['ref_no'];
		$transaction['details'] = $TObj['TransactionDetail'];
		foreach($transaction['details'] as $i=>$o):
			$transaction['details'][$i]['id']=$o['transaction_type_id'];
			$transaction['details'][$i]['description']=$o['details'];
		endforeach;
		pr($transaction);
		$newPay = $this->new_payment($transaction);
		$transaction['account'] = $newPay;
		$transaction =array('Ledger'=>$transaction);

		pr($newPay);
		try {
		    $ledger_action = $this->requestAction('/ledgers/new_payment',array('pass'=>$transaction,'action'=>'new_payment'));
		} catch (Exception $e) {
		    $this->log($e->getMessage(), 'error');
		}
		return $transaction;
		
	}
}
