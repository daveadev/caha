<?php
class DailyTotalCollectionsController extends AppController {

	var $name = 'DailyTotalCollections';

	function index() {
		$this->DailyTotalCollection->recursive = 0;
		$this->set('dailyTotalCollections', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid student', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('dailyTotalCollection', $this->DailyCollection->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->data['DailyTotalCollection']['created'] = date('Y-m-d H:i:s',time());
			$this->DailyTotalCollection->create();
			if ($this->DailyTotalCollection->save($this->data)) {
				$this->Session->setFlash(__('The DailyTotalCollection has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The DailyTotalCollection could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid DailyTotalCollection', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->DailyTotalCollection->save($this->data)) {
				$this->Session->setFlash(__('The DailyTotalCollection has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The DailyTotalCollection could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->DailyTotalCollection->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for DailyTotalCollection', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->DailyTotalCollection->delete($id)) {
			$this->Session->setFlash(__('DailyTotalCollection deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('DailyTotalCollection was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
