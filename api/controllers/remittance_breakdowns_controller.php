<?php
class RemittanceBreakdownsController extends AppController {

	var $name = 'RemittanceBreakdowns';

	function index() {
		$this->RemitanceBreakdown->recursive = 0;
		$this->set('remittanceBreakdowns', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid remittance', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('remittanceBreakdown', $this->RemitanceBreakdown->read(null, $id));
	}

	function add() {
		//pr($this->data); exit();
		
		if (!empty($this->data)) {
			$this->RemitanceBreakdown->create();
			if ($this->RemitanceBreakdown->save($this->data)) {
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
			if ($this->RemitanceBreakdown->save($this->data)) {
				$this->Session->setFlash(__('The Remittance has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Remittance could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->RemitanceBreakdown->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Remittance', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->RemitanceBreakdown->delete($id)) {
			$this->Session->setFlash(__('Remittance deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Remittance was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	
}
