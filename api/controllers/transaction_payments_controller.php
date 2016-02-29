<?php
class TransactionPaymentsController extends AppController {

	var $name = 'TransactionPayments';

	function index() {
		$this->TransactionPayment->recursive = 0;
		$this->set('transactionPayments', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid transaction payment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('transactionPayment', $this->TransactionPayment->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->TransactionPayment->create();
			if ($this->TransactionPayment->save($this->data)) {
				$this->Session->setFlash(__('The transaction payment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction payment could not be saved. Please, try again.', true));
			}
		}
		$transactions = $this->TransactionPayment->Transaction->find('list');
		$transactionTypes = $this->TransactionPayment->TransactionType->find('list');
		$this->set(compact('transactions', 'transactionTypes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid transaction payment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->TransactionPayment->save($this->data)) {
				$this->Session->setFlash(__('The transaction payment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction payment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->TransactionPayment->read(null, $id);
		}
		$transactions = $this->TransactionPayment->Transaction->find('list');
		$transactionTypes = $this->TransactionPayment->TransactionType->find('list');
		$this->set(compact('transactions', 'transactionTypes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for transaction payment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->TransactionPayment->delete($id)) {
			$this->Session->setFlash(__('Transaction payment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Transaction payment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
