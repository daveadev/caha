<?php
class TransactionTypesController extends AppController {

	var $name = 'TransactionTypes';

	function index() {
		$this->TransactionType->recursive = 1;
		$this->paginate['TransactionType']['recursive']=-1;
		$this->paginate = $this->TransactionType->preparePagination($this->paginate);
		$transactionTypes =  $this->paginate();
		foreach($transactionTypes as $i=>$tty){
			$T = $tty['TransactionType'];
			//Compute for the correct amount for SP
			if($T['amounts']&&$T['id']=='SBQPY'):
				$amounts =  array();
				$due_dates =  array();
				// Deconstruct due dates and amounts
				foreach(explode(',', $T['amounts']) as $amt){
					$a = explode('/', $amt);
					array_push($due_dates,$a[0]);
					array_push($amounts,$a[1]);
				}
				// Deconstruct description
				$desc = explode(',', $T['description']);
				// Convert first bill month and current month to year and month
				$billYrMo =  (int)date("Ym",strtotime($due_dates[0]));
				$currYrMo = (int)date("Ym",time());
				
				// Build data of first item
				$dueDate = $due_dates[0];
				$dueAmount = $amounts[0];
				$__description =  $desc[0];
				$__amounts =  $dueDate.'/'.$dueAmount;
				// If over due loop apply correction
				if($billYrMo<=$currYrMo){
					$dueAmount = 0;
					$__description = array();
					$__amounts = array();
					// Loop into the applicable month until currYrMo
					for($j=0,$b=$billYrMo;$b<=$currYrMo;$j++,$b++){
						$dueAmount+=(float)$amounts[$j];
						array_push($__amounts,$due_dates[$j].'/'.$amounts[$j]);
						array_push($__description,$desc[$j]);						
					}
					// Display desc and amts 
					$__description =  implode(',', $__description);
					$__amounts =  implode(',', $__amounts);
				}
				// Tokenize amounts
				$__token =  md5($__amounts);
				// Build new T data
				$T['token'] = $__token;
				$T['amounts'] = $__amounts;
				$T['description'] = $__description;
				$T['amount'] = $dueAmount;
				$transactionTypes[$i]['TransactionType']=$T;
			endif;
		}
		$this->set(compact('transactionTypes'));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid transaction type', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('transactionType', $this->TransactionType->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->TransactionType->create();
			if ($this->TransactionType->save($this->data)) {
				$this->Session->setFlash(__('The transaction type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction type could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid transaction type', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->TransactionType->save($this->data)) {
				$this->Session->setFlash(__('The transaction type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction type could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TransactionType->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for transaction type', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TransactionType->delete($id)) {
			$this->Session->setFlash(__('Transaction type deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Transaction type was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
