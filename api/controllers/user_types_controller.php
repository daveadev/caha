<?php
class UserTypesController extends AppController {

	var $name = 'UserTypes';

	function index() {
		$this->UserType->recursive = 0;
		$this->set('userTypes', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user type', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('userType', $this->UserType->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->UserType->create();
			if ($this->UserType->save($this->data)) {
				$this->Session->setFlash(__('The user type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user type could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user type', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->UserType->save($this->data)) {
				$this->Session->setFlash(__('The user type has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user type could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->UserType->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user type', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->UserType->delete($id)) {
			$this->Session->setFlash(__('User type deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User type was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
