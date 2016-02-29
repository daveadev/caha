<?php
class LedgersController extends AppController {

	var $name = 'Ledgers';

	function index() {
		$this->Ledger->recursive = 0;
		$this->set('ledgers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid ledger', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('ledger', $this->Ledger->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Ledger->create();
			if ($this->Ledger->save($this->data)) {
				$this->Session->setFlash(__('The ledger has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ledger could not be saved. Please, try again.', true));
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
}
