<?php
class AccountFeesController extends AppController {

	var $name = 'AccountFees';

	function index() {
		//$this->paginate['AccountFee']['contain']=array('Fee','Account');
		$this->paginate['AccountFee']['contain']=array('Fee');
		$fees = $this->paginate();
		if($this->isAPIRequest()){
			foreach($fees as $i=>$fee){
				pr($fee);
			}
		}
		exit();
		$this->set('accountFees', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid account fee', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('accountFee', $this->AccountFee->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->AccountFee->create();
			if ($this->AccountFee->save($this->data)) {
				$this->Session->setFlash(__('The account fee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account fee could not be saved. Please, try again.', true));
			}
		}
		$accounts = $this->AccountFee->Account->find('list');
		$fees = $this->AccountFee->Fee->find('list');
		$this->set(compact('accounts', 'fees'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid account fee', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->AccountFee->save($this->data)) {
				$this->Session->setFlash(__('The account fee has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account fee could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AccountFee->read(null, $id);
		}
		$accounts = $this->AccountFee->Account->find('list');
		$fees = $this->AccountFee->Fee->find('list');
		$this->set(compact('accounts', 'fees'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for account fee', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->AccountFee->delete($id)) {
			$this->Session->setFlash(__('Account fee deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Account fee was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
