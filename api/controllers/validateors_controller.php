<?php
class ValidateorsController extends AppController {

	var $name = 'Validateors';
	
	function index() {
		$this->Validateor->recursive = 0;
		$this->set('validateors', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid validateor', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('validateor', $this->Validateor->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Validateor->create();
			if ($this->Validateor->save($this->data)) {
				$this->Session->setFlash(__('The validateor has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The validateor could not be saved. Please, try again.', true));
			}
			
		}
		$accounts = $this->Validateor->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid validateor', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Validateor->save($this->data)) {
				$this->Session->setFlash(__('The validateor has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The validateor could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Validateor->read(null, $id);
		}
		$accounts = $this->Validateor->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for validateor', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Validateor->delete($id)) {
			$this->Session->setFlash(__('validateor deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('validateor was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
