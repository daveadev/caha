<?php
class AccountTransactionsController extends AppController {

	var $name = 'AccountTransactions';

	function index() {
		$this->AccountTransaction->recursive = 0;
		$this->set('accountTransactions', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account transaction', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('accountTransaction', $this->AccountTransaction->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AccountTransaction->create();
			if ($this->AccountTransaction->save($this->data)) {
				$this->Session->setFlash(__('The account transaction has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account transaction could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->AccountTransaction->Account->find('list');
		$transactionTypes = $this->AccountTransaction->TransactionType->find('list');
		$this->set(compact('accounts', 'transactionTypes'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account transaction', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AccountTransaction->save($this->data)) {
				$this->Session->setFlash(__('The account transaction has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account transaction could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AccountTransaction->read(null, $id);
		}
		$accounts = $this->AccountTransaction->Account->find('list');
		$transactionTypes = $this->AccountTransaction->TransactionType->find('list');
		$this->set(compact('accounts', 'transactionTypes'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account transaction', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AccountTransaction->delete($id)) {
			$this->Session->setFlash(__('Account transaction deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account transaction was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
