<?php
class AccountHistoriesController extends AppController {

	var $name = 'AccountHistories';

	function index() {
		$this->AccountHistory->recursive = 0;
		$this->set('accountHistories', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account history', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('accountHistory', $this->AccountHistory->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AccountHistory->create();
			if ($this->AccountHistory->save($this->data)) {
				$this->Session->setFlash(__('The account history has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account history could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->AccountHistory->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account history', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AccountHistory->save($this->data)) {
				$this->Session->setFlash(__('The account history has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account history could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AccountHistory->read(null, $id);
		}
		$accounts = $this->AccountHistory->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account history', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AccountHistory->delete($id)) {
			$this->Session->setFlash(__('Account history deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account history was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
