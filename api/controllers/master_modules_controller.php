<?php
class MasterModulesController extends AppController {

	var $name = 'MasterModules';

	function index() {
		$this->MasterModule->recursive = 0;
		$this->set('masterModules', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid master module', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('masterModule', $this->MasterModule->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->MasterModule->create();
			if ($this->MasterModule->saveAll($this->data)) {
				$this->Session->setFlash(__('The master module has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The master module could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid master module', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->MasterModule->save($this->data)) {
				$this->Session->setFlash(__('The master module has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The master module could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->MasterModule->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for master module', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->MasterModule->delete($id)) {
			$this->Session->setFlash(__('Master module deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Master module was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
