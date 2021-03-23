<?php
class TransactionsController extends AppController {

	var $name = 'Transactions';

	function index() {
		$this->Transaction->recursive = 0;
		$transacs = $this->paginate();
		if($this->isAPIRequest()){
			foreach($transacs as $i=>$t){
				//pr($t);
				$details = array();
				foreach($t['TransactionDetail'] as $x=>$d){
					array_push($details,$d);
				}
				$t['Transaction']['details'] = $details;
				if(isset($t['Student']['full_name'])){
					$t['Transaction']['name'] = $t['Student']['full_name'];
					$t['Transaction']['sno'] = $t['Student']['sno'];
					
				}else
					$t['Transaction']['name'] = $t['Inquiry']['full_name'];
				$transacs[$i]['Transaction'] = $t['Transaction'];
			}
		}
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
			if ($this->Transaction->save($this->data)) {
				$this->Session->setFlash(__('The transaction has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.', true));
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
