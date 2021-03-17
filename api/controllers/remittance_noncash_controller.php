<?php
class RemittanceNoncashController extends AppController {

	var $name = 'RemittanceNoncashController';

	function index() {
		$this->RemittanceNoncash->recursive = 0;
		$this->set('remittanceNoncash', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid remittance', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('remittanceNoncash', $this->RemittanceNoncash->read(null, $id));
	}

	function add() {
		//pr($this->data); exit();
		
		if (!empty($this->data)) {
			$this->RemittanceNoncash->create();
			if ($this->RemittanceNoncash->save($this->data)) {
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
			if ($this->RemittanceNoncash->save($this->data)) {
				$this->Session->setFlash(__('The Remittance has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Remittance could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->RemittanceNoncash->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Remittance', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RemittanceNoncash->delete($id)) {
			$this->Session->setFlash(__('Remittance deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Remittance was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	
}
