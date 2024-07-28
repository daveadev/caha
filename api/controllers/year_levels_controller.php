<?php
class YearLevelsController extends AppController {

	var $name = 'YearLevels';

	function index() {
		$this->YearLevel->recursive = 0;
		$this->set('yearLevels', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid year level', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('yearLevel', $this->YearLevel->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->YearLevel->create();
			if ($this->YearLevel->save($this->data)) {
				$this->Session->setFlash(__('The year level has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The year level could not be saved. Please, try again.', true));
			}
		}
		$departments = $this->YearLevel->Department->find('list');
		$this->set(compact('departments'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid year level', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->YearLevel->save($this->data)) {
				$this->Session->setFlash(__('The year level has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The year level could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->YearLevel->read(null, $id);
		}
		$departments = $this->YearLevel->Department->find('list');
		$this->set(compact('departments'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for year level', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->YearLevel->delete($id)) {
			$this->Session->setFlash(__('Year level deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Year level was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
