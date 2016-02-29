<?php
class AccountSchedulesController extends AppController {

	var $name = 'AccountSchedules';

	function index() {
		$this->AccountSchedule->recursive = 0;
		$this->set('accountSchedules', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account schedule', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('accountSchedule', $this->AccountSchedule->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AccountSchedule->create();
			if ($this->AccountSchedule->save($this->data)) {
				$this->Session->setFlash(__('The account schedule has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account schedule could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->AccountSchedule->Account->find('list');
		$this->set(compact('accounts'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account schedule', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AccountSchedule->save($this->data)) {
				$this->Session->setFlash(__('The account schedule has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account schedule could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AccountSchedule->read(null, $id);
		}
		$accounts = $this->AccountSchedule->Account->find('list');
		$this->set(compact('accounts'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account schedule', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AccountSchedule->delete($id)) {
			$this->Session->setFlash(__('Account schedule deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account schedule was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
