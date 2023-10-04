<?php
class PaymentPlansController extends AppController {

	var $name = 'PaymentPlans';
	var $uses =  array('PaymentPlan','Ledger');

	function index() {
		$this->PaymentPlan->recursive = 1;
		$conf = $this->paginate['PaymentPlan'];
		$payPlans = $this->paginate();
		foreach($payPlans as $pi=>$po):
			$payPlans[$pi]['PaymentPlan']['schedule']=$po['PayPlanSchedule'];
		endforeach;
		$this->set('paymentPlans',$payPlans);
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

			$this->data['PaymentPlan']['user']= $this->Auth->user()['User']['username'];;
			$this->data['PaymentPlan']['total_balance']=$this->data['PaymentPlan']['total_due'];
			$this->data['PaymentPlan']['total_payments'] = 0;
			$this->data['PayPlanSchedule']= $this->data['PaymentPlan']['schedule'];
			if ($this->PaymentPlan->saveAll($this->data)) {
				$AID =  $this->data['PaymentPlan']['account_id'];
				$ESP =  $this->data['PaymentPlan']['esp'];
				$this->Ledger->removeEntry($AID,'OLDAC',$ESP,'+');
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
