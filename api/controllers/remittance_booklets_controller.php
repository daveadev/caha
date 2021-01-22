<?php
class RemittanceBookletsController extends AppController {

	var $name = 'RemittanceBookletsController';

	function index() {
		$this->RemittanceBooklets->recursive = 0;
		$this->set('remittanceBooklets', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid remittance', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('remittanceBooklets', $this->RemittanceBooklets->read(null, $id));
	}

	function add() {
		//pr($this->data); exit();
		
		if (!empty($this->data)) {
			$this->RemittanceBooklets->create();
			if ($this->RemittanceBooklets->save($this->data)) {
				$this->Session->setFlash(__('The Remittance has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Remittance could not be saved. Please, try again.', true));
			}
			
		}
		
		
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Remittance', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->RemittanceBooklets->save($this->data)) {
				$this->Session->setFlash(__('The Remittance has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Remittance could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->RemittanceBooklets->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Remittance', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RemittanceBooklets->delete($id)) {
			$this->Session->setFlash(__('Remittance deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Remittance was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	
}
