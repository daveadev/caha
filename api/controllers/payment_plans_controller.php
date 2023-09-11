<?php
class PaymentPlansController extends AppController {

	var $name = 'PaymentPlans';

	function index() {
		$this->PaymentPlan->recursive = 0;
		$this->set('PaymentPlans', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid PaymentPlan', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('PaymentPlan', $this->PaymentPlan->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->PaymentPlan->create();
			if ($this->PaymentPlan->save($this->data)) {
				$this->Session->setFlash(__('The PaymentPlan has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The PaymentPlan could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid PaymentPlan', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->PaymentPlan->save($this->data)) {
				$this->Session->setFlash(__('The PaymentPlan has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The PaymentPlan could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->PaymentPlan->read(null, $id);
		}
		
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for PaymentPlan', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->PaymentPlan->delete($id)) {
			$this->Session->setFlash(__('PaymentPlan deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('PaymentPlan was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
