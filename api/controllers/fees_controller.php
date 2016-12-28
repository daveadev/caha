<?php
class FeesController extends AppController {

	var $name = 'Fees';

	function index() {
		$this->Fee->recursive = 0;
		$this->set('fees', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid fee', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('fee', $this->Fee->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Fee->create();
			if ($this->Fee->save($this->data)) {
				$this->Session->setFlash(__('The fee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fee could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->Fee->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid fee', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Fee->save($this->data)) {
				$this->Session->setFlash(__('The fee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fee could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Fee->read(null, $id);
		}
		$accounts = $this->Fee->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for fee', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Fee->delete($id)) {
			$this->Session->setFlash(__('Fee deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Fee was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
