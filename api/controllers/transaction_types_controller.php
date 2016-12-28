<?php
class TransactionTypesController extends AppController {

	var $name = 'TransactionTypes';

	function index() {
		$this->TransactionType->recursive = 1;
		$this->paginate = $this->TransactionType->preparePagination($this->paginate);
		$this->set('transactionTypes', $this->paginate());
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
