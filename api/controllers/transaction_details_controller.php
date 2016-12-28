<?php
class TransactionDetailsController extends AppController {

	var $name = 'TransactionDetails';

	function index() {
		$this->TransactionDetail->recursive = 0;
		$this->set('transactionDetails', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid transaction detail', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('transactionDetail', $this->TransactionDetail->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->TransactionDetail->create();
			if ($this->TransactionDetail->save($this->data)) {
				$this->Session->setFlash(__('The transaction detail has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction detail could not be saved. Please, try again.', true));
			}
		}
		$transactions = $this->TransactionDetail->Transaction->find('list');
		$this->set(compact('transactions'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid transaction detail', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->TransactionDetail->save($this->data)) {
				$this->Session->setFlash(__('The transaction detail has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction detail could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TransactionDetail->read(null, $id);
		}
		$transactions = $this->TransactionDetail->Transaction->find('list');
		$this->set(compact('transactions'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for transaction detail', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TransactionDetail->delete($id)) {
			$this->Session->setFlash(__('Transaction detail deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Transaction detail was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
