<?php
class YearLevelsController extends AppController {

	var $name = 'YearLevels';

	function index() {
		$this->YearLevel->recursive = 0;
		$this->set('yearLevels', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid YearLevel', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('YearLevel', $this->YearLevel->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->YearLevel->create();
			if ($this->YearLevel->save($this->data)) {
				$this->Session->setFlash(__('The YearLevel has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The YearLevel could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid YearLevel', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->YearLevel->save($this->data)) {
				$this->Session->setFlash(__('The YearLevel has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The YearLevel could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->YearLevel->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for YearLevel', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->YearLevel->delete($id)) {
			$this->Session->setFlash(__('YearLevel deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('YearLevel was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
