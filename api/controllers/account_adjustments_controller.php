<?php
class AccountAdjustmentsController extends AppController {

	var $name = 'AccountAdjustments';

	function index() {
		$this->AccountAdjustment->recursive = 0;
		$this->set('accountAdjustments', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account adjustment', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('accountAdjustment', $this->AccountAdjustment->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AccountAdjustment->create();
			if ($this->AccountAdjustment->save($this->data)) {
				$this->Session->setFlash(__('The account adjustment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account adjustment could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->AccountAdjustment->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account adjustment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AccountAdjustment->save($this->data)) {
				$this->Session->setFlash(__('The account adjustment has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account adjustment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AccountAdjustment->read(null, $id);
		}
		$accounts = $this->AccountAdjustment->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account adjustment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AccountAdjustment->delete($id)) {
			$this->Session->setFlash(__('Account adjustment deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account adjustment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
