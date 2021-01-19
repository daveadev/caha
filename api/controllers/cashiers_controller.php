<?php
class CashiersController extends AppController {

	var $name = 'Cashiers';

	function index() {
		$this->Cashier->recursive = 0;
		
		$this->set('cashiers', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid cashier', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('cashier', $this->Cashier->read(null, $id));
	}

	function add() {
		//pr($this->data); exit();
		
		if (!empty($this->data)) {
			$this->Cashier->create();
			
			if ($this->Cashier->save($this->data)) {
				
				$this->Session->setFlash(__('The cashier has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cashier could not be saved. Please, try again.', true));
			}
			
		}
		
		
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid cashier', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Cashier->save($this->data)) {
				$this->Session->setFlash(__('The cashier has been saved', true));
				$this->Cashier(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cashier could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Cashier->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Cashier', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Cashier->delete($id)) {
			$this->Session->setFlash(__('Cashier deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Cashier was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	
}
